<?php

namespace Whozidis\HallOfFame\Providers;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Theme\Facades\Theme;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Whozidis\HallOfFame\Services\PgpService;
use Whozidis\HallOfFame\Services\CertificateService;
use Whozidis\HallOfFame\Console\Commands\SetupThemeCommand;
use Whozidis\HallOfFame\Repositories\Interfaces\VulnerabilityReportInterface;
use Whozidis\HallOfFame\Repositories\Eloquent\VulnerabilityReportRepository;

class HallOfFameServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        // Load plugin's composer dependencies
        $pluginComposerPath = __DIR__ . '/../../vendor/autoload.php';
        if (file_exists($pluginComposerPath)) {
            require_once $pluginComposerPath;
        }
        
        // Register plugin services
        $this->app->singleton(PgpService::class);
        $this->app->singleton(CertificateService::class);
        
        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                SetupThemeCommand::class,
            ]);
        }
        
        // Bind interfaces if needed
        $this->app->bind(VulnerabilityReportInterface::class, VulnerabilityReportRepository::class);
    }

    public function boot(): void
    {
        // Load GNUPG stubs in dev environments to satisfy static analyzers when ext-gnupg is missing.
        $stubs = __DIR__ . '/../Support/gnupg_stubs.php';
        if (file_exists($stubs)) {
            require_once $stubs;
        }
        // Exit early if plugin is not active
        if (!$this->isPluginActive('hall-of-fame')) {
            return;
        }

        $this->setNamespace('plugins/hall-of-fame');

        // Load configurations, views, translations and migrations using the trait methods
        $this->loadAndPublishConfigurations(['permissions', 'general']);
        $this->loadAndPublishTranslations();
        $this->loadAndPublishViews();
        $this->loadMigrations();

        // Register routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/admin.php');
        
        // Register theme view locations through the HallOfFameTheme class
        \Whozidis\HallOfFame\Theme\HallOfFameTheme::registerThemeViews();
        
        // Register hook actions within the booted callback
        $this->app->booted(function () {
            // Register custom rate limiters (non-invasive)
            try {
                RateLimiter::for('forgot-password', function (Request $request) {
                    $emailKey = (string) $request->input('email');
                    $ipKey = $request->ip();

                    return [
                        // burst control
                        Limit::perMinute(5)->by($ipKey),
                        // per-hour controls by IP and by email
                        Limit::perHour(3)->by($ipKey),
                        $emailKey ? Limit::perHour(3)->by('email:' . mb_strtolower($emailKey)) : Limit::none(),
                    ];
                });

                RateLimiter::for('account-deletion-email', function (Request $request) {
                    $ipKey = $request->ip();
                    $userId = optional(auth('customer')->user())->getKey();
                    return [
                        Limit::perMinute(1)->by($ipKey),
                        Limit::perHour(3)->by($ipKey),
                        $userId ? Limit::perHour(3)->by('cust:' . $userId) : Limit::none(),
                    ];
                });
            } catch (\Throwable $e) {
                // Silently ignore if RateLimiter not available in this context
            }

            // Attach route-specific middleware dynamically by route name
            try {
                $routes = app('router')->getRoutes();

                if ($route = $routes->getByName('customer.password.request')) {
                    $route->middleware('throttle:forgot-password');
                }

                if ($route = $routes->getByName('customer.delete-account.store')) {
                    $route->middleware('throttle:account-deletion-email');
                }

                // Enforce strong password + execute post-change security actions
                if ($route = $routes->getByName('customer.post.change-password')) {
                    $route->middleware([
                        \Whozidis\HallOfFame\Http\Middleware\StrongPasswordMiddleware::class,
                        \Whozidis\HallOfFame\Http\Middleware\AfterPasswordChangeActions::class,
                    ]);
                }

                // Enforce strong password on registration as well
                if ($route = $routes->getByName('customer.register.post')) {
                    $route->middleware(\Whozidis\HallOfFame\Http\Middleware\StrongPasswordMiddleware::class);
                }
            } catch (\Throwable $e) {
                // Ignore if router not resolved or route names differ
            }

            // Add footer content using the standard hook
            if (defined('THEME_FRONT_FOOTER')) {
                add_action(THEME_FRONT_FOOTER, function () {
                    return view('plugins/hall-of-fame::partials.footer')->render();
                }, 20);
            }

            // Register admin menu items
            $this->registerAdminMenuItems();
        });

        // Add assets using the standard action hook
        add_action(BASE_ACTION_ENQUEUE_SCRIPTS, function () {
            if (!is_plugin_active('hall-of-fame')) {
                return;
            }
            
            // Use Assets facade for script/style registration (preferred method)
            Assets::addStylesDirectly([
                'vendor/core/plugins/hall-of-fame/css/hall-of-fame.css',
                'vendor/core/plugins/hall-of-fame/css/hall-of-fame-navigation.css'
            ])->addScriptsDirectly('vendor/core/plugins/hall-of-fame/js/hall-of-fame.js');
        });
    }
    
    /**
     * Register admin menu items
     */
    protected function registerAdminMenuItems(): void
    {
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

        DashboardMenu::registerItem([
            'id' => 'cms-plugins-certificates',
            'priority' => 3,
            'parent_id' => 'cms-plugins-hall-of-fame',
            'name' => 'Certificates',
            'icon' => 'fa fa-certificate',
            'url' => 'certificates',
            'permissions' => ['certificates.index'],
        ]);

        DashboardMenu::registerItem([
            'id' => 'cms-plugins-hof-settings',
            'priority' => 99,
            'parent_id' => 'cms-plugins-hall-of-fame',
            'name' => 'plugins/hall-of-fame::permissions.settings',
            'icon' => 'fa fa-cogs',
            'url' => '#',
            'permissions' => ['hall-of-fame.settings'],
        ]);

        // PGP Key Management submenu
        DashboardMenu::registerItem([
            'id' => 'cms-plugins-hof-pgp-keys',
            'priority' => 0,
            'parent_id' => 'cms-plugins-hof-settings',
            'name' => 'PGP Key Management',
            'icon' => 'fa fa-key',
            'url' => route('hall-of-fame.settings.pgp-keys.index'),
            'permissions' => ['hall-of-fame.settings'],
        ]);

        // PGP Settings submenu
        $pgpSettingsUrl = null;
        try {
            $pgpSettingsUrl = route('hall-of-fame.settings.pgp.edit');
        } catch (\Throwable $e) {
            $pgpSettingsUrl = '#';
        }

        DashboardMenu::registerItem([
            'id' => 'cms-plugins-hof-settings-pgp',
            'priority' => 1,
            'parent_id' => 'cms-plugins-hof-settings',
            'name' => 'plugins/hall-of-fame::settings.pgp.title',
            'icon' => 'fa fa-key',
            'url' => $pgpSettingsUrl,
            'permissions' => ['hall-of-fame.settings'],
        ]);

        // Researcher Settings submenu
        $researcherSettingsUrl = null;
        try {
            $researcherSettingsUrl = route('hall-of-fame.settings.researchers.edit');
        } catch (\Throwable $e) {
            $researcherSettingsUrl = '#';
        }

        DashboardMenu::registerItem([
            'id' => 'cms-plugins-hof-settings-researchers',
            'priority' => 2,
            'parent_id' => 'cms-plugins-hof-settings',
            'name' => 'plugins/hall-of-fame::settings.researchers.title',
            'icon' => 'fa fa-users-cog',
            'url' => $researcherSettingsUrl,
            'permissions' => ['hall-of-fame.settings'],
        ]);

        // Vulnerability Report Settings submenu
        $vulnerabilityReportSettingsUrl = null;
        try {
            $vulnerabilityReportSettingsUrl = route('hall-of-fame.settings.vulnerability-reports.edit');
        } catch (\Throwable $e) {
            $vulnerabilityReportSettingsUrl = '#';
        }

        DashboardMenu::registerItem([
            'id' => 'cms-plugins-hof-settings-vulnerability-reports',
            'priority' => 3,
            'parent_id' => 'cms-plugins-hof-settings',
            'name' => 'plugins/hall-of-fame::settings.vulnerability_reports.title',
            'icon' => 'fa fa-bug',
            'url' => $vulnerabilityReportSettingsUrl,
            'permissions' => ['hall-of-fame.settings'],
        ]);

        // Certificate Settings submenu
        $certificateSettingsUrl = null;
        try {
            $certificateSettingsUrl = route('hall-of-fame.settings.certificates.edit');
        } catch (\Throwable $e) {
            $certificateSettingsUrl = '#';
        }

        DashboardMenu::registerItem([
            'id' => 'cms-plugins-hof-settings-certificates',
            'priority' => 4,
            'parent_id' => 'cms-plugins-hof-settings',
            'name' => 'Certificate Settings',
            'icon' => 'fa fa-certificate',
            'url' => $certificateSettingsUrl,
            'permissions' => ['hall-of-fame.settings'],
        ]);
    }

    protected function isPluginActive(string $plugin): bool
    {
        return function_exists('is_plugin_active') && is_plugin_active($plugin);
    }
}
