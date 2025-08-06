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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Lightbox2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">

    @stack('sb-styles')
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h4>RAN Serenity</h4>
        <a href="#" class="nav-link active" data-is-sidebar="1" data-url="/admin/dashboard">
            <i class="bi bi-columns-gap"></i> Dashboard
        </a>
       <ul class="nav flex-column list-unstyled">
            <li class="nav-item">
                <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#loanSubNav" aria-expanded="false" aria-controls="loanSubNav">
                    <i class="bi bi-table me-2"></i> Loan Request
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="collapse nav flex-column ps-4 list-unstyled" id="loanSubNav">
                    <li class="nav-item">
                        <a href="/admin/loan-request" data-url="/admin/loan-request" class="nav-link">All</a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/loan-request/assigned-status" class="nav-link">Pending</a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/loan-request/assigned-status" class="nav-link">For Interview</a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/loan-request/assigned-status" class="nav-link">For revision</a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/loan-request/assigned-status" class="nav-link">Waiting</a>
                    </li>
                </ul>
            </li>
        </ul>
        <a href="#" class="nav-link" data-is-sidebar="1" data-url="/admin/blogpost">
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
        <nav class="topbar d-flex justify-content-between align-items-center px-3 py-2 mt-3">
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
                @auth
                    <div class="dropdown">
                        <button class="btn p-0 border-0 bg-white py-1 px-2" type="button" data-bs-toggle="dropdown">
                            <div class="d-flex align-items-center">
                                @if (Auth::user()->profile_src)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_src) }}" alt="Profile Picture"
                                        class="user-avatar me-2 object-fit-cover" style="object-fit: cover;">
                                @else
                                    <div class="user-avatar me-2">
                                        {{ strtoupper(substr(Auth::user()->firstname, 0, 1) . substr(Auth::user()->lastname, 0, 1)) }}
                                    </div>
                                @endif

                                <div class="d-none d-md-block text-start">
                                    <div class="fw-medium">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                                    </div>
                                    <div class="small text-muted"></div>
                                </div>
                                <i class="ri-arrow-down-s-line ms-2 text-muted"></i>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>

                                <a class="dropdown-item" href="#" data-is-sidebar="0" data-url="/admin/profile">
                                    <i class="ri-user-line me-2"></i>Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-is-sidebar="0" data-url="/admin/settings">
                                    <i class="ri-settings-line me-2"></i>Settings
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="ri-question-line me-2"></i>
                                    Help & Support
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script src="https://unpkg.com/browser-image-compression@latest/dist/browser-image-compression.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/home.js') }}"></script>
    <script src="{{ asset('js/blogpost.js') }}"></script>
    @stack('sb-scripts')

</body>

</html>