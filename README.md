# VS Code or Cursor Settings for GiveWP Development

This repository contains standardized VS Code and Cursor configurations for GiveWP ecosystem development in a local WordPress environment, providing **PHPStorm-like capabilities** including automatic code formatting, code standards enforcement, advanced code navigation, and powerful refactoring features.

## 🎯 Installation Location

**⚠️ IMPORTANT: These configuration files must be placed in the ROOT directory of your WordPress installation that contains the GiveWP ecosystem plugins.**

### Where to Place the Files:

```
your-wordpress-site/
├── wp-config.php
├── wp-content/
│   └── plugins/
│       ├── give/
│       ├── give-recurring/
│       ├── give-currency-switcher/
│       └── [other GiveWP plugins...]
├── .vscode/              ← Place these files here
│   ├── settings.json     ← VS Code/Cursor settings
│   ├── extensions.json   ← Recommended extensions
│   └── tasks.json        ← Development tasks
├── .php-cs-fixer.php     ← PHP CS Fixer configuration
├── composer.json         ← PHP dependencies
├── composer.lock         ← PHP version lock
├── .gitignore           ← Git ignored files
├── scripts/             ← Development scripts (auto-created)
│   └── fix-current-file-namespace.php ← Namespace management script
└── README.md            ← This documentation file
```

### Why the WordPress Root?

-   **VS Code/Cursor workspace**: Opens the entire WordPress site as a workspace
-   **Plugin ecosystem access**: Can work on multiple GiveWP plugins simultaneously
-   **Consistent formatting**: All plugins share the same formatting rules
-   **Centralized configuration**: One set of rules for the entire ecosystem

## 📏 Code Standards & Development Features

### PHP Standards & Advanced Features

-   **PSR-12** coding standards for all PHP files
-   **Automatic formatting on save** using PHP CS Fixer (PHPStorm-like behavior)
-   **Automatic import organization** and namespace resolution
-   **Advanced code navigation** with "Go to Definition", "Find All References", and "Go to Implementation"
-   **Intelligent refactoring** with automatic class renaming and usage updates
-   **Automatic DocBlock generation** - Type `/**` and press Enter to generate complete documentation blocks
-   **Excluded from auto-formatting**: Files in `includes/` directories (legacy code)
-   **Excluded from auto-formatting**: `vendor/`, `node_modules/`, `build/`, `dist/`, `wp-content/uploads/`, `wp-content/cache/`

### JavaScript/TypeScript Standards & Features

-   **Prettier** formatting with custom rules (configured in `.vscode/settings.json`):
-   **Tab Width**: 4 spaces
-   **Print Width**: 120 characters
-   **Single Quotes**: Enabled
-   **Trailing Comma**: ES5 style
-   **Bracket Spacing**: Disabled
-   **Semicolons**: Required
-   **Arrow Function Parentheses**: Avoided when possible
-   **ESLint** for code quality and consistency
-   **Automatic import organization** on save
-   **Advanced IntelliSense** with enhanced autocomplete and error detection
-   **Excluded from auto-formatting**: `node_modules/`, `build/`, `dist/`

### Important Note

Legacy files within `includes/` directories will **NOT** have automatic formatting applied when saving. This prevents potential issues with existing code that may not follow current standards.

### 🚀 PHPStorm-Like Features

This configuration transforms VS Code/Cursor into a powerful PHP development environment with features comparable to PHPStorm:

-   **Automatic Code Formatting**: Code is automatically formatted to PSR-12 standards when you save files
-   **Advanced Code Navigation**:
    -   `Ctrl+Click` or `F12` to go to definition
    -   `Shift+F12` to find all references
    -   `Ctrl+Shift+O` to navigate to symbols in file
-   **Intelligent Refactoring**:
    -   Press `F2` on any class name, method, or variable to rename it across the entire codebase
    -   Namespace resolution and automatic use statement generation
-   **Enhanced Documentation**:
    -   Type `/**` and press Enter to automatically generate complete DocBlocks
    -   Intelligent parameter and return type suggestions
-   **WordPress Integration**: Advanced understanding of WordPress hooks, functions, and patterns

#### 🔧 PHP Namespace Management Script

The `scripts/fix-current-file-namespace.php` script provides **PHPStorm-like namespace management** for PHP files when they are moved between directories. This is a **manual alternative** to PHPStorm's automatic namespace refactoring feature, which is not available in VS Code/Cursor for PHP files.

##### When to Use

Use this script when you:

-   Move a PHP file **within the `src/` directory** to another location
-   Need to update the namespace declaration to match the new directory structure
-   Want to automatically update all references to the moved class in other files

