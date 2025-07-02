<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>RAN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/blogpost.css') }}">
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h4>RAN Serenity</h4>
        <a href="#" class="nav-link active" data-url="/admin/dashboard">
            <i class="bi bi-columns-gap"></i> Dashboard
        </a>
        <a href="#" class="nav-link" data-url="/admin/blogpost">
            <i class="bi bi-newspaper"></i> Blogpost
        </a>
    </div>

    <!-- Overlay for mobile -->
    <div class="overlay" id="overlay"></div>

    <!-- Main Content -->
    <div class="main" id="mainContent">

        <!-- Topbar Background -->
        <div class="topbar-bg"></div>
        <!-- Topbar (Floating on topbar-bg) -->
        <nav class="topbar d-flex justify-content-between align-items-center px-3 py-2">
            <button class="btn btn-light d-md-none toggle-btn" id="toggleBtn">
                <i class="bi bi-list"></i>
            </button>

            <nav aria-label="breadcrumb" class="mt-2 ml-4 breadcrumbs-container">
                <ol id="breadcrumbs" class="breadcrumb rounded-breadcrumb px-3 py-2 mb-0">
                    {{-- Default content kung walang JS --}}
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>



            <div class="d-flex align-items-center gap-4 ms-auto pe-1">
                <!-- 🔔 Notification Bell -->
                <div class="dropdown">
                    <button class="btn position-relative text-white p-0" type="button" id="notifDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell-fill fs-5 top-bar-icon"></i>
                        <span class="position-absolute top-0 start-100 translate-middle-y badge rounded-pill bg-danger"
                            style="font-size: 0.65rem; transform: translate(-40%, -40%) !important;">
                            3
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notifDropdown"
                        style="min-width: 280px;">
                        <li>
                            <h6 class="dropdown-header">Notifications</h6>
                        </li>
                        <li><a class="dropdown-item" href="#">📦 New order received</a></li>
                        <li><a class="dropdown-item" href="#">✅ Task completed</a></li>
                        <li><a class="dropdown-item" href="#">📩 2 new messages</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-center text-primary" href="#">View all</a></li>
                    </ul>
                </div>

                <!-- 👤 Profile Image -->
                <div class="dropdown">
                    <a href="#" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://i.pinimg.com/736x/d4/8c/80/d48c8055b732720be02bead70f992747.jpg" alt="Profile"
                            class="rounded" style="width: 45px; height: 45px; object-fit: cover;">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>



        <!-- Dynamic Content -->
        <div id="content" class="my-4 mx-2">
            @yield('content')
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
    <!-- Bootstrap JS (Dropdowns need this) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Integrate CKEditor 5 Classic via CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/home.js') }}"></script>
    <script src="{{ asset('js/blogpost.js') }}"></script>
</body>

</html>