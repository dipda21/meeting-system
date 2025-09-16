<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Bank Sulselbar - Meeting System') }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Bank Sulselbar Color Scheme */
            --primary-color: #1B365D; /* Deep Navy Blue - professional banking */
            --primary-light: #2E5984;
            --secondary-color: #C41E3A; /* Deep Red - trustworthy accent */
            --accent-color: #D4AF37; /* Gold - premium banking feel */
            --success-color: #16A085;
            --warning-color: #F39C12;
            --danger-color: #E74C3C;
            --light-bg: #F8FAFC;
            --white: #FFFFFF;
            --text-dark: #2C3E50;
            --text-light: #7F8C8D;
            --shadow-light: 0 2px 10px rgba(27, 54, 93, 0.1);
            --shadow-medium: 0 4px 20px rgba(27, 54, 93, 0.15);
            --shadow-heavy: 0 8px 30px rgba(27, 54, 93, 0.2);
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--light-bg) 0%, #E8F0FE 100%);
            min-height: 100vh;
            color: var(--text-dark);
        }

        /* Bank Pattern Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(27, 54, 93, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(196, 30, 58, 0.02) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        /* Enhanced Navbar - Bank Professional Style */
        .navbar {
            background: var(--white) !important;
            border-bottom: 3px solid var(--primary-color);
            box-shadow: var(--shadow-medium);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.75rem;
            color: var(--primary-color) !important;
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
        }

        .navbar-brand:hover {
            transform: translateY(-1px);
            color: var(--secondary-color) !important;
        }

        .navbar-brand i {
            color: var(--accent-color);
            margin-right: 0.75rem;
            font-size: 1.5rem;
            text-shadow: 0 2px 4px rgba(212, 175, 55, 0.3);
        }

        .navbar-brand .brand-text {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link {
            color: var(--text-dark) !important;
            font-weight: 500;
            padding: 0.75rem 1.25rem !important;
            border-radius: var(--border-radius);
            transition: var(--transition);
            position: relative;
            margin: 0 0.25rem;
        }

        .nav-link:hover {
            background: var(--primary-color);
            color: var(--white) !important;
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
        }

        .nav-link i {
            margin-right: 0.5rem;
            transition: var(--transition);
        }

        .nav-link:hover i {
            transform: scale(1.1);
        }

        /* Active nav link */
        .nav-link.active {
            background: var(--primary-color);
            color: var(--white) !important;
        }

        /* Mobile toggle button */
        .navbar-toggler {
            border: 2px solid var(--primary-color);
            border-radius: var(--border-radius);
            padding: 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(27, 54, 93, 0.25);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2827, 54, 93, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Dropdown Menu */
        .dropdown-menu {
            background: var(--white);
            border: 1px solid rgba(27, 54, 93, 0.15);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-medium);
            margin-top: 0.5rem;
            padding: 0.5rem;
        }

        .dropdown-item {
            transition: var(--transition);
            border-radius: 8px;
            font-weight: 500;
            color: var(--text-dark);
            padding: 0.75rem 1rem;
        }

        .dropdown-item:hover {
            background: var(--primary-color);
            color: var(--white);
            transform: translateX(5px);
        }

        .dropdown-item i {
            margin-right: 0.5rem;
            width: 16px;
            text-align: center;
        }

        /* Main Content */
        main {
            background: transparent;
            min-height: calc(100vh - 100px);
        }

        /* Enhanced Alerts - Bank Style */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            font-weight: 500;
            position: relative;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(22, 160, 133, 0.1) 0%, rgba(22, 160, 133, 0.05) 100%);
            color: #0D5D56;
            border-left: 4px solid var(--success-color);
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(231, 76, 60, 0.1) 0%, rgba(231, 76, 60, 0.05) 100%);
            color: #A93226;
            border-left: 4px solid var(--danger-color);
        }

        .alert i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }

        .btn-close {
            transition: var(--transition);
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .btn-close:hover {
            transform: rotate(90deg) scale(1.1);
        }

        /* Container improvements */
        .container {
            max-width: 1200px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.5rem;
            }
            
            .navbar-brand .brand-text {
                display: none;
            }
            
            .nav-link {
                padding: 0.5rem 1rem !important;
                margin: 0.25rem 0;
            }
            
            .navbar-collapse {
                margin-top: 1rem;
                padding-top: 1rem;
                border-top: 1px solid rgba(27, 54, 93, 0.1);
            }
        }

        /* Animation Classes */
        .fade-in {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-in {
            animation: slideInRight 0.5s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-light);
        }

        /* Loading state */
        .loading {
            position: relative;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 16px;
            height: 16px;
            margin: -8px 0 0 -8px;
            border: 2px solid var(--primary-color);
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-university"></i>
                    <span class="brand-text">Bank Sulselbar</span>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="fas fa-chart-line"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('meetings.*') ? 'active' : '' }}" href="{{ route('meetings.index') }}">
                                    <i class="fas fa-handshake"></i> Rapat
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('meeting-minutes.*') ? 'active' : '' }}" href="{{ route('meeting-minutes.index') }}">
                                    <i class="fas fa-file-contract"></i> Notulen
                                </a>
                            </li>
                            @if(auth()->user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                        <i class="fas fa-users-cog"></i> Kelola Pengguna
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt"></i> {{ __('Login') }}
                                    </a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus"></i> {{ __('Register') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-tie me-2"></i>
                                    <span>{{ Auth::user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault();">
                                            <i class="fas fa-user-circle"></i> Profile
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="fade-in">
            @if(session('success'))
                <div class="container mt-4">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="container mt-4">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="container mt-4">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Enhanced UX JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scrolling
            document.documentElement.style.scrollBehavior = 'smooth';
            
            // Auto-hide alerts after 6 seconds
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    if (bsAlert) {
                        bsAlert.close();
                    }
                }, 6000);
            });

            // Add loading state to forms
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.classList.add('loading');
                        submitBtn.disabled = true;
                    }
                });
            });

            // Add click effect to nav links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Add ripple effect
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        background: rgba(255, 255, 255, 0.5);
                        border-radius: 50%;
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        pointer-events: none;
                    `;
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => ripple.remove(), 600);
                });
            });

            // Enhanced navbar scroll behavior
            let lastScrollTop = 0;
            const navbar = document.querySelector('.navbar');
            
            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > 100) {
                    navbar.style.boxShadow = 'var(--shadow-heavy)';
                } else {
                    navbar.style.boxShadow = 'var(--shadow-medium)';
                }
                
                lastScrollTop = scrollTop;
            });
        });

        // Add ripple animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
    
    @yield('scripts')
</body>
</html>