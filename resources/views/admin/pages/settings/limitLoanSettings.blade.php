@extends('admin.container')

@section('content')
    <div class="container-fluid p-4">
        <div class="section-title d-flex align-items-center">
            <i class="ri-scales-3-line me-2"></i> Loan Limit Setting
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="table-card">
                    <div class="card-body">
                        <div class="position-relative mb-4">
                            <button type="button" data-url="/admin/settings"
                                class="btn small position-absolute start-0 top-50 translate-middle-y p-0">
                                <i class="ri-arrow-go-back-line"></i> Back
                            </button>
                            <h4 class="text-center text-dark m-0 d-none">Loan Limit Setting</h4>
                        </div>

                        <div class="search-filter-container mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="ri-search-line"></i></span>
                                <input type="text" id="searchReferral" class="form-control" placeholder="Search code">
                            </div>
                            <div class="input-group select-group">
                                <span class="input-group-text bg-white"><i class="ri-filter-2-line"></i></span>
                                <select id="filterReferral" class="form-select">
                                    <option value="all">All</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="btn-group view-toggle" role="group" aria-label="View toggle">
                                <button type="button" class="btn btn-outline-primary active" id="listViewBtn"
                                    title="List View">
                                    <i class="ri-list-unordered"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary" id="cardViewBtn" title="Card View">
                                    <i class="ri-layout-grid-line"></i>
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive" id="tableWrapper">
                            <table class="table table-bordered" id="loanLimitSetting">
                                <thead>
                                    <tr>
                                        <th>Referral Code</th>
                                        <th>Description</th>
                                        <th>Availability</th>
                                        <th>Date Created</th>
                                        <th>Created by</th>
                                        <th>Code Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <div id="loanLimitCardContainer" class="row g-3">
                            <!-- Cards rendered here -->
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 mx-3">
                        <div id="tableInfo" class="small text-muted fw-semibold"></div>
                        <nav>
                            <ul id="pagination" class="pagination pagination-sm mb-0"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
