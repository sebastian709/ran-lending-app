@extends('admin.container')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                <h4 class="mb-3 mb-md-0">
                     Loan Request List
                </h4>
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div class="input-group" style="min-width: 250px;">
                        <span class="input-group-text bg-white"><i class="ri-search-line"></i></span>
                        <input type="text" id="search" class="form-control" placeholder="Search by name, ID, referral...">
                    </div>
                    <!-- <span id="counter" class="badge bg-secondary"></span> -->
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center" id="loanTable">
                    <thead class="table-light">
                        <tr>
                            <th>Loan ID</th>
                            <th>Borrower Name</th>
                            <th>Loan Type</th>
                            <th>Loan Amount</th>
                            <th>Loan Tenure</th>
                            <th>Interest Rate</th>
                            <th>Purpose</th>
                            <th>Request Date</th>
                            <th>Referral</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="loanBody"></tbody>
                </table>
            </div>

            <div id="emptyState" class="text-center text-muted my-4" style="display: none;">
                <i class="bi bi-folder-x" style="font-size: 2rem;"></i><br>
                No loan requests found.
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div><strong>Total Results:</strong> <span id="totalData">0</span></div>
                <nav>
                    <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<div id="customLoanPopup" class="custom-popup d-none">
    <div class="popup-content">
        <button class="btn-close" id="closePopup"></button>
        <h4 class="mb-3">Loan Request: <span class="alr_loan_id"></span></h4>

        <div id="editNotice" class="alert alert-warning py-2 px-3 mb-4 rounded">
            You are editing this as <strong class="alr_edit_as_name">{{ Auth::user()->firstname. ' ' . Auth::user()->lastname }}</strong>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="loanTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#detailsTab" type="button">Loan Details</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link viewdocuments" data-bs-toggle="tab" data-bs-target="#documentsTab" type="button">Uploaded Documents</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#historyTab" type="button">Loan History</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activityTab" type="button">Activity Log</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link viewComment" data-bs-toggle="tab" data-bs-target="#commentTab" type="button">Comments</button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Loan Details -->
            <div class="tab-pane fade show active alr_loan_details_content" id="detailsTab">
                <!-- Content Here -->
            </div>

            <!-- Uploaded Documents -->
            <div class="tab-pane fade" id="documentsTab">
                
            </div>

            <!-- Loan History -->
            <div class="tab-pane fade" id="historyTab" role="tabpanel">
                
            </div>

            <!-- Activity Log -->
            <div class="tab-pane fade" id="activityTab">
                
            </div>

             <!-- Comment Log -->
            <div class="tab-pane fade" id="commentTab">
                
            </div>

            <!-- Approval Tracking -->
            <!-- <div class="tab-pane fade" id="approvalTab">
                <div class="mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked>
                        <label class="form-check-label">Admin 1</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox">
                        <label class="form-check-label">Admin 2</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox">
                        <label class="form-check-label">Admin 3</label>
                    </div>
                </div>
            </div>
        </div> -->
    </div>

   <!-- Approval Section (Right Aligned) -->
    <div class="mt-4 border-top pt-3 d-flex justify-content-between align-items-center">
        <div>
            <button class="btn btn-primary" id="approveBtn" admin_id="{{ Auth::user()->id }}">
                <i class="ri-file-check-fill"></i> Process Request
            </button>
        </div>
        <div class="text-end">
            <h6 class="mb-3">
                <strong>Pending Approval</strong>
                <span class="badge bg-secondary ms-2" id="approvalCounter">0/3</span>
            </h6>

           <div class="d-flex gap-3 justify-content-end">
                @foreach ($admins as $admin)
                    <div class="form-check">
                        <input class="form-check-input admin-approval" 
                            style="pointer-events:none" 
                            admin_id="{{ Auth::user()->id }}"
                            type="checkbox" 
                            id="admin{{ $admin->id }}" 
                            value="{{ $admin->id }}"
                            @if ($admin->id !== Auth::user()->id) disabled @endif>
                        <label class="form-check-label" for="admin{{ $admin->id }}">
                            {{ $admin->full_name }}
                        </label>
                        <p class="text-success status_approval"></p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


<!-- sample data muna -->
<!-- Lightbox2 JS -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('js/loanrequest.js') }}"></script> -->

@endsection

