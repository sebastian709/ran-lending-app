@extends('admin.container')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body mx-3 my-scroll-hidden bg-white" style="height: 80vh; overflow-y: auto;">
                        <div class="row align-items-center mb-3 sticky-top bg-white py-2 border-bottom"
                            style="z-index: 1020;">
                            <div class="col-md-6 col-12">
                                <h5 class="mb-0">Admin Settings</h5>
                            </div>
                            <div class="col-md-6 col-12 text-md-end mt-2 mt-md-0">
                                <div class="input-group input-group-sm justify-content-md-end align-items-center">
                                    <small class="fw-bold me-2">Search</small>
                                    <input type="text" id="admin-search-input" class="form-control border"
                                        placeholder="Search..." aria-label="Search">
                                    <!-- <button id="admin-search-btn" class="btn btn-outline-secondary" type="button">
                                                                    <i class="ri-search-line"></i>
                                                                </button> -->
                                    <div id="admin-search-suggestions" class="dropdown-menu show d-none suggestion-box">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <label class="fw-bold">General Settings</label><br>
                                <small class="text-muted">Manage basic system preferences and default
                                    configurations.</small>
                            </div>
                            <hr class="my-3">
                            <form id="as-general-settings">
                                <div class="col-lg-12">
                                    <div class="d-flex flex-column mx-3">
                                        <!-- content here -->
                                    </div>
                                </div>
                                <div class="col-lg-12 d-flex mt-4 g-btn-container">
                                    <button type="button" class="btn btn-primary btn-sm a-btn-update">Update</button>
                                    <div class="btn-group p-end" role="group">
                                        <button type="button" class="btn btn-primary btn-sm a-btn-save" hidden>Save</button>
                                        <button type="button" class="btn btn-light btn-sm a-btn-cancel"
                                            hidden>Cancel</button>
                                    </div>
                                </div>
                            </form>
                            <hr class="my-3">
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <label class="fw-bold">Loan Settings</label><br>
                                <small class="text-muted">Configure loan related preferences.</small>
                            </div>
                            <hr class="my-3">
                            <form id="as-loan-settings">
                                <div class="col-lg-12">
                                    <div class="d-flex flex-column mx-3">
                                        <label>Loan Interest</label>
                                        <div class="d-flex">
                                            <input type="text" name="loan_interest"
                                                value="{{ $result['loan_interest'] ?? 0 }}"
                                                class="w-lg-25 border-0 border-bottom rounded-0 shadow-none a-loan-interest"
                                                disabled>
                                            <span>%</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 d-flex mt-4 g-btn-container">
                                    <button type="button" class="btn btn-primary btn-sm a-btn-update">Update</button>
                                    <div class="btn-group p-end" role="group">
                                        <button type="button" class="btn btn-primary btn-sm a-btn-save" hidden>Save</button>
                                        <button type="button" class="btn btn-light btn-sm a-btn-cancel"
                                            hidden>Cancel</button>
                                    </div>
                                </div>
                            </form>
                            <hr class="my-3">
                        </div>
                        <div class="row mt-4" {{ Auth::user()->is_super_admin == 1 ? '' : 'hidden' }}>
                            <div class="col-lg-12">
                                <label class="fw-bold">Loan Request Access</label><br>
                                <small class="text-muted">Grant access to the admins.</small>
                            </div>
                            <hr class="my-3">
                            <form id="as-loan-request-access">
                                <div class="col-lg-12">
                                    <div class="d-flex flex-column mx-3">
                                        <label>Test</label>
                                        <div class="d-flex">
                                            
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr class="my-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection