<!-- Hall of Fame Internal Navigation -->
<div class="hof-navigation mb-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="nav-wrapper bg-light rounded p-3 shadow-sm">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <!-- Main HoF Navigation -->
                        <div class="hof-nav-links d-flex flex-wrap">
                            <a href="{{ route('public.hall-of-fame.index') }}"
                                class="btn btn-outline-primary me-2 mb-2 {{ request()->routeIs('public.hall-of-fame.index') ? 'active' : '' }}">
                                <i class="fas fa-shield-alt me-1"></i>
                                {{ trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame') }}
                            </a>

                            <a href="{{ route('public.vulnerability-reports.create') }}"
                                class="btn btn-outline-success me-2 mb-2 {{ request()->routeIs('public.vulnerability-reports.create') ? 'active' : '' }}">
                                <i class="fas fa-bug me-1"></i>
                                {{ trans('plugins/hall-of-fame::vulnerability-reports.submit_a_vulnerability') }}
                            </a>
                        </div>

                        <!-- User Authentication Section -->
                        <div class="hof-auth-section d-flex flex-wrap">
                            @auth
                                <!-- Authenticated User Menu -->
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="hofUserDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-user me-1"></i>
                                        {{ Auth::user()->name ?? Auth::user()->first_name . ' ' . Auth::user()->last_name }}
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="hofUserDropdown">
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('public.hall-of-fame.auth.dashboard') ? 'active' : '' }}"
                                                href="{{ route('public.hall-of-fame.auth.dashboard') }}">
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
                                                <i class="fas fa-file-alt me-2"></i>
                                                {{ trans('plugins/hall-of-fame::dashboard.my_reports') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('public.hall-of-fame.dashboard.certificates') ? 'active' : '' }}"
                                                href="{{ route('public.hall-of-fame.dashboard.certificates') }}">
                                                <i class="fas fa-certificate me-2"></i>
                                                {{ trans('plugins/hall-of-fame::dashboard.my_certificates') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('public.hall-of-fame.dashboard.analytics') ? 'active' : '' }}"
                                                href="{{ route('public.hall-of-fame.dashboard.analytics') }}">
                                                <i class="fas fa-chart-bar me-2"></i>
                                                {{ trans('plugins/hall-of-fame::dashboard.analytics') }}
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('public.hall-of-fame.auth.logout') }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-sign-out-alt me-2"></i>
                                                    {{ trans('plugins/hall-of-fame::dashboard.logout') }}
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <!-- Guest User Menu -->
                                <a href="{{ route('public.hall-of-fame.auth.login') }}"
                                    class="btn btn-outline-primary me-2 mb-2 {{ request()->routeIs('public.hall-of-fame.auth.login') ? 'active' : '' }}">
                                    <i class="fas fa-sign-in-alt me-1"></i>
                                    {{ trans('plugins/hall-of-fame::dashboard.login') }}
                                </a>
                                <a href="{{ route('public.hall-of-fame.auth.register') }}"
                                    class="btn btn-success mb-2 {{ request()->routeIs('public.hall-of-fame.auth.register') ? 'active' : '' }}">
                                    <i class="fas fa-user-plus me-1"></i>
                                    {{ trans('plugins/hall-of-fame::dashboard.register') }}
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
