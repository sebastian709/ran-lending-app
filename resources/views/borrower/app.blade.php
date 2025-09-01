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
    <!-- borrower css -->
    <link rel="stylesheet" href="{{ asset('css/borrower.css') }}">
    @stack('sb-styles')
</head>

<body>
    <div id="app">
        {{-- Navbar (optional: can customize this if needed) --}}
        <input type="hidden" id="gb_user_id" value="{{ Auth::id() }}">
        <audio id="notifSound" src="{{ asset('sound/Default.mp3') }}" preload="auto"></audio>
        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="container-fluid px-4">
                <a class="navbar-brand font-pacifico text-primary-custom text-decoration-none" href="{{ url('/') }}"
                    style="font-size: 1.8rem;">
                    RAN Lending
                </a>

                <div class="d-flex align-items-center">
                    <!-- Notifications -->
                    <div class="position-relative me-3 dropdown">
                        <!-- Main Dropdown Trigger -->
                        <button class="btn btn-outline-primary-custom position-relative" id="notifDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                            <i class="ri-notification-line"></i>
                            <span class="notification-badge" id="general_notification_count">3</span>
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
                                <a href="#" class="small text-primary dropdown-no-close markAllAsRead">Mark all as
                                    read</a>
                                <div>
                                    <!-- <a href="#" class="me-3 fw-semibold text-dark dropdown-no-close">All</a>
                                <a href="#" class="fw-light text-muted dropdown-no-close">Unread</a> -->
                                    <a href="#" class="small text-primary" data-url="/admin/notification-page"
                                        style="cursor:pointer;">See all</a>
                                </div>

                            </div>

                            <!-- Notification Items -->
                            <div style="max-height: 400px; overflow-y: auto;" class="notification-items">
                                <p class="text-center">No Notification</p>
                            </div>

                            <!-- Footer -->
                            <div class="text-center py-2">
                                <a href="#" class="text-primary small fw-semibold dropdown-no-close seeMoreNotif">See
                                    more
                                    notifications</a>
                            </div>
                        </div>
                    </div>


                    <!-- User Dropdown -->
                    @auth
                        <div class="dropdown">
                            <button class="btn p-0 border-0 bg-transparent" type="button" data-bs-toggle="dropdown">
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
                                <li {{ Auth::user()->is_admin == 0 ? 'hidden' : '' }}>
                                    <a class="dropdown-item" href="#" data-is-sidebar="0" data-url="/admin/dashboard">
                                        <i class="ri-loop-left-line me-2"></i>Admin Mode
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" data-url="/profile">
                                        <i class="ri-user-line me-2"></i>Profile
                                    </a>
                                </li>
                                <!-- <li>
                                                    <a class="dropdown-item"  href="#" data-url="/borrower/change-password">
                                                        <i class="ri-key-2-line"></i> Change Password
                                                    </a>
                                                </li> -->
                                <li>
                                    <a class="dropdown-item" href="#">
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
    <script src="{{ asset('js/components/global-notification.js') }}"></script>
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="{{ asset('js/borrower.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
    @stack('sb-scripts')

    <script type="module">
        const authUser = @json(Auth::user());
        console.log(authUser);
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
            const AuthID = parseInt($('#gb_user_id').val());
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

        $(document).ready(function () {
            window.general_notification_count();
        });
    </script>
</body>

</html>