<?php

/**
 * Fix Current File Namespace
 *
 * Simple script that fixes the namespace of the currently open file
 * based on the directory where it is located.
 *
 * Usage: php scripts/fix-current-file-namespace.php <file_path>
 */

if ($argc < 2) {
    echo "❌ Usage: php scripts/fix-current-file-namespace.php <file_path>\n";
    echo "Example: php scripts/fix-current-file-namespace.php wp-content/plugins/give/src/API/REST/V3/Routes/Controllers/SubscriptionController.php\n";
    exit(1);
}

$filePath = $argv[1];

if (!file_exists($filePath)) {
    echo "❌ File not found: $filePath\n";
    exit(1);
}

echo "🔍 Analyzing file: $filePath\n";

// Read file content
$content = file_get_contents($filePath);

// Extract information
$className = extractClassName($content);
$currentNamespace = extractNamespace($content);
$expectedNamespace = calculateNamespaceFromPath(dirname($filePath));

echo "📁 Current directory: " . dirname($filePath) . "\n";
echo "🏷️  Current namespace: $currentNamespace\n";
echo "🎯 Expected namespace: $expectedNamespace\n";

if ($currentNamespace === $expectedNamespace) {
    echo "✅ Namespace is already correct!\n";
    exit(0);
}

echo "\n🔄 Updating namespace...\n";

// Update namespace in file
$updatedContent = updateFileNamespace($content, $expectedNamespace);
file_put_contents($filePath, $updatedContent);

echo "✅ Namespace updated in file\n";

// Update references in other files
if ($className) {
    echo "🔗 Updating references in other files...\n";
    updateReferences($className, $currentNamespace, $expectedNamespace);
}

echo "🎉 Process completed!\n";

/**
 * Extract class name from PHP file content
 */
function extractClassName($content)
{
    if (preg_match('/class\s+(\w+)/', $content, $matches)) {
        return $matches[1];
    }
    return null;
}

/**
 * Extract namespace from PHP file content
 */
function extractNamespace($content)
{
    if (preg_match('/namespace\s+([^;]+);/', $content, $matches)) {
        return trim($matches[1]);
    }
    return '';
}

/**
 * Calculate namespace based on directory path following PSR-4 pattern
 */
function calculateNamespaceFromPath($path)
{
    // Convert to relative path if absolute
    $path = str_replace('\\', '/', $path);

    // Extract relative path to plugin
    if (strpos($path, 'wp-content/plugins/') !== false) {
        $path = substr($path, strpos($path, 'wp-content/plugins/') + strlen('wp-content/plugins/'));
    }

    // Remove plugin name (give) and src/
    if (preg_match('/^give\/src\/(.+)$/', $path, $matches)) {
        $path = $matches[1];
    }

    // Convert path to PSR-4 namespace
    $namespace = str_replace('/', '\\', $path);

    // Capitalize each namespace part
    $parts = explode('\\', $namespace);
    $parts = array_map('ucfirst', $parts);
    $namespace = implode('\\', $parts);

    // Add plugin base namespace
    $namespace = 'Give\\' . $namespace;

    return $namespace;
}

/**
 * Update namespace in file content
 */
function updateFileNamespace($content, $newNamespace)
{
    // Find exact namespace line
    $lines = explode("\n", $content);
    $updated = false;

    for ($i = 0; $i < count($lines); $i++) {
        $line = $lines[$i];
        // Look only for namespace declarations at the beginning of the line
        if (preg_match('/^\s*namespace\s+[^;]+;\s*$/', $line)) {
            $lines[$i] = preg_replace('/^\s*namespace\s+[^;]+;\s*$/', "namespace $newNamespace;", $line);
            $updated = true;
            break; // Only update the first occurrence
        }
    }

    // If namespace not found, add after <?php
    if (!$updated) {
        for ($i = 0; $i < count($lines); $i++) {
            if (trim($lines[$i]) === '<?php') {
                array_splice($lines, $i + 1, 0, ['', "namespace $newNamespace;"]);
                break;
            }
        }
    }

    return implode("\n", $lines);
}

/**
 * Update references in other files
 */
function updateReferences($className, $oldNamespace, $newNamespace)
{
    $searchDir = __DIR__ . '/..';
    $files = findPhpFiles($searchDir);

    $updatedFiles = 0;

    foreach ($files as $file) {
        $content = file_get_contents($file);
        $originalContent = $content;

        // Update only exact use statements
        if ($oldNamespace && $newNamespace) {
            $lines = explode("\n", $content);
            $updated = false;

            for ($i = 0; $i < count($lines); $i++) {
                $line = $lines[$i];

                // Pattern 1: exact use statement with old namespace
                if (preg_match('/^\s*use\s+\\' . preg_quote($oldNamespace, '/') . '\\\\' . preg_quote($className, '/') . '\s*;\s*$/', $line)) {
                    $lines[$i] = preg_replace(
                        '/^\s*use\s+\\' . preg_quote($oldNamespace, '/') . '\\\\' . preg_quote($className, '/') . '\s*;\s*$/',
                        "use $newNamespace\\$className;",
                        $line
                    );
                    $updated = true;
                    echo "  ✓ Updating use statement on line " . ($i + 1) . " in $file\n";
                }

                // Pattern 2: use statement with different namespace (case file was moved)
                // Look for any use statement that contains the class
                elseif (preg_match('/^\s*use\s+([^;]+)\\\\' . preg_quote($className, '/') . '\s*;\s*$/', $line, $matches)) {
                    $currentNamespace = $matches[1];
                    // If current namespace is not the correct new namespace, update
                    if ($currentNamespace !== $newNamespace) {
                        $lines[$i] = preg_replace(
                            '/^\s*use\s+([^;]+)\\\\' . preg_quote($className, '/') . '\s*;\s*$/',
                            "use $newNamespace\\$className;",
                            $line
                        );
                        $updated = true;
                        echo "  ✓ Fixing incorrect namespace on line " . ($i + 1) . " in $file ($currentNamespace -> $newNamespace)\n";
                    }
                }
            }

            if ($updated) {
                $content = implode("\n", $lines);
            }
        }

        if ($content !== $originalContent) {
            file_put_contents($file, $content);
            $updatedFiles++;
            echo "  ✓ File updated: $file\n";
        }
    }

    echo "✅ References updated in $updatedFiles files\n";
}

/**
 * Find all PHP files
 */
function findPhpFiles($dir)
{
    $files = [];

    // Look for src/ folders inside wp-content/plugins/
    $pluginsDir = $dir . '/wp-content/plugins';
    if (is_dir($pluginsDir)) {
        $plugins = glob($pluginsDir . '/*', GLOB_ONLYDIR);

        foreach ($plugins as $pluginDir) {
            $srcDir = $pluginDir . '/src';
            if (is_dir($srcDir)) {
                $iterator = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($srcDir, RecursiveDirectoryIterator::SKIP_DOTS)
                );

                foreach ($iterator as $file) {
                    if ($file->getExtension() === 'php') {
                        $files[] = $file->getPathname();
                    }
                }
            }
        }
    }

    return $files;
}
