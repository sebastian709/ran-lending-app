@extends('borrower.app')

@section('styles')

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .form-control[readonly] {
            background-color: #f9fafb;
            cursor: not-allowed;
        }
    </style>

@endsection

@section('content')
    <div class="d-flex">
        @include('borrower.layouts.sidebar')

        <div class="main-content flex-grow-1 notif-page">
            <div class="container">
                <div class="card shadow border-0 rounded-4 m-5">
                    <div class="card-body">
                        <div class="row">
                            <!-- Header Row -->
                            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                <h6 class="mb-0 fw-bold">Notifications</h6>
                                <!-- <a href="#" class="small text-primary dropdown-no-close clearAllNotif">Clear All</a> -->
                            </div>

                            <!-- Tabs Row -->
                            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                <a href="#" class="small text-primary dropdown-no-close markAllAsRead">Mark all as read</a>
                                <div>
                                    <!-- <a href="#" class="me-3 fw-semibold text-dark dropdown-no-close">All</a>
                                        <a href="#" class="fw-light text-muted dropdown-no-close">Unread</a> -->
                                    <a href="#" class="small text-primary dropdown-no-close clearAllNotif">Clear All</a>
                                </div>

                            </div>

                            <!-- Notification Items -->
                            <div style="max-height: 80vh; overflow-y: auto;" class="notification-items">

                                <p class="text-center">No Data</p>

                            </div>

                            <!-- Footer -->
                            <div class="text-center py-2">
                                <a href="#" class="text-primary small fw-semibold dropdown-no-close seeMoreNotif">See more
                                    notifications</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
