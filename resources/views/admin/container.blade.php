@php
    $loanStatusAccess = Auth::user()->load('loanAccess');

    $total_active = 0;

    $total_active += $loanStatusAccess->loanAccess->pending;
    $total_active += $loanStatusAccess->loanAccess->for_interview;
    $total_active += $loanStatusAccess->loanAccess->for_revision;
    $total_active += $loanStatusAccess->loanAccess->waiting;

    $total_active += $loanStatusAccess->loanAccess->rejected;
    $total_active += $loanStatusAccess->loanAccess->transferred_and_processed;
    $total_active += $loanStatusAccess->loanAccess->closed;


@endphp

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
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Lightbox2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Optional Theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    @stack('sb-styles')
</head>

<body>
    <input type="hidden" id="getAuthID" value="{{ Auth::user()->id }}" />
    <audio id="notifSound" src="{{ asset('sound/Default.mp3') }}" preload="auto"></audio>


    <!-- Sidebar -->
   
    <div class="sidebar" id="sidebar">
        <a href="#" class="nav-link active" data-is-sidebar="1" data-url="/admin/dashboard">
            <i class="bi bi-columns-gap"></i> Dashboard
        </a>
        <ul class="nav flex-column list-unstyled">
            <li class="nav-item">
                <a href="#" class="nav-link"  data-url="/admin/loan-request/" data-bs-toggle="collapse" data-bs-target="#loanSubNav"
                    aria-expanded="false" aria-controls="loanSubNav">
                    <i class="bi bi-table me-2"></i> Loan Request
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="collapse nav flex-column ps-4 list-unstyled" id="loanSubNav">
                    <li class="nav-item p-0 m-0">
                        <a href="#"  class="nav-link py-1 m-0 active lrFirstReload" {{ $total_active < 2 ? 'hidden' : '' }}>All</a>
                    </li>
                    <li class="nav-item p-0 m-0">

                        <a href="#" data-loan_status="1" class="nav-link py-1 m-0" {{ $loanStatusAccess->loanAccess->pending == 0 ? 'hidden' : '' }} > Pending</a>
                    </li>
                    <li class="nav-item p-0 m-0">
                        <a href="#" data-loan_status="2" class="nav-link py-1 m-0" {{ $loanStatusAccess->loanAccess->for_interview == 0 ? 'hidden' : '' }} >For Interview</a>
                    </li>
                    <li class="nav-item p-0 m-0">
                        <a href="#" data-loan_status="3" class="nav-link py-1 m-0" {{ $loanStatusAccess->loanAccess->for_revision == 0 ? 'hidden' : '' }} >For revision</a>
                    </li>
                    <li class="nav-item p-0 m-0">
                        <a href="#" data-loan_status="4" class="nav-link py-1 m-0" {{ $loanStatusAccess->loanAccess->waiting == 0 ? 'hidden' : '' }} > Waiting</a>
                    </li>
                    <li class="nav-item p-0 m-0">
                        <a href="#" data-loan_status="6" class="nav-link py-1 m-0" {{ $loanStatusAccess->loanAccess->rejected == 0 ? 'hidden' : '' }} > Rejected</a>
                    </li>
                    <li class="nav-item p-0 m-0">
                        <a href="#" data-loan_status="5" class="nav-link py-1 m-0" {{ $loanStatusAccess->loanAccess->transferred_and_processed == 0 ? 'hidden' : '' }} > Transferred</a>
                    </li>
                    <li class="nav-item p-0 m-0">
                        <a href="#" data-loan_status="7" class="nav-link py-1 m-0" {{ $loanStatusAccess->loanAccess->closed == 0 ? 'hidden' : '' }} > Closed</a>

                    </li>
                </ul>
            </li>
        </ul>
        <!-- <a href="#" class="nav-link" data-is-sidebar="1" data-url="/admin/profile/referral-management">
            <i class="ri-coupon-3-line"></i> Referral Code
        </a> -->
        <a href="#" class="nav-link" data-is-sidebar="1" data-url="/admin/customer">
            <i class="ri-user-community-line"></i> Customer
        </a>
        <a href="#" class="nav-link" data-is-sidebar="1" data-url="/admin/blogpost">
            <i class="bi bi-newspaper"></i> Blogpost
        </a>
        <a href="#" class="nav-link" data-is-sidebar="1" data-url="/paymentpage">
            <i class="ri-wallet-3-line"></i> Payment Page
        <a href="#" data-url="/admin/appeal-request/" class="nav-link" data-is-sidebar="1" data-url="/admin/blogpost">
        <i class="bi bi-exclamation-triangle-fill"></i> Appeal Request
        </a>
    </div>


    <!-- Overlay for mobile -->
    <div class="overlay" id="overlay"></div>

    <!-- Main Content -->
    <div class="main" id="mainContent">

        <!-- Topbar Background -->
        <!-- <div class="topbar-bg"></div> -->
        <!-- Topbar (Floating on topbar-bg) -->
         <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
            <div class="container-fluid px-4">
                <button class="btn btn-light d-md-none toggle-btn" id="toggleBtn">
                    <i class="bi bi-list"></i>
                </button>
                <a class="navbar-brand font-pacifico text-primary-custom text-decoration-none" href="{{ url('/') }}"
                    style="font-size: 1.8rem;">
                    RAN Lending
                </a>

                <!-- <nav aria-label="breadcrumb" class="mt-2 ml-4 breadcrumbs-container">
                    <ol id="breadcrumbs" class="breadcrumb rounded-breadcrumb px-3 py-2 mb-0">
                        {{-- Default content kung walang JS --}}
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav> -->



                <div class="d-flex align-items-center gap-4 ms-auto pe-1">
                    <!-- 🔔 Notification Bell -->
                    <div class="dropdown">
                        <button class="btn btn-outline-primary-custom position-relative" type="button" id="notifDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                            <i class="ri-notification-line"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle-y badge rounded-pill bg-danger d-none"
                                style="font-size: 0.65rem; transform: translate(-40%, -40%) !important;"
                                id="general_notification_count">
                                0
                            </span>
                        </button>

                        <!-- Dropdown -->
                        <div class="dropdown-menu dropdown-menu-end shadow p-0" aria-labelledby="notifDropdown"
                            style="min-width: 400px;">

                            <!-- Header Row -->
                            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                <h6 class="mb-0 fw-bold">Notifications</h6>
                                <!-- <a href="#" class="small text-primary" data-url="/admin/notification-page"
                                    style="cursor:pointer;">See all</a> -->
                            </div>

                            <!-- Tabs Row -->
                            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                <a href="#" class="small text-primary dropdown-no-close markAllAsRead">Mark all as read</a>
                                <div>
                                    <!-- <a href="#" class="me-3 fw-semibold text-dark dropdown-no-close">All</a>
                                    <a href="#" class="fw-light text-muted dropdown-no-close">Unread</a> -->
                                    <a href="#" class="small text-primary" data-url="/admin/notification-page"
                                    style="cursor:pointer;">See all</a>
                                </div>
                                
                            </div>

                            <!-- Notification Items -->
                            <div style="max-height: 400px; overflow-y: auto;" class="notification-items">
                                <!-- Notification 1 -->
                                <!-- <div class="px-3 py-2 border-bottom items unread position-relative">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <div class="d-flex align-items-start">
                                            <i class="ri-mail-unread-line text-primary fs-5 me-2"></i>
                                            <div>
                                                <p class="mb-1 small">You have 2 new messages</p>
                                                <small class="text-muted">1m ago</small>
                                            </div>
                                        </div>
                                        <div class="position-relative">
                                            <i class="ri-more-2-fill text-muted fs-6" role="button" id="notifMore1"
                                                data-bs-toggle="dropdown" aria-expanded="false"></i>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifMore1">
                                                <li><a class="dropdown-item" href="#">Mark as read</a></li>
                                                <li><a class="dropdown-item" href="#">Clear notification</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> -->
                                <p class="text-center">No notifications</p>
                            </div>

                            <!-- Footer -->
                            <div class="text-center py-2">
                                <a href="#" class="text-primary small fw-semibold dropdown-no-close seeMoreNotif">See more
                                    notifications</a>
                            </div>
                        </div>
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
                                    <a class="dropdown-item" href="#" data-is-sidebar="0" data-url="/home">
                                        <i class="ri-loop-left-line me-2"></i>Borrower Mode
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" data-is-sidebar="0" data-url="/admin/profile">
                                        <i class="ri-user-line me-2"></i>Profile
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
            </div>
        </nav>



        <!-- Dynamic Content -->
        <div id="content" class="" style="padding-top: 30px;">
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
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/short-unique-id@latest/dist/short-unique-id.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
    <script src="{{ asset('js/components/global-notification.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/home.js') }}"></script>
    <script src="{{ asset('js/blogpost.js') }}"></script>
    <script src="{{ asset('js/loanrequest.js') }}"></script>
    <script src="{{ asset('js/customer.js') }}"></script>
    @stack('sb-scripts')

    <script type="module">
        // Import Firebase SDKs
        import { initializeApp } from "https://www.gstatic.com/firebasejs/12.1.0/firebase-app.js";
        import { getDatabase, ref, query, orderByChild, startAt, onChildAdded }
            from "https://www.gstatic.com/firebasejs/12.1.0/firebase-database.js";

        // Your Firebase config
        const firebaseConfig = {
            apiKey: "AIzaSyDI5V6np4Xstxl01DbS9j2PCV3tFmttbHw",
            authDomain: "ran-realtime.firebaseapp.com",
            databaseURL: "https://ran-realtime-default-rtdb.firebaseio.com",
            projectId: "ran-realtime",
            storageBucket: "ran-realtime.firebasestorage.app",
            messagingSenderId: "678506273903",
            appId: "1:678506273903:web:f7979289e002145776c19a",
            measurementId: "G-THBZK5DGGR"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const database = getDatabase(app);

        // === TIMESTAMP MARKER (oras ng pag-load ng page) ===
        const pageLoadTimestamp = Math.floor(Date.now() / 1000);

        // === LISTENER SETUP ===
        const table_id = "notifications";
        const notifRef = query(
            ref(database, table_id),
            orderByChild("timestamp"),
            startAt(pageLoadTimestamp) // 👉 kuha lang ng >= timestamp
        );

        // Listen for new child (na >= pageLoadTimestamp)
        onChildAdded(notifRef, (snapshot) => {
            const AuthID = parseInt($('#getAuthID').val());
            const notif = snapshot.val();

            if (notif.user_ids.includes(AuthID)) {
                // console.log("🔥 New notification:", notif.user_ids, "at", notif.timestamp);

                let sound = document.getElementById("notifSound");
                sound.currentTime = 0;
                sound.play().catch(err => {
                    console.warn("Sound play blocked by browser:", err);
                });
                window.general_notification_count();
                window.general_notification_data(10, 0, false);
            }
        });

        $(document).ready(function(){
            window.general_notification_count();
        });
    </script>
</body>

</html>