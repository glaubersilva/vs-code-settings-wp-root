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
    echo "✗ Usage: php scripts/fix-current-file-namespace.php <file_path>\n";
    echo "Example: php scripts/fix-current-file-namespace.php wp-content/plugins/give/src/API/REST/V3/Routes/Controllers/SubscriptionController.php\n";
    exit(1);
}

$filePath = $argv[1];

if (!file_exists($filePath)) {
    echo "✗ File not found: $filePath\n";
    exit(1);
}

echo "●●● Analyzing file: $filePath\n\n";

// Read file content
$content = file_get_contents($filePath);

// Extract information
$className = extractClassName($content);
$currentNamespace = extractNamespace($content);
$expectedNamespace = calculateNamespaceFromPath(dirname($filePath));

echo "→ Current directory: " . dirname($filePath) . "\n";
echo "→ Current namespace: $currentNamespace\n";
echo "→ Expected namespace: $expectedNamespace\n";

if ($currentNamespace === $expectedNamespace) {
    echo "\n✅ Namespace is already correct!\n\n";
} else {
    echo "\n↻ Updating namespace...\n";

    // Update namespace in file
    $updatedContent = updateFileNamespace($content, $expectedNamespace);
    file_put_contents($filePath, $updatedContent);

    echo "\n✅ Namespace updated in file\n\n";
}

// Update references in other files
if ($className) {
    echo "\n↻ Updating references in other files...\n";
    // Always update references, even if the main file namespace is already correct
    updateReferences($className, $currentNamespace, $expectedNamespace);
}

echo "\n♥♥♥ Process completed! ♥♥♥\n\n";

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

    // Extract plugin name and remaining path
    $pathParts = explode('/', $path, 2);
    if (count($pathParts) >= 2) {
        $pluginName = $pathParts[0];
        $remainingPath = $pathParts[1];

        // Convert path to PSR-4 namespace
        $namespace = str_replace('/', '\\', $remainingPath);

        // Capitalize each namespace part
        $parts = explode('\\', $namespace);
        $parts = array_map('ucfirst', $parts);
        $namespace = implode('\\', $parts);

        // Add plugin base namespace (for give plugins, use "Give")
        if (stripos($pluginName, 'give') !== false) {
            $namespace = 'Give\\' . $namespace;
        } else {
            $pluginNamespace = ucfirst($pluginName);
            $namespace = $pluginNamespace . '\\' . $namespace;
        }

        return $namespace;
    }

    // Fallback for files directly in plugin root
    $pluginName = basename($path);
    if (stripos($pluginName, 'give') !== false) {
        return 'Give';
    }
    return ucfirst($pluginName);
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
    $files = findFilesWithReferences($className);

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
                        echo "  \n✓ Fixing incorrect namespace on line " . ($i + 1) . " in $file ($currentNamespace -> $newNamespace)\n";
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
            echo "  \n► File updated: $file\n";
        }
    }

    echo "\n✅ References updated in $updatedFiles files\n\n";
}

/**
 * Find files that contain references to the specified class using grep
 */
function findFilesWithReferences($className)
{
    $files = [];

    // Directories to exclude from grep search (common in WordPress plugins)
    $excludeDirs = [
        'vendor',
        'node_modules',
    ];

    // Search for use statements containing the class name only in "give" plugins
    $pluginsDir = 'wp-content/plugins';
    if (is_dir($pluginsDir)) {
        // Find all directories that contain "give" in the name, excluding unwanted directories
        $givePlugins = [];
        $plugins = glob($pluginsDir . '/*', GLOB_ONLYDIR);

        foreach ($plugins as $plugin) {
            $pluginName = basename($plugin);
            $pluginPath = $plugin;

            // Check if this plugin path contains any excluded directories
            $shouldExclude = false;
            foreach ($excludeDirs as $excludeDir) {
                if (strpos($pluginPath, '/' . $excludeDir . '/') !== false ||
                    strpos($pluginPath, '\\' . $excludeDir . '\\') !== false) {
                    $shouldExclude = true;
                    break;
                }
            }

            // Only include if it contains "give" and is not in an excluded directory
            if (!$shouldExclude && stripos($pluginName, 'give') !== false) {
                $givePlugins[] = $plugin;
            }
        }

        if (empty($givePlugins)) {
            echo "! No 'give' plugins found\n";
            $files = [];
        } else {
            echo "\n●●● Searching in Give plugins: " . implode(', ', array_map('basename', $givePlugins)) . "\n";

            // Execute grep command on all PHP files in give plugins, excluding specified directories
            $output = [];
            $returnCode = 0;
            $searchPaths = $givePlugins;

            if (empty($searchPaths)) {
                echo "! No Give plugins found\n";
                $files = [];
            } else {
                echo "\n●●● Searching in all PHP files within Give plugins (" . count($searchPaths) . " plugins)\n";

                // Build exclude patterns for grep
                $excludePatterns = [];
                foreach ($excludeDirs as $dir) {
                    $excludePatterns[] = "--exclude-dir=$dir";
                }

                $searchPathsString = implode(' ', array_map('escapeshellarg', $searchPaths));
                $excludePatternsString = implode(' ', $excludePatterns);
                exec("grep -r -l --include='*.php' $excludePatternsString 'use.*$className' $searchPathsString", $output, $returnCode);

                // grep returns 1 when no matches found, which is normal
                if ($returnCode === 0 || $returnCode === 1) {
                    $files = $output;
                } else {
                    echo "! Warning: grep command failed, no files will be updated\n";
                    $files = [];
                }
            }
        }
    }

    return $files;
}
