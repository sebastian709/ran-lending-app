@extends('admin.container')

@section('content')
<div class="container-fluid p-4 cp-customer-container">
    <div class="section-title d-flex align-items-center">
        <i class="ri-user-community-line me-2"></i> Customer Management
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="table-card">
                <div class="card-body pt-2">
                    <h3 class="mb-4 cp-page-title d-none">Customer Management</h3>

                    <!-- Tabs -->
                    <!-- <ul class="nav nav-tabs cp-status-tabs" id="cp-statusTab" role="tablist">
                        <li class="nav-item"><a class="nav-link active cp-tab-link" data-bs-toggle="tab" href="#cp-all" role="tab">All</a></li>
                        <li class="nav-item"><a class="nav-link cp-tab-link" data-bs-toggle="tab" href="#cp-active" role="tab">Active</a></li>
                        <li class="nav-item"><a class="nav-link cp-tab-link" data-bs-toggle="tab" href="#cp-scheduled" role="tab">Scheduled</a></li>
                        <li class="nav-item"><a class="nav-link cp-tab-link" data-bs-toggle="tab" href="#cp-closed" role="tab">Closed</a></li>
                        <li class="nav-item"><a class="nav-link cp-tab-link" data-bs-toggle="tab" href="#cp-rejected" role="tab">Rejected</a></li>
                        <li class="nav-item"><a class="nav-link cp-tab-link" data-bs-toggle="tab" href="#cp-cancelled" role="tab">Cancelled</a></li>
                    </ul> -->

                    <!-- Tab Content -->
                    <div class="tab-content cp-tab-content">
                        <div class="tab-pane fade show active cp-tab-pane" id="cp-all" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped cp-table" id="cp-all-table">
                                    <thead></thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade cp-tab-pane" id="cp-active" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped cp-table" id="cp-active-table">
                                    <thead></thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade cp-tab-pane" id="cp-scheduled" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped cp-table" id="cp-scheduled-table">
                                    <thead></thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade cp-tab-pane" id="cp-closed" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped cp-table" id="cp-closed-table">
                                    <thead></thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade cp-tab-pane" id="cp-rejected" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped cp-table" id="cp-rejected-table">
                                    <thead></thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade cp-tab-pane" id="cp-cancelled" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped cp-table" id="cp-cancelled-table">
                                    <thead></thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- /.tab-content -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