**⚠️ Important**: This script only works for files that are **inside the `src/` directory**. However, it can update references to the moved class in **any PHP file** within the plugins, including files in `includes/`, `admin/`, and other directories.

##### How to Use

**Method 1: Command Palette (Recommended)**

1. **Open the file** you want to fix in the editor
2. **Press `Ctrl+Shift+P`** (or `Cmd+Shift+P` on Mac) to open the Command Palette
3. **Type "Tasks: Run Task"** and select it
4. **Select "Fix Current File Namespace"** from the task list
5. The script will automatically:
    - Detect the current file path
    - Calculate the correct namespace based on PSR-4 standards
    - Update the namespace declaration in the file
    - Find and update all references in other files

**Method 2: Manual Command**

```bash
php scripts/fix-current-file-namespace.php <path-to-file>
```

Example:

```bash
php scripts/fix-current-file-namespace.php wp-content/plugins/give/src/API/REST/V3/Routes/Controllers/SubscriptionController.php
```

##### What the Script Does

1. **Analyzes the file**: Reads the current namespace and file location
2. **Calculates correct namespace**: Based on PSR-4 standards and the file's directory structure
3. **Updates namespace declaration**: Modifies the `namespace` statement at the top of the file
4. **Updates references**: Finds all `use` statements in other files and updates them to the new namespace
5. **Provides feedback**: Shows what changes were made and which files were updated

##### ⚠️ Important Warnings

**This script is experimental and should be used with caution:**

1. **Always verify changes**: Don't trust the script blindly - review the changes it makes
2. **Check for syntax errors**: Run `php -l <filename>` to verify the file has no syntax errors
3. **Test functionality**: Ensure the updated code still works as expected
4. **Manual verification**: Check that all references were updated correctly

##### Limitations

-   **Manual process**: Unlike PHPStorm, you must run the script manually for each moved file
-   **Experimental nature**: The script may not handle all edge cases perfectly
-   **No undo functionality**: Changes are applied directly to files
-   **Limited to PSR-4**: Designed for GiveWP's PSR-4 namespace structure
-   **Source files only**: Only works for files **inside the `src/` directory** (but can update references anywhere)

##### Example Output

```
🔍 Analyzing file: wp-content/plugins/give/src/API/REST/V3/Routes/Controllers/SubscriptionController.php
📁 Current directory: wp-content/plugins/give/src/API/REST/V3/Routes/Controllers
🏷️  Current namespace: Give\API\REST\V3\Routes\Subscriptions
🎯 Expected namespace: Give\API\REST\V3\Routes\Controllers

🔄 Updating namespace...
✅ Namespace updated in file
🔗 Updating references in other files...
  ✓ Updated: wp-content/plugins/give/src/API/REST/V3/Routes/ServiceProvider.php
✅ References updated in 1 files
🎉 Process completed!
```

##### Troubleshooting

-   **Script not found**: Ensure the `scripts/` directory exists in your project root
-   **Permission errors**: Make sure the script has execute permissions
-   **Namespace not updated**: Check if the file path is correct and follows PSR-4 structure
-   **References not found**: The script searches in all PHP files within `wp-content/plugins/*/` directories

## 🏗️ Project Structure Philosophy

This configuration is designed for **GiveWP ecosystem development** in a local WordPress development environment. It follows a **dual-structure approach** that can be applied to multiple plugins:

### Target Environment

-   **Local WordPress development site** with GiveWP ecosystem
-   **Multiple plugins**: `give` (main plugin), `give-recurring`, `give-currency-switcher`, `give-fee-recovery`, etc.
-   **Each plugin** can follow the same dual-structure pattern

### Modern Development (`src/` directory)

-   **Modern PHP practices**: Object-oriented programming, namespaces, PSR-12 standards
-   **Automatic formatting**: All files in `src/` follow strict PSR-12 formatting
-   **Namespace organization**: Proper use of PHP namespaces and autoloading
-   **Clean architecture**: Separation of concerns, dependency injection, etc.

### Legacy Code (`includes/` directory)

-   **Legacy WordPress code**: Older code that doesn't follow modern standards
-   **Minimal modifications**: Code that should be touched as little as possible
-   **No auto-formatting**: Prevents breaking existing functionality
-   **Gradual migration**: Can be refactored over time to move to `src/`

### Example Structure

```
wp-content/plugins/
├── give/
│   ├── src/           # Modern code (auto-formatted)
│   └── includes/      # Legacy code (no auto-formatting)
├── give-recurring/
│   ├── src/           # Modern code (auto-formatted)
│   └── includes/      # Legacy code (no auto-formatting)
├── give-currency-switcher/
│   ├── src/           # Modern code (auto-formatted)
│   └── includes/      # Legacy code (no auto-formatting)
└── give-fee-recovery/
    ├── src/           # Modern code (auto-formatted)
    └── includes/      # Legacy code (no auto-formatting)
```

### Why This Approach?

-   **Safety**: Prevents accidental breaking of legacy functionality
-   **Gradual modernization**: Allows incremental code improvements
-   **Team productivity**: New code follows standards, old code remains stable
-   **WordPress compatibility**: Maintains compatibility with existing WordPress patterns
-   **Ecosystem consistency**: All GiveWP plugins can follow the same structure

## 📋 Prerequisites

-   [Visual Studio Code](https://code.visualstudio.com/) or [Cursor](https://cursor.sh/)
-   [Composer](https://getcomposer.org/) installed globally
-   [Node.js](https://nodejs.org/) (for some JavaScript/TypeScript extensions)

## 🚀 Initial Setup

### 1. Install Composer Dependencies

Run the following command in the project root to install PHP formatting tools:

```bash
composer install
```

### 2. Install Essential Extensions

The following extensions are **required** for the configurations to work properly:

#### PHP Development

-   **PHP Intelephense** (`bmewburn.vscode-intelephense-client`)

    -   Provides **PHPStorm-like IntelliSense**, autocomplete, and navigation for PHP
    -   **Advanced code analysis** and semantic understanding
    -   **Intelligent refactoring** with automatic usage updates
    -   **Enhanced code navigation** with "Go to Definition", "Find All References", and "Go to Implementation"
    -   **Automatic DocBlock generation** - Type `/**` and press Enter
    -   Configured to work only with IntelliSense (formatting disabled)
    -   **⚠️ EXTREMELY IMPORTANT**: Purchase the **lifetime license** for premium features and superior PHP code navigation
    -   **Premium features include**: Advanced code analysis, better autocomplete, improved refactoring tools, and enhanced code navigation
    -   **Without the license**: Limited functionality and basic IntelliSense only

-   **PHP CS Fixer** (`junstyle.php-cs-fixer`)
    -   **Automatic PHP code formatting** following PSR-12 (PHPStorm-like behavior)
    -   Configured to use the version installed via Composer
    -   **Formats automatically on save** - no manual formatting needed
    -   **Automatic import organization** and namespace resolution

#### JavaScript/TypeScript Development

-   **Prettier** (`esbenp.prettier-vscode`)

    -   **Automatic code formatter** for JS, TS, JSON, CSS, SCSS (PHPStorm-like behavior)
    -   **Formats automatically on save** - no manual formatting needed
    -   Uses settings from `.prettierrc.json`

-   **ESLint** (`dbaeumer.vscode-eslint`)

    -   **Advanced linting** for JavaScript/TypeScript
    -   **Real-time code validation** and error detection
    -   **Automatic fix suggestions** on save

-   **TypeScript Next** (`ms-vscode.vscode-typescript-next`)
    -   **Enhanced TypeScript support** with advanced IntelliSense
    -   **Improved type checking** and error detection

### 2.5. ⚠️ CRITICAL: Intelephense Lifetime License

**The PHP Intelephense extension requires a lifetime license for optimal PHP development experience.**

#### Why the Lifetime License is Essential:

-   **Advanced Code Analysis**: Superior understanding of PHP code structure and relationships
-   **Enhanced Autocomplete**: More accurate and context-aware code suggestions
-   **Intelligent Refactoring**: Advanced tools for renaming, moving, and restructuring code (F2 key functionality)
-   **Superior Navigation**: Advanced "Go to Definition", "Find All References", and "Go to Implementation"
-   **Better Error Detection**: More accurate syntax and semantic error detection
-   **Namespace Management**: Enhanced namespace resolution and automatic import suggestions
-   **WordPress Integration**: Better understanding of WordPress hooks, functions, and patterns
-   **Automatic DocBlock Generation**: Complete documentation block generation with `/**` + Enter

#### Without the License:

-   Limited IntelliSense functionality
-   Basic autocomplete only
-   Reduced code analysis capabilities
-   Missing advanced navigation features
-   No intelligent refactoring (F2 key won't work properly)
-   Limited DocBlock generation capabilities

#### Purchase Information:

-   **One-time payment**: Lifetime access to all premium features
-   **Available at**: [Intelephense Premium](https://intelephense.com/premium)
-   **Investment**: Essential for professional PHP development

**⚠️ Note**: This recommendation is purely technical and based on the necessity of premium features for optimal PHP development experience. We do not receive any commission, affiliate benefits, or compensation from Intelephense. The recommendation is made solely because the premium features are essential for the advanced code navigation, refactoring, and IntelliSense capabilities described in this configuration.

### 3. Recommended Extensions (Optional)

#### Productivity

-   **Import Cost** (`wix.vscode-import-cost`) - Shows imported package sizes
-   **Auto Rename Tag** (`formulahendry.auto-rename-tag`) - Auto-rename paired HTML/XML tags
-   **Auto Close Tag** (`formulahendry.auto-close-tag`) - Auto-close HTML/XML tags
-   **Path Intellisense** (`christian-kohler.path-intellisense`) - File path autocomplete
-   **npm Intellisense** (`christian-kohler.npm-intellisense`) - Autocomplete for npm modules
-   **Bracket Pair Colorizer 2** (`CoenraadS.bracket-pair-colorizer-2`) - Colorizes matching brackets

#### Git and Version Control

-   **Git Toolbox** (`gondor.git-toolbox`) - Git Toolbox is a powerful collection of Git extensions
-   **Open in GitHub, GitLab, Gitea, Bitbucket, VisualStudio.com** (`ziyasal.vscode-open-in-github`) - Open files in Git platforms

#### WordPress Development

-   **WordPress Hooks** (`johnbillion.vscode-wordpress-hooks`) - WordPress hooks IntelliSense

#### Advanced PHP

-   **PHP Debug** (`xdebug.php-debug`) - PHP debugging support

## ⚙️ Included Configurations

### Automatic Formatting & Code Standards

-   **PHP**: Automatic formatting on save using PHP CS Fixer (PSR-12) - PHPStorm-like behavior
-   **JavaScript/TypeScript**: Automatic formatting on save using Prettier
-   **CSS/SCSS**: Automatic formatting on save using Prettier
-   **JSON**: Automatic formatting on save using Prettier

### Advanced Code Navigation & Refactoring

-   **PHP Navigation**: "Go to Definition" (F12), "Find All References" (Shift+F12), "Go to Implementation"
-   **Intelligent Refactoring**: Press F2 on any class, method, or variable to rename across the entire codebase
-   **Automatic Import Organization**: JavaScript/TypeScript imports organized on save
-   **PHP Namespace Management**: Automatic namespace resolution and use statement generation via PHP CS Fixer
-   **DocBlock Generation**: Type `/**` and press Enter for automatic documentation block generation

### Exclusions

-   `vendor/` - Composer dependencies
-   `node_modules/` - Node.js dependencies
-   `build/` and `dist/` - Build files
-   `wp-content/uploads/` and `wp-content/cache/` - WordPress uploads and cache
-   `**/includes/**` - Legacy code directories (excluded from auto-formatting to preserve existing functionality)

## 📁 File Structure

```
.
├── .vscode/
│   ├── settings.json      # VS Code/Cursor settings (includes Prettier config)
│   └── extensions.json    # Recommended extensions
├── .php-cs-fixer.php      # PHP CS Fixer configuration
├── composer.json          # PHP dependencies
├── composer.lock          # PHP version lock
├── .gitignore            # Git ignored files
└── README.md             # This file
```

## 🔧 Customization

### Adding New Formatting Rules

To add specific PHP CS Fixer rules, edit the `.php-cs-fixer.php` file.

To customize Prettier, edit the `.vscode/settings.json` file (Prettier settings are configured inline).

### Project-Specific Settings

Settings are centralized in the `.vscode/settings.json` file. You can add project-specific settings in this file. These settings work identically in both VS Code and Cursor.

## 🐛 Troubleshooting

### PHP CS Fixer not working

1. Check if Composer is installed
2. Run `composer install` in the project root
3. Check if the PHP CS Fixer extension is installed

### Prettier not formatting

1. Check if the Prettier extension is installed
2. Check if Prettier settings are configured in `.vscode/settings.json`
3. Restart VS Code/Cursor

### PHP IntelliSense not working

1. Check if the PHP Intelephense extension is installed
2. Check if the file is being recognized as PHP
3. Wait a few seconds for IntelliSense to load

## 📝 Notes

-   Configurations are optimized for WordPress development
-   PHP CS Fixer uses the version installed via Composer to ensure consistency
-   All essential extensions are listed in `.vscode/extensions.json`
-   The `.gitignore` is configured to include only the necessary configuration files
