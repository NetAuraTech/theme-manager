# Theme Manager Package

A comprehensive Laravel package for managing themes in content management systems. This package provides theme installation, compilation, management, and asset handling capabilities for CMS applications built with the Core CMS package.

## Description

The Theme Manager package extends the Core CMS by providing a complete theme management system. It allows users to upload custom themes, compile SCSS to CSS, manage theme assets, and switch between different themes through an intuitive admin interface. The package includes a default theme with modern design patterns and comprehensive styling.

## Features

- ✅ Theme upload and installation via ZIP files
- ✅ SCSS compilation with Sass
- ✅ CSS minification and optimization
- ✅ Asset management and serving
- ✅ Theme switching through admin interface
- ✅ Default responsive theme included
- ✅ Multi-language theme support
- ✅ Theme caching and performance optimization
- ✅ Job-based theme compilation
- ✅ Theme asset source integration

## Requirements

- PHP ^8.1
- Laravel ^12.0
- Node.js and npm (for theme compilation)
- netauratech/core-cms ^1.0
- ext-zip (for theme uploads)

## Installation

### Via Composer

```bash
composer require netauratech/theme-manager
```

### Manual Installation

1. Clone the repository into your Laravel project
2. Add the dependency to your `composer.json`
3. Run `composer install`

## Configuration

### 1. Service Provider

The service provider is automatically registered. If manual registration is needed:

```php
'providers' => [
    // ...
    Netauratech\ThemeManager\ThemeManagerServiceProvider::class,
],
```

### 2. Publishing Assets

Publish translation files (optional):

```bash
php artisan vendor:publish --tag=theme-manager-translations
```

### 3. Environment Setup

For production environments, ensure Node.js is available:

```env
NODE_PATH=/path/to/node/bin
```

## Usage

### Admin Interface

Access the theme management interface at `/admin/theme`. The interface allows you to:

- Upload new themes via ZIP files
- Activate/deactivate themes
- Compile theme assets
- Delete custom themes (excluding default)

### Theme Structure

A theme should follow this directory structure:

```
theme-name/
├── assets/
│   ├── fonts/           # Font files (.woff, .woff2, .ttf)
│   └── editor/          # Visual editor images
├── css/                 # Compiled CSS files
│   ├── app.css          # Main application styles
│   ├── admin.css        # Admin interface styles
│   └── critical.css     # Critical/above-the-fold CSS
├── scss/                # SCSS source files
│   ├── abstracts/       # Variables, mixins, functions
│   ├── base/            # Reset, typography, base styles
│   ├── components/      # UI components
│   ├── layout/          # Layout components
│   ├── modules/         # Page-specific modules
│   ├── utilities/       # Utility classes
│   ├── vendor/          # Third-party styles
│   ├── app.scss         # Main application entry point
│   ├── admin.scss       # Admin interface entry point
│   └── critical.scss    # Critical CSS entry point
├── js/                  # JavaScript files
│   ├── app.js           # Main application script
│   └── admin.js         # Admin interface & visual editor script
├── views/               # Blade template files
│   ├── assets/          # Asset inclusion templates
│   │   ├── admin/
│   │   │   ├── css.blade.php    # Admin CSS imports
│   │   │   └── js.blade.php     # Admin JS imports
│   │   ├── css.blade.php        # Frontend CSS imports
│   │   └── js.blade.php         # Frontend JS imports
│   ├── component-id.blade.php   # Visual editor components
│   └── [other-views].blade.php  # Override default views
├── lang/                # Translation files
└── preview.png          # Theme preview image
```

### Creating Custom Themes

#### 1. SCSS Architecture

The default theme uses a modular SCSS architecture with three main entry points:

```scss
// app.scss - Main frontend stylesheet
@use "./components";
@use "./base";
@use "./layout";
@use "./utilities";
@use "./vendor";
@forward "./modules";

// admin.scss - Admin interface stylesheet
@use "./abstracts" as *;
@use "./components/bullet";
@use "./components/choices";
// ... admin-specific imports

// critical.scss - Critical above-the-fold CSS
// Contains essential styles for initial page render
```

#### 2. Theme Variables

Customize theme appearance using SCSS variables:

```scss
// abstracts/_tokens.scss
$active-theme: $light; // or $dark
$enable-media-query-dark-mode: false;

// Colors
$color-primary-500: var(--primary-500);
$color-accent-400: var(--accent-400);

// Typography
$font-family-base: 'Atkinson-Hyperlegible', sans-serif;
$heading-font-family: $font-family-base;
```

#### 3. Component Styling

Create component styles following BEM methodology:

```scss
// components/_component-name.scss
.component {
    // Base styles
    
    &__element {
        // Element styles
    }
    
    &--modifier {
        // Modifier styles
    }
}
```

### Theme Compilation

#### Automatic Compilation

Themes are compiled automatically when:
- Uploaded through the admin interface
- Manually triggered via the compile button

#### Manual Compilation

Compile themes programmatically:

```php
use Netauratech\ThemeManager\Jobs\CompileTheme;
use Netauratech\ThemeManager\Jobs\MinifyTheme;

$themePath = $themeManager->getThemePath('theme-name');
CompileTheme::dispatch($themePath);
MinifyTheme::dispatch($themePath);
```

### Asset Management

#### Theme Asset Source

The package provides automatic asset resolution:

```php
// Assets are served from: /assets/{path}?theme={theme-name}
<link rel="stylesheet" href="{{ route('assets.show', ['path' => 'css/app.css']) }}">
```

#### Cache Busting

Use cache busting for asset versioning:

```php
<?php
$cacheBuster = substr(md5(json_encode(now())), 0, 8);
?>
<link rel="stylesheet" href="{{ route('assets.show', ['path' => 'css/app.css']) }}?v={{ $cacheBuster }}">
```

### Theme Development

#### Default Theme Components

The default theme includes many UI components (this is just a selection of examples):

- **Alert System**: Toast notifications with animations
- **Button Variants**: Primary, accent, transparent, link styles
- **Card Components**: Interactive cards with hover effects
- **Form Elements**: Styled inputs, selects, textareas
- **Grid System**: Flexible grid layouts
- **Navigation**: Responsive navigation with mobile support
- **Modals**: Accessible modal dialogs
- **Typography**: Responsive typography scale
- **Captcha**: Puzzle-based CAPTCHA system
- **File Manager**: Media management interface
- **Editor**: Visual content editor components

#### Utility Classes

Comprehensive utility classes for rapid development:

```css
/* Spacing */
.margin-4, .padding-4
.margin-block-4, .padding-block-4

/* Colors */
.clr-primary-500, .bg-primary-500
.clr-neutral-800, .bg-neutral-800

/* Typography */
.fs-600, .fw-bold
.text-center, .uppercase

/* Layout */
.flex-group, .grid, .even-columns
.container, .section
```

### Theme Views

Theme views serve two main purposes:

#### 1. Asset Inclusion Templates

Located in `views/assets/`, these templates handle asset loading:

- `views/assets/css.blade.php` - Frontend CSS imports
- `views/assets/js.blade.php` - Frontend JavaScript imports
- `views/assets/admin/css.blade.php` - Admin CSS imports
- `views/assets/admin/js.blade.php` - Admin JavaScript imports

#### 2. Visual Editor Components

Component views must be named using the component identifier from `admin.js`:

```php
// If your admin.js registers a component with _id: 'hero'
// Create: views/hero.blade.php

// If your admin.js registers a component with _id: 'grid-collage' 
// Create: views/grid-collage.blade.php
```

These views implement the frontend rendering of components registered with the visual editor.

#### 3. View Overrides

You can override any default CMS views by placing them in your theme's views directory with the same path structure.

### Theme Assets

#### Custom Asset Loading

Load theme-specific assets:

```blade
<link rel="stylesheet" href="{{ route('assets.show', ['path' => 'css/custom.css', 'theme' => $themeName]) }}">
```

## API Reference

### ThemeManager Service

