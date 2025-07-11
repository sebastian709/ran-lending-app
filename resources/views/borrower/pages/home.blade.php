@extends('borrower.app')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0056b3;
            --secondary-color: #ff8c00;
        }

        .font-pacifico {
            font-family: 'Pacifico', cursive;
        }

        .bg-primary-custom { background-color: var(--primary-color) !important; }
        .bg-secondary-custom { background-color: var(--secondary-color) !important; }
        .text-primary-custom { color: var(--primary-color) !important; }
        .text-secondary-custom { color: var(--secondary-color) !important; }

        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            color: white;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            background-color: rgba(0, 86, 179, 0.9);
            border-color: rgba(0, 86, 179, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 86, 179, 0.3);
        }

        .btn-outline-primary-custom {
            color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
        }

        .btn-outline-primary-custom:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .rounded-custom { border-radius: 8px !important; }

        .navbar-custom {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background-color: white !important;
        }

        .sidebar {
            background-color: #f8f9fa;
            min-height: calc(100vh - 76px);
            border-right: 1px solid #e9ecef;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav li { margin-bottom: 0.5rem; }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #6c757d;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar-nav a:hover {
            background-color: rgba(0, 86, 179, 0.1);
            color: var(--primary-color);
        }

        .sidebar-nav a.active {
            background-color: var(--primary-color);
            color: white;
        }

        .sidebar-nav a i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }

        .dashboard-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f3f4;
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .empty-state {
            background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
            border: 2px dashed #e9ecef;
            border-radius: 16px;
            padding: 4rem 2rem;
            text-align: center;
            margin: 2rem 0;
        }

        .empty-state-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, rgba(0, 86, 179, 0.1), rgba(255, 140, 0, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
        }

        .empty-state-icon i {
            font-size: 3rem;
            color: var(--primary-color);
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

        .main-content {
            background-color: #fafbfc;
            min-height: calc(100vh - 76px);
        }

        .welcome-text {
            background: linear-gradient(135deg, var(--primary-color), #1e40af);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                top: 76px;
                left: -250px;
                width: 250px;
                z-index: 1000;
                transition: left 0.3s ease;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0 !important;
            }
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
@endsection

@section('content')
<div class="d-flex">
    <!-- Sidebar -->
    @include('borrower.layouts.sidebar')

    <!-- Main Content -->
    <div class="main-content flex-grow-1">
        <div class="container-fluid p-4">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="dashboard-card p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="h3 fw-bold mb-1 welcome-text">Welcome, {{ Auth::user()->firstname }}!</h1>
                                <p class="text-muted mb-0">Manage your loans and track your financial journey</p>
                            </div>
                            <div class="d-none d-md-block">
                                <div class="text-end">
                                    <div class="small text-muted">Today</div>
                                    <div class="fw-medium" id="current-date"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div class="row">
                <div class="col-12">
                    <div class="dashboard-card">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="ri-money-dollar-circle-line"></i>
                            </div>
                            <h2 class="h3 fw-bold mb-3">Hi, {{ Auth::user()->firstname }}! You Haven't Applied for a Loan Yet.</h2>
                            <p class="text-muted mb-4 fs-5">No worries! It's quick and easy to get started. Apply for a loan today!</p>
                            <button onclick="window.location.href='{{ url('/apply-loan') }}'" class="btn btn-primary-custom btn-lg" id="apply-loan-btn">
                                <i class="ri-add-circle-line me-2"></i>Apply for a Loan
                            </button>

                            <div class="row mt-5">
                                <div class="col-md-4 mb-3 text-center">
                                    <i class="ri-time-line text-primary-custom" style="font-size: 2rem;"></i>
                                    <h5 class="fw-bold mt-2">Quick Process</h5>
                                    <p class="small text-muted">Get approved in as little as 24 hours</p>
                                </div>
                                <div class="col-md-4 mb-3 text-center">
                                    <i class="ri-shield-check-line text-primary-custom" style="font-size: 2rem;"></i>
                                    <h5 class="fw-bold mt-2">Secure & Safe</h5>
                                    <p class="small text-muted">Bank-level protection for your data</p>
                                </div>
                                <div class="col-md-4 mb-3 text-center">
                                    <i class="ri-customer-service-2-line text-primary-custom" style="font-size: 2rem;"></i>
                                    <h5 class="fw-bold mt-2">24/7 Support</h5>
                                    <p class="small text-muted">Our team is ready to assist anytime</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="dashboard-card p-4">
                        <h4 class="fw-bold mb-3">Recent Activity</h4>
                        <div class="text-center py-4">
                            <i class="ri-history-line text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">No recent activity to show</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
</script>
@endsection
