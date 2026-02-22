@extends('admin.container')

@section('content')
    <div class="container-fluid p-4">
        <div class="section-title d-flex align-items-center">
            <i class="ri-settings-3-line me-2"></i> Admin Settings
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="table-card">
                    <div class="card-body mx-3 my-scroll-hidden bg-white" style="height: 80vh; overflow-y: auto;">
                        <div class="row align-items-center mb-3 sticky-top bg-white py-2" style="z-index: 1020;">
                            <div class="col-md-6 col-12">
                                <h5 class="mb-0 d-none">Admin Settings</h5>
                            </div>
                            <div class="col-md-6 col-12 text-md-end mt-2 mt-md-0">
                                <div class="input-group input-group-sm justify-content-md-end align-items-center">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="ri-search-line"></i></span>
                                        <input type="text" id="admin-search-input" class="form-control border"
                                            placeholder="Search..." aria-label="Search">
                                    </div>
                                    <div id="admin-search-suggestions" class="dropdown-menu show d-none suggestion-box">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Accordion section -->
                        <div class="accordion pt-4" id="settingsAccordion">
                            <!-- General Settings Accordion Item -->
                            <div class="accordion-item border-0 mb-4 settings-card-container">
                                <h2 class="accordion-header header" id="headingGeneral">
                                    <button class="accordion-button p-0 collapsed admin_settings_container" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseGeneral" aria-expanded="false"
                                        aria-controls="collapseGeneral"
                                        style="background:none; border:none; box-shadow:none; padding-left: 0;">
                                        <div>
                                            <label class="fw-bold mb-0 py-2">General Settings</label><br>
                                            <small class="text-muted">Manage basic system preferences and default
                                                configurations.</small>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapseGeneral" class="accordion-collapse collapse"
                                    aria-labelledby="headingGeneral" data-bs-parent="#settingsAccordion">
                                    <div class="accordion-body p-0">
                                        <form id="as-loan-settings" class="my-3">
                                            <div class="d-flex flex-column mx-3">
                                                <label>Loan Interest</label>
                                                <div class="d-flex align-items-center">
                                                    <input type="text" name="loan_interest"
                                                        value="{{ $result['loan_interest'] ?? 0 }}"
                                                        class="w-lg-25 border-0 border-bottom rounded-0 shadow-none a-loan-interest"
                                                        disabled>
                                                    <span class="ms-2">%</span>
                                                </div>
                                            </div>
                                            <div class="d-flex mt-4 g-btn-container">
                                                <button type="button"
                                                    class="btn btn-primary btn-sm a-btn-update">Update</button>
                                                <div class="btn-group ms-auto" role="group">
                                                    <button type="button" class="btn btn-primary btn-sm a-btn-save"
                                                        hidden>Save</button>
                                                    <button type="button" class="btn btn-light btn-sm a-btn-cancel"
                                                        hidden>Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <!-- Loan Request Access (conditional) -->
                            @if(Auth::user()->is_super_admin == 1)
                                <div class="settings-card-container">
                                    <div class="accordion-like d-flex justify-content-between align-items-center header"
                                        style="cursor: pointer;">
                                        <div class="accordion-content admin_settings_container header">
                                            <label class="fw-bold mb-0">Loan Request Access</label><br>
                                            <small class="text-muted">Grant access to the admins.</small>
                                        </div>
                                        <div class="accordion-icon">
                                            <i class="ri-arrow-right-s-line fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <hr>
                            <div class="settings-card-container" data-url="/admin/settings/loan-limit-settings">
                                <div class="header accordion-like d-flex justify-content-between align-items-center"
                                    style="cursor: pointer;">
                                    <div class="accordion-content admin_settings_container">
                                        <label class="fw-bold mb-0">Loan Limit Setting</label><br>
                                        <small class="text-muted">Manage borrowing limits for accounts.</small>
                                    </div>
                                    <div class="accordion-icon">
                                        <i class="ri-arrow-right-s-line fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
