@extends('admin.container')

@section('content')
    <div class="container-md notif-page">
        <div class="row my-2">
            <div class="col-lg-12 py-1">
                <div class="card">
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