# VS Code Standards for GiveWP Development

This repository contains standardized VS Code configurations for GiveWP ecosystem development in a local WordPress environment, including automatic code formatting, linting, and essential extensions.

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
├── .php-cs-fixer.php     ← Place these files here
├── composer.json         ← Place these files here
├── composer.lock         ← Place these files here
├── .gitignore           ← Place these files here
└── README.md            ← Place these files here
```

### Why the WordPress Root?

-   **VS Code workspace**: Opens the entire WordPress site as a workspace
-   **Plugin ecosystem access**: Can work on multiple GiveWP plugins simultaneously
-   **Consistent formatting**: All plugins share the same formatting rules
-   **Centralized configuration**: One set of rules for the entire ecosystem

## 📏 Code Standards

### PHP Standards

-   **PSR-12** coding standards for all PHP files
-   Automatic formatting on save using PHP CS Fixer
-   Automatic import organization and namespace resolution
-   **Excluded from auto-formatting**: Files in `includes/` directories (legacy code)
-   **Excluded from auto-formatting**: `vendor/`, `node_modules/`, `build/`, `dist/`, `wp-content/uploads/`, `wp-content/cache/`

### JavaScript/TypeScript Standards

-   **Prettier** formatting with custom rules (configured in `.vscode/settings.json`):
-   **Tab Width**: 4 spaces
-   **Print Width**: 120 characters
-   **Single Quotes**: Enabled
-   **Trailing Comma**: ES5 style
-   **Bracket Spacing**: Disabled
-   **Semicolons**: Required
-   **Arrow Function Parentheses**: Avoided when possible
-   **ESLint** for code quality and consistency
-   Automatic import organization on save
-   **Excluded from auto-formatting**: `node_modules/`, `build/`, `dist/`

### Important Note

Legacy files within `includes/` directories will **NOT** have automatic formatting applied when saving. This prevents potential issues with existing code that may not follow current standards.

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

    -   Provides IntelliSense, autocomplete, and navigation for PHP
    -   Configured to work only with IntelliSense (formatting disabled)

-   **PHP CS Fixer** (`junstyle.php-cs-fixer`)
    -   Automatic PHP code formatting following PSR-12
    -   Configured to use the version installed via Composer
    -   Formats automatically on save

#### JavaScript/TypeScript Development

-   **Prettier** (`esbenp.prettier-vscode`)

    -   Code formatter for JS, TS, JSON, CSS, SCSS
    -   Configured to format automatically on save
    -   Uses settings from `.prettierrc.json`

-   **ESLint** (`dbaeumer.vscode-eslint`)

    -   Linting for JavaScript/TypeScript
    -   Validates code automatically

-   **TypeScript Next** (`ms-vscode.vscode-typescript-next`)
    -   Enhanced TypeScript support

### 3. Recommended Extensions (Optional)

#### Productivity

-   **Import Cost** (`wix.vscode-import-cost`) - Shows imported package sizes
-   **Auto Rename Tag** (`formulahendry.auto-rename-tag`) - Auto-rename paired HTML/XML tags
-   **Auto Close Tag** (`formulahendry.auto-close-tag`) - Auto-close HTML/XML tags
-   **Path Intellisense** (`christian-kohler.path-intellisense`) - File path autocomplete
-   **npm Intellisense** (`christian-kohler.npm-intellisense`) - Autocomplete for npm modules
-   **Bracket Pair Colorizer 2** (`CoenraadS.bracket-pair-colorizer-2`) - Colorizes matching brackets

#### Git and Version Control

-   **Git Toolbox** (`eamodio.gitlens`) - Enhanced Git capabilities and visualization
-   **Open in GitHub, GitLab, Gitea, Bitbucket, VisualStudio.com** (`ziyasal.vscode-open-in-github`) - Open files in Git platforms

#### WordPress Development

-   **WordPress Hooks** (`johnbillion.vscode-wordpress-hooks`) - WordPress hooks IntelliSense

#### Advanced PHP

-   **PHP DocBlocker** (`neilbrayfield.php-docblocker`) - PHP DocBlock generator
-   **PHP Debug** (`xdebug.php-debug`) - PHP debugging support
-   **Tag Highlight Matching** (`vincaslt.highlight-matching-tag`) - Highlights matching HTML/XML tags

## ⚙️ Included Configurations

### Automatic Formatting

-   **PHP**: Automatic formatting on save using PHP CS Fixer (PSR-12)
-   **JavaScript/TypeScript**: Automatic formatting on save using Prettier
-   **CSS/SCSS**: Automatic formatting on save using Prettier
-   **JSON**: Automatic formatting on save using Prettier

### Import Organization

-   Automatic import organization in JavaScript/TypeScript on save
-   Automatic PHP namespace resolution and import organization via PHP CS Fixer

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
│   ├── settings.json      # VS Code settings (includes Prettier config)
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

Settings are centralized in the `.vscode/settings.json` file. You can add project-specific settings in this file.

## 🐛 Troubleshooting

### PHP CS Fixer not working

1. Check if Composer is installed
2. Run `composer install` in the project root
3. Check if the PHP CS Fixer extension is installed

### Prettier not formatting

1. Check if the Prettier extension is installed
2. Check if Prettier settings are configured in `.vscode/settings.json`
3. Restart VS Code

### PHP IntelliSense not working

1. Check if the PHP Intelephense extension is installed
2. Check if the file is being recognized as PHP
3. Wait a few seconds for IntelliSense to load

## 📝 Notes

-   Configurations are optimized for WordPress development
-   PHP CS Fixer uses the version installed via Composer to ensure consistency
-   All essential extensions are listed in `.vscode/extensions.json`
-   The `.gitignore` is configured to include only the necessary configuration files
