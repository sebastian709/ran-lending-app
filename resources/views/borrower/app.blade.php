<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- App CSS (from Vite or Laravel Mix) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- Custom Styles from child views --}}
    @yield('styles')

    <!-- $.confirm -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
    @stack('sb-styles')

    <style>
    :root {
        --primary-color: #0056b3;
        --secondary-color: #ff8c00;
    }

    .font-pacifico {
        font-family: 'Pacifico', cursive;
    }

    .bg-primary-custom {
        background-color: var(--primary-color) !important;
    }

    .text-primary-custom {
        color: var(--primary-color) !important;
    }

    .btn-outline-primary-custom {
        color: var(--primary-color);
        border-color: var(--primary-color);
        border-radius: 8px;
    }

    .btn-outline-primary-custom:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .navbar-custom {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        background-color: white !important;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: #dc3545;
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

</head>
<body>
    <div id="app">
        {{-- Navbar (optional: can customize this if needed) --}}
        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="container-fluid px-4">
                <a class="navbar-brand font-pacifico text-primary-custom text-decoration-none" href="{{ url('/') }}" style="font-size: 1.8rem;">
                    RAN Lending
                </a>

                <div class="d-flex align-items-center">
                    <!-- Notifications -->
                    <div class="position-relative me-3">
                        <button class="btn btn-outline-primary-custom position-relative">
                            <i class="ri-notification-line"></i>
                            <span class="notification-badge">3</span>
                        </button>
                    </div>

                    <!-- User Dropdown -->
                    @auth
                        <div class="dropdown">
                            <button class="btn p-0 border-0 bg-transparent" type="button" data-bs-toggle="dropdown">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-2">{{ strtoupper(substr(Auth::user()->firstname, 0, 1) . substr(Auth::user()->lastname, 0, 1)) }}</div>
                                    <div class="d-none d-md-block text-start">
                                        <div class="fw-medium">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</div>
                                        <div class="small text-muted"></div>
                                    </div>
                                    <i class="ri-arrow-down-s-line ms-2 text-muted"></i>
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="ri-user-line me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ri-settings-line me-2"></i>Settings</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ri-question-line me-2"></i>Help & Support</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="ri-logout-box-line me-2"></i>Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>
        {{-- Main Content Area --}}
        <main class="">
            @yield('content')
        </main>
    </div>

    {{-- Scripts injected from child views --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
    <script src="{{ asset('js/index.js') }}"></script>
    @yield('scripts')
    @stack('sb-scripts')
</body>
</html>
