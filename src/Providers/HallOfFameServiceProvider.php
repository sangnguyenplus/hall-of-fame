<?php

namespace Whozidis\HallOfFame\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class HallOfFameServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(\Whozidis\HallOfFame\Repositories\Interfaces\VulnerabilityReportInterface::class, function ($app) {
            return new \Whozidis\HallOfFame\Repositories\Eloquent\VulnerabilityReportRepository(new \Whozidis\HallOfFame\Models\VulnerabilityReport());
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/hall-of-fame')
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes(['web', 'admin'])
            ->publishAssets();

        // Add admin menu
        $this->app['events']->listen(RouteMatched::class, function () {
            DashboardMenu::registerItem([
                'id' => 'cms-plugins-hall-of-fame',
                'priority' => 5,
                'parent_id' => null,
                'name' => 'plugins/hall-of-fame::hall-of-fame.name',
                'icon' => 'fa fa-shield-alt',
                'url' => route('vulnerability-reports.index'),
                'permissions' => ['vulnerability-reports.index'],
            ])->registerItem([
                'id' => 'cms-plugins-hall-of-fame-vulnerability-reports',
                'priority' => 1,
                'parent_id' => 'cms-plugins-hall-of-fame',
                'name' => 'plugins/hall-of-fame::vulnerability-reports.name',
                'icon' => 'fa fa-bug',
                'url' => route('vulnerability-reports.index'),
                'permissions' => ['vulnerability-reports.index'],
            ]);
        });
    }
}
