<?php

namespace Whozidis\HallOfFame\Providers;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Theme\Facades\Theme;

class HallOfFameServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        // Register the repository binding
        $this->app->bind(
            \Whozidis\HallOfFame\Repositories\Interfaces\VulnerabilityReportInterface::class,
            \Whozidis\HallOfFame\Repositories\Eloquent\VulnerabilityReportRepository::class
        );
    }

    public function boot(): void
    {
        // Set the namespace for the plugin
        if (!$this->isPluginActive('hall-of-fame')) {
            return;
        }

        $this->setNamespace('plugins/hall-of-fame');

        // Load configurations
        $this->loadAndPublishConfigurations(['permissions', 'general']);

        // Load translations
        $this->loadAndPublishTranslations();

        // Load migrations
        $this->loadMigrations();

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'plugins/hall-of-fame');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        if (is_in_admin()) {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/admin.php');
        }

        // Publish assets
        $this->publishAssets();

        // Register menu item after all routes are loaded
        $this->app->booted(function () {
            DashboardMenu::registerItem([
                'id' => 'cms-plugins-hall-of-fame',
                'priority' => 5,
                'parent_id' => null,
                'name' => 'Hall of Fame',
                'icon' => 'fa fa-shield-alt',
                'url' => '#',
                'permissions' => ['vulnerability-reports.index', 'researchers.index'],
            ]);

            DashboardMenu::registerItem([
                'id' => 'cms-plugins-vulnerability-reports',
                'priority' => 1,
                'parent_id' => 'cms-plugins-hall-of-fame',
                'name' => 'plugins/hall-of-fame::vulnerability-reports.name',
                'icon' => 'fa fa-bug',
                'url' => 'vulnerability-reports',
                'permissions' => ['vulnerability-reports.index'],
            ]);

            DashboardMenu::registerItem([
                'id' => 'cms-plugins-researchers',
                'priority' => 2,
                'parent_id' => 'cms-plugins-hall-of-fame',
                'name' => 'plugins/hall-of-fame::researcher.name',
                'icon' => 'fa fa-users',
                'url' => 'researchers',
                'permissions' => ['researchers.index'],
            ]);
        });

        // Add assets to theme
        add_action(BASE_ACTION_ENQUEUE_SCRIPTS, function () {
            if (is_plugin_active('hall-of-fame')) {
                Theme::asset()
                    ->container('footer')
                    ->usePath(false)
                    ->add('hall-of-fame-js', 'vendor/core/plugins/hall-of-fame/js/hall-of-fame.js', ['jquery'])
                    ->add('hall-of-fame-css', 'vendor/core/plugins/hall-of-fame/css/hall-of-fame.css');
            }
        });
    }

    protected function isPluginActive(string $plugin): bool
    {
        return function_exists('is_plugin_active') && is_plugin_active($plugin);
    }
}
