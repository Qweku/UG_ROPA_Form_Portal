<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RoPA Portal - University of Ghana</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Custom Styles --}}
    <style>
        :root {
            --ropa-primary: #153d6f;
            --ropa-accent: #b69964;
            --ropa-primary-light: #e8eef5;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
        }

        .navbar-ropa {
            background-color: var(--ropa-primary);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-ropa .navbar-brand,
        .navbar-ropa .nav-link {
            color: white !important;
        }

        .navbar-ropa .nav-link:hover {
            color: var(--ropa-accent) !important;
        }

        .btn-ropa-primary {
            background-color: var(--ropa-primary);
            color: white;
            border: none;
        }

        .btn-ropa-primary:hover {
            background-color: #0e2d52;
            color: white;
        }

        .btn-ropa-accent {
            background-color: var(--ropa-accent);
            color: white;
            border: none;
        }

        .btn-ropa-accent:hover {
            background-color: #9e7d4a;
            color: white;
        }

        .footer-ropa {
            background-color: var(--ropa-primary);
            color: white;
            padding: 1rem 0;
            margin-top: 3rem;
        }

        .alert-ropa-info {
            background-color: var(--ropa-primary-light);
            border-left: 4px solid var(--ropa-primary);
            color: var(--ropa-primary);
        }
    </style>

    @stack('styles')
</head>
<body>
    {{-- Navigation Bar --}}
    <nav class="navbar navbar-ropa navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-database me-2"></i>
                University of Ghana - RoPA Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/ropa') }}">
                            <i class="fas fa-list me-1"></i> My Forms
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/ropa/create') }}">
                            <i class="fas fa-plus me-1"></i> New Form
                        </a>
                    </li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ url('/profile') }}">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ url('/logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="py-4">
        <div class="container">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i> Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Page Content --}}
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    <footer class="footer-ropa mt-auto">
        <div class="container text-center">
            <p class="mb-0">
                <small>
                    &copy; {{ date('Y') }} University of Ghana - Data Protection and Privacy Framework
                    <br>
                    <i class="fas fa-shield-alt me-1"></i> Record of Processing Activities (RoPA)
                </small>
            </p>
        </div>
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>
</html>
