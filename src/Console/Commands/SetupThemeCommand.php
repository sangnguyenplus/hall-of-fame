<?php

namespace Whozidis\HallOfFame\Console\Commands;

use Botble\Theme\Facades\Theme;
use Illuminate\Console\Command;
use Whozidis\HallOfFame\Theme\HallOfFameTheme;

class SetupThemeCommand extends Command
{
    protected $signature = 'hall-of-fame:setup-theme {theme?}';

    protected $description = 'Setup Hall of Fame views for current or specified theme';

    public function handle()
    {
        $themeName = $this->argument('theme') ?: Theme::getThemeName();

        if (! $themeName) {
            $this->error('No theme specified and no active theme detected.');

            return 1;
        }

        $this->info("Setting up Hall of Fame for theme: {$themeName}");

        // Show current support status
        $supported = HallOfFameTheme::getSupportedThemes();
        $this->info('Currently supported themes: ' . (empty($supported) ? 'none' : implode(', ', $supported)));

        if (HallOfFameTheme::hasThemeSupport($themeName)) {
            $this->info("Theme '{$themeName}' is already supported.");

            if (! $this->confirm('Do you want to regenerate the theme structure?')) {
                return 0;
            }
        }

        // Generate theme structure
        if (HallOfFameTheme::generateThemeStructure($themeName)) {
            $this->info("✓ Successfully generated theme structure for '{$themeName}'");
            $this->info("Views created in: platform/plugins/hall-of-fame/resources/views/themes/{$themeName}/");
            $this->line('');
            $this->info('You can now customize these views for your theme:');
            $this->line('- views/hall-of-fame.blade.php');
            $this->line('- views/certificates.blade.php');
            $this->line('- views/certificate-verify.blade.php');
            $this->line('- views/certificate-details.blade.php');
            $this->line('- layouts/master.blade.php');
        } else {
            $this->error("Failed to generate theme structure for '{$themeName}'");

            return 1;
        }

        return 0;
    }
}
