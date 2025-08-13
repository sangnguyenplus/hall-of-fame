<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        try {
            if (class_exists('Botble\\Base\\Facades\\Meta')) {
                echo Botble\Base\Facades\Meta::toHtml();
            }
        } catch (Throwable $e) {
            // silently ignore if Meta facade/service is unavailable
        }
        try {
            if (class_exists('Botble\\SeoHelper\\Facades\\SeoHelper')) {
                echo Botble\SeoHelper\Facades\SeoHelper::render();
            }
        } catch (Throwable $e) {
            // silently ignore if SeoHelper facade/service is unavailable
        }
    @endphp

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Hall of Fame Plugin CSS -->
    {{-- Plugin assets will be served from the plugin's public directory --}}
    <style>
        /* Inline critical styles to avoid asset loading issues during development */
        .hall-of-fame-dashboard {
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .hof-card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }

        .hof-btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .hof-text-primary {
            color: #007bff !important;
        }

        .hof-sidebar {
            background-color: #343a40;
            min-height: 100vh;
        }

        .hof-sidebar .nav-link {
            color: #adb5bd;
        }

        .hof-sidebar .nav-link:hover,
        .hof-sidebar .nav-link.active {
            color: #fff;
            background-color: #495057;
        }
    </style>

    <!-- External CSS Libraries -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css" rel="stylesheet">

    @php $themeAvailable = class_exists('Theme'); @endphp
    @if ($themeAvailable)
        {!! Theme::header() !!}
    @endif

    @stack('header')
</head>

<body class="dashboard-layout">
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('public.hall-of-fame.index') }}">
                <i class="fas fa-shield-alt me-2"></i>
                {{ trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.hall-of-fame.index') }}">
                            <i class="fas fa-home me-1"></i>
                            {{ trans('plugins/hall-of-fame::dashboard.home') }}
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            {{ isset($researcher) ? $researcher->name : 'Researcher' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('public.hall-of-fame.dashboard.profile') }}">
                                    <i class="fas fa-user me-2"></i>
                                    {{ trans('plugins/hall-of-fame::dashboard.profile') }}
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('public.hall-of-fame.auth.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>
                                        {{ trans('plugins/hall-of-fame::auth.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <div class="dashboard-sidebar">
            <div class="sidebar-content">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.hall-of-fame.dashboard.index') ? 'active' : '' }}"
                            href="{{ route('public.hall-of-fame.dashboard.index') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            {{ trans('plugins/hall-of-fame::dashboard.dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.hall-of-fame.dashboard.reports*') ? 'active' : '' }}"
                            href="{{ route('public.hall-of-fame.dashboard.reports') }}">
                            <i class="fas fa-bug me-2"></i>
                            {{ trans('plugins/hall-of-fame::dashboard.reports') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.hall-of-fame.dashboard.certificates') ? 'active' : '' }}"
                            href="{{ route('public.hall-of-fame.dashboard.certificates') }}">
                            <i class="fas fa-certificate me-2"></i>
                            {{ trans('plugins/hall-of-fame::dashboard.certificates') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.hall-of-fame.dashboard.analytics') ? 'active' : '' }}"
                            href="{{ route('public.hall-of-fame.dashboard.analytics') }}">
                            <i class="fas fa-chart-bar me-2"></i>
                            {{ trans('plugins/hall-of-fame::dashboard.analytics') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.hall-of-fame.dashboard.profile') ? 'active' : '' }}"
                            href="{{ route('public.hall-of-fame.dashboard.profile') }}">
                            <i class="fas fa-user me-2"></i>
                            {{ trans('plugins/hall-of-fame::dashboard.profile') }}
                        </a>
                    </li>
                </ul>

                <hr class="my-4">

                <div class="quick-actions">
                    <h6 class="sidebar-heading">{{ trans('plugins/hall-of-fame::dashboard.quick_actions') }}</h6>
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link nav-link-sm" href="{{ route('public.vulnerability-reports.create') }}">
                                <i class="fas fa-plus me-2"></i>
                                {{ trans('plugins/hall-of-fame::dashboard.submit_new_report') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-sm" href="{{ route('public.hall-of-fame.index') }}">
                                <i class="fas fa-trophy me-2"></i>
                                {{ trans('plugins/hall-of-fame::dashboard.browse_hall_of_fame') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="dashboard-main">
            <div class="dashboard-content">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <!-- Dashboard JavaScript -->
    <script>
        /**
         * Hall of Fame Dashboard JavaScript
         * Inline to avoid asset loading issues during development
         */
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            if (typeof bootstrap !== 'undefined') {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            // Initialize popovers
            if (typeof bootstrap !== 'undefined') {
                var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
                var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl);
                });
            }

            // Hall of Fame specific functionality
            initializeHallOfFameDashboard();
        });

        function initializeHallOfFameDashboard() {
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                document.querySelectorAll('.alert-dismissible').forEach(alert => {
                    if (alert.querySelector('.btn-close')) {
                        alert.querySelector('.btn-close').click();
                    }
                });
            }, 5000);

            // Form validation enhancements
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    form.classList.add('was-validated');
                });
            });

            // AJAX form handling for reports
            handleReportForms();
        }

        function handleReportForms() {
            document.querySelectorAll('.report-action-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(form);
                    const url = form.getAttribute('action');

                    fetch(url, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showNotification('Success', data.message, 'success');
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                showNotification('Error', data.message || 'An error occurred', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('Error', 'An unexpected error occurred', 'error');
                        });
                });
            });
        }

        function showNotification(title, message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className =
                `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';

            notification.innerHTML = `
                <strong>${title}:</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 5000);
        }

        // Export functions for global access
        window.showNotification = showNotification;
        window.initializeHallOfFameDashboard = initializeHallOfFameDashboard;
    </script>

    @if ($themeAvailable)
        {!! Theme::footer() !!}
    @endif

    @stack('footer')
</body>

</html>
