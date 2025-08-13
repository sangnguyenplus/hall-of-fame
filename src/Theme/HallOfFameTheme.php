<?php

namespace Whozidis\HallOfFame\Theme;

use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class HallOfFameTheme
{
    /**
     * Register views for the Hall of Fame plugin with dynamic theme detection
     * This ensures the plugin works with ANY theme, not just hardcoded ones.
     */
    public static function registerThemeViews(): void
    {
        $pluginViewsPath = platform_path('plugins/hall-of-fame/resources/views');
        $paths = [];

        try {
            $currentTheme = Theme::getThemeName();

            if ($currentTheme) {
                // Priority 1: Theme's custom override (if user manually created)
                if (function_exists('theme_path')) {
                    $themeOverridePath = theme_path($currentTheme . '/views/plugins/hall-of-fame');
                    if (File::isDirectory($themeOverridePath)) {
                        $paths[] = $themeOverridePath;
                        Log::info("Hall of Fame: Using theme override views for '{$currentTheme}'");
                    }
                }

                // Priority 2: Plugin's theme-specific views (if available)
                $pluginThemeViewsPath = $pluginViewsPath . '/themes/' . $currentTheme;
                if (File::isDirectory($pluginThemeViewsPath)) {
                    $paths[] = $pluginThemeViewsPath;
                    Log::info("Hall of Fame: Using plugin theme-specific views for '{$currentTheme}'");
                }

                // Priority 3: Plugin's default theme (if 'default' theme exists)
                if ($currentTheme !== 'default') {
                    $defaultThemeViewsPath = $pluginViewsPath . '/themes/default';
                    if (File::isDirectory($defaultThemeViewsPath)) {
                        $paths[] = $defaultThemeViewsPath;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('Hall of Fame: Theme detection failed: ' . $e->getMessage());
        }

        // Priority 4: Base plugin views (always available as fallback)
        if (File::isDirectory($pluginViewsPath)) {
            $paths[] = $pluginViewsPath;
        }

        // Register namespace with priority-ordered paths
        if (! empty($paths)) {
            view()->addNamespace('plugins/hall-of-fame', $paths);
            Log::info('Hall of Fame: Registered view paths: ' . implode(', ', $paths));
        }
    }

    /**
     * Check if theme-specific views are available for current theme
     */
    public static function hasThemeSupport(?string $themeName = null): bool
    {
        $themeName = $themeName ?: Theme::getThemeName();

        if (! $themeName) {
            return false;
        }

        $pluginThemeViewsPath = platform_path('plugins/hall-of-fame/resources/views/themes/' . $themeName);

        return File::isDirectory($pluginThemeViewsPath);
    }

    /**
     * Get list of supported themes
     */
    public static function getSupportedThemes(): array
    {
        $themesPath = platform_path('plugins/hall-of-fame/resources/views/themes');

        if (! File::isDirectory($themesPath)) {
            return [];
        }

        return collect(File::directories($themesPath))
            ->map(function ($path) {
                return basename($path);
            })
            ->filter(function ($name) {
                return $name !== '.' && $name !== '..' && ! empty($name);
            })
            ->values()
            ->toArray();
    }

    /**
     * Auto-generate basic theme structure for current theme (if needed)
     * This is optional and can be called during plugin activation
     */
    public static function generateThemeStructure(?string $themeName = null): bool
    {
        $themeName = $themeName ?: Theme::getThemeName();

        if (! $themeName || self::hasThemeSupport($themeName)) {
            return false; // Already exists or no theme
        }

        try {
            $themeViewsPath = platform_path('plugins/hall-of-fame/resources/views/themes/' . $themeName);

            // Create basic directory structure
            File::makeDirectory($themeViewsPath . '/views', 0755, true);
            File::makeDirectory($themeViewsPath . '/layouts', 0755, true);

            // Copy default/universal views as starting point
            $sourceViews = platform_path('plugins/hall-of-fame/resources/views/public');
            $targetViews = $themeViewsPath . '/views';

            if (File::isDirectory($sourceViews)) {
                File::copyDirectory($sourceViews, $targetViews);
            }

            // Create a basic layout file
            $layoutContent = self::generateBasicLayout($themeName);
            File::put($themeViewsPath . '/layouts/master.blade.php', $layoutContent);

            Log::info("Hall of Fame: Generated theme structure for '{$themeName}'");

            return true;

        } catch (\Exception $e) {
            Log::error("Hall of Fame: Failed to generate theme structure for '{$themeName}': " . $e->getMessage());

            return false;
        }
    }

    /**
     * Generate a basic layout template for a theme
     */
    private static function generateBasicLayout(string $themeName): string
    {
        return <<<BLADE
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {!! Theme::header() !!}
    
    <title>@yield('title', 'Hall of Fame')</title>
</head>
<body class="hall-of-fame-{$themeName}">
    {!! Theme::partial('header') !!}
    
    <main class="main-content">
        @yield('content')
    </main>
    
    {!! Theme::partial('footer') !!}
    
    {!! Theme::footer() !!}
</body>
</html>
BLADE;
    }
}
