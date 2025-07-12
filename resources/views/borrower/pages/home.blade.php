@extends('borrower.app')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
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
