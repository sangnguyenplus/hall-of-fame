<!-- Hall of Fame Modern 2025 Design System -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap"
    rel="stylesheet">

<style>
    {!! file_get_contents(plugin_path('hall-of-fame') . '/resources/assets/css/modern-hof.css') !!}
</style>

<!-- Font Awesome 6 Latest -->
@if (!app()->environment('local') || !str_contains(app('request')->header('User-Agent', ''), 'curl'))
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endif

<!-- Hall of Fame Navigation -->
<div class="hof-navigation">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="hof-nav-wrapper">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <!-- Main HoF Navigation -->
                        <div class="hof-nav-links d-flex flex-wrap">
                            <a href="{{ route('public.hall-of-fame.index') }}"
                                class="btn btn-outline-primary hof-nav-link {{ request()->routeIs('public.hall-of-fame.index') ? 'active' : '' }}">
                                <i class="fas fa-shield-alt me-1"></i>
                                {{ trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame') }}
                            </a>

                            <a href="{{ route('public.vulnerability-reports.create') }}"
                                class="btn btn-outline-success hof-nav-link {{ request()->routeIs('public.vulnerability-reports.create') ? 'active' : '' }}">
                                <i class="fas fa-bug me-1"></i>
                                {{ trans('plugins/hall-of-fame::vulnerability-reports.submit_a_vulnerability') }}
                            </a>

                            @if (Route::has('public.certificates.index'))
                                <a href="{{ route('public.certificates.index') }}"
                                    class="btn btn-outline-info hof-nav-link {{ request()->routeIs('public.certificates.*') ? 'active' : '' }}">
                                    <i class="fas fa-certificate me-1"></i>
                                    Certificates
                                </a>
                            @endif
                        </div>

                        <!-- User Authentication Section -->
                        <div class="hof-auth-section d-flex flex-wrap">
                            @if (hof_researcher_authenticated())
                                <!-- Authenticated User Menu -->
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle hof-btn hof-btn-primary"
                                        type="button" id="hofUserDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fas fa-user me-1"></i>
                                        {{ hof_researcher()->name }}
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="hofUserDropdown">
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('public.hall-of-fame.dashboard.*') ? 'active' : '' }}"
                                                href="{{ route('public.hall-of-fame.dashboard.index') }}">
                                                <i class="fas fa-tachometer-alt me-2"></i>
                                                {{ trans('plugins/hall-of-fame::dashboard.dashboard') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('public.hall-of-fame.dashboard.profile') ? 'active' : '' }}"
                                                href="{{ route('public.hall-of-fame.dashboard.profile') }}">
                                                <i class="fas fa-user-edit me-2"></i>
                                                {{ trans('plugins/hall-of-fame::dashboard.profile') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('public.hall-of-fame.dashboard.reports') ? 'active' : '' }}"
                                                href="{{ route('public.hall-of-fame.dashboard.reports') }}">
                                                <i class="fas fa-list me-2"></i>
                                                {{ trans('plugins/hall-of-fame::dashboard.my_reports') }}
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('public.hall-of-fame.auth.logout') }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-sign-out-alt me-2"></i>
                                                    {{ trans('plugins/hall-of-fame::auth.logout') }}
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <!-- Guest Navigation -->
                                <div class="d-flex gap-2">
                                    <a href="{{ route('public.hall-of-fame.auth.login') }}"
                                        class="btn btn-outline-primary hof-btn hof-btn-outline">
                                        <i class="fas fa-sign-in-alt me-1"></i>
                                        {{ trans('plugins/hall-of-fame::auth.login') }}
                                    </a>
                                    <a href="{{ route('public.hall-of-fame.auth.register') }}"
                                        class="btn btn-primary hof-btn hof-btn-primary">
                                        <i class="fas fa-user-plus me-1"></i>
                                        {{ trans('plugins/hall-of-fame::auth.register') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hall of Fame Modern JavaScript -->
<script>
    {!! file_get_contents(plugin_path('hall-of-fame') . '/resources/assets/js/modern-hof.js') !!}
</script>
