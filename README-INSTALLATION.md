# Hall of Fame Plugin Installation & Theme Setup

## Quick Start (Works with Any Theme)

The Hall of Fame plugin works **out-of-the-box** with any Botble CMS theme.
No configuration required!

```bash
# 1. Install the plugin
composer require whozidis/hall-of-fame

# 2. Run migrations
php artisan migrate

# 3. That's it! Plugin works with your theme automatically
```

## Theme Compatibility

### ✅ Automatic Compatibility

- **Universal views** that work with any theme
- **Smart theme detection** and fallback system
- **No vendor overrides** required
- **No manual configuration** needed

### 🎨 Optional Theme Customization

If you want to customize the appearance for your specific theme:

#### Method 1: Theme Directory Override (Recommended)

Create custom views in your active theme:

```php
themes/[your-theme]/views/plugins/hall-of-fame/
├── hall-of-fame.blade.php          # Main hall of fame page
├── certificates.blade.php          # Certificates listing
├── certificate-verify.blade.php    # Certificate verification
└── certificate-details.blade.php   # Certificate details
```

#### Method 2: Quick Setup Command

```bash
# Auto-generate customizable views for your theme
php artisan hall-of-fame:setup-theme

# Or for a specific theme
php artisan hall-of-fame:setup-theme martfury
```

## Current Theme Support Status

| Theme | Status | Notes |
|-------|--------|-------|
| **Any Theme** | ✅ Full Support | Universal compatibility |
| Default | ✅ Optimized | Built-in support |
| Martfury | ✅ Optimized | Custom styling included |
| Whozidis | ✅ Optimized | Custom styling included |
| Your Theme | ✅ Compatible | Works automatically |

## For Theme Developers

Want to add custom styling for Hall of Fame? Create these views in your theme:

```php
// themes/your-theme/views/plugins/hall-of-fame/hall-of-fame.blade.php
@extends(Theme::getThemeNamespace() . '::layouts.default')

@section('content')
    <div class="your-theme-hall-of-fame">
        <!-- Your custom HTML here -->
        <!-- $reports variable is available -->
    </div>
@endsection
```

## Troubleshooting

### Q: Plugin not showing properly in my theme?

A: The plugin uses universal views that work with all themes.
If you need custom styling, use Method 1 above.

### Q: Can I use my own CSS/styling?

A: Yes! Either customize in your theme directory or modify the plugin's CSS files.

### Q: Do I need vendor overrides?

A: No! The plugin works without vendor overrides. They're no longer needed.

### Q: Error: "View not found"?

A: Make sure you've run `php artisan view:clear` after installation.

## Distribution Notes

This plugin is designed for public distribution:

- ✅ Works on any Botble CMS installation
- ✅ No theme dependencies or requirements
- ✅ No manual vendor override setup needed
- ✅ Safe for marketplace distribution
- ✅ Backward compatible with existing installations
