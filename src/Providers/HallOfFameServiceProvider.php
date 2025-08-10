<?php

namespace Whozidis\HallOfFame\Providers;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;

class HallOfFameServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(\Whozidis\HallOfFame\Repositories\Interfaces\VulnerabilityReportInterface::class, function () {
            return new \Whozidis\HallOfFame\Repositories\Eloquent\VulnerabilityReportRepository(new \Whozidis\HallOfFame\Models\VulnerabilityReport());
        });
    }

    public function boot(): void
    {
        $this->setNamespace('plugins/hall-of-fame')
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadMigrations()
            ->publishAssets();

        // Register routes
        $this->app->booted(function () {
            // Load web routes
            $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

            // Load admin routes if in admin context
            if (is_in_admin()) {
                $this->loadRoutesFrom(__DIR__ . '/../../routes/admin.php');
            }

            DashboardMenu::registerItem([
                'id' => 'cms-plugins-hall-of-fame',
                'priority' => 5,
                'parent_id' => null,
                'name' => 'plugins/hall-of-fame::general.name',
                'icon' => 'fa fa-shield-alt',
                'url' => '#',
                'permissions' => ['vulnerability-reports.index'],
            ]);

            add_action(BASE_ACTION_ENQUEUE_SCRIPTS, function () {
                Assets::addScriptsDirectly('vendor/core/plugins/hall-of-fame/js/hall-of-fame.js')
                      ->addStylesDirectly('vendor/core/plugins/hall-of-fame/css/hall-of-fame.css');
            });
        });
    }
}
