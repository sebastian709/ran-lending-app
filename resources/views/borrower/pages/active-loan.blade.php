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
            <!-- Welcome Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="dashboard-card p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex ">
                                <h1 class="h3 fw-bold mb-0 me-2">Good Day,</h1>
                                <h1 class="h3 fw-bold mb-0 welcome-text">{{ Auth::user()->firstname }}!</h1>
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

            <!-- Cards -->
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="dashboard-card p-3 text-center">
                        <div class="text-primary-custom mb-2">
                            <i class="ri-bank-card-line" style="font-size: 2rem;"></i>
                        </div>
                        <div class="h4 fw-bold mb-1">₱0</div>
                        <div class="small text-muted">Loan Amount</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="dashboard-card p-3 text-center">
                        <div class="text-success mb-2">
                            <i class="ri-calendar-schedule-line" style="font-size: 2rem;"></i>
                        </div>
                        <div class="h4 fw-bold mb-1">12 Months</div>
                        <div class="small text-muted">Loan Tenure</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="dashboard-card p-3 text-center">
                        <div class="text-warning mb-2">
                            <i class="ri-wallet-3-line" style="font-size: 2rem;"></i>
                        </div>
                        <div class="h4 fw-bold mb-1">₱8,500</div>
                        <div class="small text-muted">Monthly Due Amount</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="dashboard-card p-3 text-center">
                        <div class="text-info mb-2">
                            <i class="ri-calendar-event-line" style="font-size: 2rem;"></i>
                        </div>
                        <div class="h4 fw-bold mb-1">Every 1st of the month</div>
                        <div class="small text-muted">Monthly Due Date</div>
                    </div>
                </div>
            </div>

           <!-- Upcoming Payment Box -->
            <div class="dashboard-card shadow-sm rounded bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap p-4">
                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <!-- <i class="ri-alarm-warning-line text-primary" style="font-size: 2rem;"></i> -->
                        <div class="ms-3">
                            <h5 class="fw-bold mb-0">
                                Next Payment:
                                <h4 class="fw-bold text-primary mb-1">₱8,500</h4>
                            </h5>
                            <small class="text-muted">Due on <strong>August 1, 2025</strong></small>
                        </div>
                    </div>
                    <div class="text-end">
                        <a href="/payment" class="btn btn-primary btn-md">
                            <i class="ri-wallet-line me-1"></i> Pay Now
                        </a>
                    </div>
                </div>

                <!-- Repayment Schedule Button -->
                <div class="mt-3 text-center bg-light p-2">
                    <a href="/repayment-schedule" class="btn rep_btn">
                        <i class="ri-calendar-schedule-line me-1"></i>Repayment Schedule<i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

