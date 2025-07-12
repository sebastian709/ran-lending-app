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
                            @include('borrower.layouts.loan-state')
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
@stack('scripts')
@endsection