```php
// Get active theme
$activeTheme = $themeManager->getActiveTheme();

// Get theme path
$themePath = $themeManager->getThemePath('theme-name');

// Check if uploaded theme
$isUploaded = $themeManager->isUploadedTheme('theme-name');

// Get all available themes
$themes = $themeManager->getAllThemes();

// Clear theme cache
$themeManager->clearCache();
```

### Theme Controller Actions

```php
// Upload theme
POST /admin/theme
// Parameters: zip (file)

// Set active theme
POST /admin/theme/{theme}

// Compile theme
GET /admin/theme/{theme}/compile

// Delete theme
DELETE /admin/theme/{theme}
```

## File Structure

```
src/
├── Http/
│   └── Controllers/
│       └── Admin/
│           └── ThemeController.php     # Admin theme management
├── Jobs/
│   ├── CompileTheme.php               # SCSS compilation job
│   └── MinifyTheme.php                # CSS minification job
├── Listeners/
│   └── ClearThemeCache.php            # Theme cache management
├── Services/
│   ├── ThemeAssetSource.php           # Asset serving
│   └── ThemeManager.php               # Core theme management
├── resources/
│   ├── themes/
│   │   └── default/                   # Default theme files
│   └── views/
│       └── admin/                     # Admin interface views
├── lang/                              # Translation files
├── routes/
│   └── admin.php                      # Admin routes
└── ThemeManagerServiceProvider.php    # Service provider
```

## Default Theme Features

### Design System

- **Color System**: Comprehensive color palette with light/dark mode support
- **Typography Scale**: Responsive typography with fluid scaling
- **Spacing System**: Consistent spacing using custom properties
- **Component Library**: Reusable UI components

### Responsive Design

- Mobile-first approach
- Flexible grid systems
- Responsive typography
- Touch-friendly interactions

### Performance

- Optimized CSS output
- Efficient SCSS compilation
- Asset caching strategies
- Minimal runtime overhead

### Accessibility

- Semantic HTML structure
- ARIA attributes where needed
- Keyboard navigation support
- High contrast color schemes

## Customization

### Color Themes

Create custom color themes:

```scss
$custom-theme: (
    "primary": (
        "500": hsl(200, 100%, 50%),
        // ... other shades
    ),
    "neutral": (
        "800": hsl(210, 10%, 20%),
        // ... other shades
    )
);

$active-theme: $custom-theme;
```

### Typography

Customize fonts and sizes:

```scss
$font-family-base: 'Inter', sans-serif;
$font-family-accent: 'Playfair Display', serif;

$font-sizes: (
    "small": (
        "900": 2.5rem,
        // ... other sizes
    )
);
```

## Events and Hooks

The package dispatches and listens to these events:

- `OptionUpdated`: Clears theme cache when theme option changes
- `LangLoaded`: Loads theme translations

## Performance Considerations

### Caching

- Theme information is cached in database
- Compiled CSS is stored and served statically
- Asset responses include appropriate cache headers

### Compilation

- SCSS compilation runs in background jobs
- CSS minification and autoprefixing
- Production-optimized output

## Troubleshooting

### Common Issues

**Theme not compiling:**
- Ensure Node.js and npm are installed
- Check SCSS syntax in source files
- Verify file permissions

**Assets not loading:**
- Check asset paths in templates
- Verify theme is properly activated
- Clear application cache

**Upload fails:**
- Verify ZIP file structure
- Check storage permissions
- Ensure allowed file types

### Debug Mode

Enable debug output in jobs:

```php
// In CompileTheme or MinifyTheme job
$process->run(function ($type, $buffer) {
    echo $type === Process::ERR ? "❌ $buffer" : "✅ $buffer";
});
```

## Contributing

1. Fork the project
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This package is open-source software licensed under the [MIT license](LICENSE).

## Support

For support or questions:
- Email: contact@netauratech.fr
- Create an issue on GitHub

## Changelog

### v1.0.0
- Initial release
- Theme upload and management
- SCSS compilation system
- Default responsive theme
- Asset serving integration
- Admin interface

---

© 2025 NetAuraTech. All rights reserved.