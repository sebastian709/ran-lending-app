@extends('borrower.app')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
                            <a href="#" class="small text-primary dropdown-no-close">Clear All</a>
                        </div>

                        <!-- Tabs Row -->
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <div>
                                <a href="#" class="me-3 fw-semibold text-dark dropdown-no-close">All</a>
                                <a href="#" class="fw-light text-muted dropdown-no-close">Unread</a>
                            </div>
                            <a href="#" class="small text-primary dropdown-no-close">Mark all as read</a>
                        </div>

                        <!-- Notification Items -->
                        <div style="max-height: 80vh; overflow-y: auto;" class="notification-items">

                            <!-- Notification 1 -->
                            <div class="px-3 py-2 border-bottom items unread position-relative">
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
                            </div>

                            <!-- Notification 2 -->
                            <div class="px-3 py-2 border-bottom items read position-relative">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div class="d-flex align-items-start">
                                        <i class="ri-shopping-bag-3-line text-success fs-5 me-2"></i>
                                        <div>
                                            <p class="mb-1 small">New order received</p>
                                            <small class="text-muted">15m ago</small>
                                        </div>
                                    </div>
                                    <div class="position-relative">
                                        <i class="ri-more-2-fill text-muted fs-6" role="button" id="notifMore2"
                                            data-bs-toggle="dropdown" aria-expanded="false"></i>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifMore2">
                                            <li><a class="dropdown-item" href="#">Mark as read</a></li>
                                            <li><a class="dropdown-item" href="#">Clear notification</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Notification 3 -->
                            <div class="px-3 py-2 border-bottom items unread position-relative">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div class="d-flex align-items-start">
                                        <i class="ri-checkbox-circle-line text-warning fs-5 me-2"></i>
                                        <div>
                                            <p class="mb-1 small">Task completed successfully</p>
                                            <small class="text-muted">1h ago</small>
                                        </div>
                                    </div>
                                    <div class="position-relative">
                                        <i class="ri-more-2-fill text-muted fs-6" role="button" id="notifMore3"
                                            data-bs-toggle="dropdown" aria-expanded="false"></i>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifMore3">
                                            <li><a class="dropdown-item" href="#">Mark as read</a></li>
                                            <li><a class="dropdown-item" href="#">Clear notification</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Notification 4 -->
                            <div class="px-3 py-2 border-bottom items unread position-relative">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div class="d-flex align-items-start">
                                        <i class="ri-user-follow-line text-info fs-5 me-2"></i>
                                        <div>
                                            <p class="mb-1 small">New follower: John Doe</p>
                                            <small class="text-muted">1d ago</small>
                                        </div>
                                    </div>
                                    <div class="position-relative">
                                        <i class="ri-more-2-fill text-muted fs-6" role="button" id="notifMore4"
                                            data-bs-toggle="dropdown" aria-expanded="false"></i>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifMore4">
                                            <li><a class="dropdown-item" href="#">Mark as read</a></li>
                                            <li><a class="dropdown-item" href="#">Clear notification</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Notification 5 -->
                            <div class="px-3 py-2 border-bottom items read position-relative">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div class="d-flex align-items-start">
                                        <i class="ri-star-smile-line text-danger fs-5 me-2"></i>
                                        <div>
                                            <p class="mb-1 small">You earned a new badge</p>
                                            <small class="text-muted">2w ago</small>
                                        </div>
                                    </div>
                                    <div class="position-relative">
                                        <i class="ri-more-2-fill text-muted fs-6" role="button" id="notifMore5"
                                            data-bs-toggle="dropdown" aria-expanded="false"></i>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifMore5">
                                            <li><a class="dropdown-item" href="#">Mark as read</a></li>
                                            <li><a class="dropdown-item" href="#">Clear notification</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Footer -->
                        <div class="text-center py-2">
                            <a href="#" class="text-primary small fw-semibold dropdown-no-close">See more
                                notifications</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection