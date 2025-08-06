@extends('admin.container')

@section('content')
<div class="container py-4">
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
                            <th>Loan Amount</th>
                            <th>Loan Tenure</th>
                            <th>Interest Rate</th>
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
        <h4 class="mb-3">Loan Request: <span>LN-001</span></h4>

        <div id="editNotice" class="alert alert-warning py-2 px-3 mb-4 rounded">
            You are editing this as <strong>Almira</strong>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="loanTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#detailsTab" type="button">Loan Details</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#documentsTab" type="button">Uploaded Documents</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#historyTab" type="button">Loan History</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activityTab" type="button">Activity Log</button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Loan Details -->
            <div class="tab-pane fade show active" id="detailsTab">
                <div class="mt-4 px-3 py-4 border rounded bg-light shadow-sm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Amount:</strong> ₱50,000<br>
                            <strong>Loan Term:</strong> 12 months<br>
                            <strong>Interest:</strong> 5%
                        </div>
                        <div class="col-md-6">
                            <strong>Purpose:</strong> Business expansion<br>
                            <strong>Request Date:</strong> 2025-08-01<br>
                            <strong>Last Updated:</strong> 2025-08-05<br>
                            <strong>Referral:</strong> Code
                        </div>
                    </div>
                    <div class="mt-3">
                        <strong>Status:</strong> Pending Approval
                    </div>
                </div>
            </div>

            <!-- Uploaded Documents -->
            <div class="tab-pane fade" id="documentsTab">
                <div class="mt-3">
                    <p class="text-center fw-bold mb-4">Borrower name's Documents.</p>

                    <!-- Document Tabs -->
                    <ul class="nav nav-tabs justify-content-center mb-3" id="docTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#incomeTab" type="button" role="tab">Proof of Income</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#qrTab" type="button" role="tab">QR Code</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#idTab" type="button" role="tab">Government ID</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#supportTab" type="button" role="tab">Supporting Docs</button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content text-center">
                        <div class="tab-pane fade show active" id="incomeTab" role="tabpanel">
                            <img src="https://templatelab.com/wp-content/uploads/2018/03/income-verification-letter-01.jpg" alt="Proof of Income" class="img-fluid border rounded" style="max-height: 300px;">
                        </div>
                        <div class="tab-pane fade" id="qrTab" role="tabpanel">
                            <img src="https://www.researchgate.net/profile/Vinod-Shukla/publication/340398294/figure/fig3/AS:876175308103681@1585907880294/Sample-Figure-of-QR-Code-QR-code-works-in-a-simple-way-It-all-starts-of-by-feeding-in_Q320.jpg" alt="QR Code" class="img-fluid border rounded" style="max-height: 300px;">
                        </div>
                        <div class="tab-pane fade" id="idTab" role="tabpanel">
                            <img src="" alt="Government ID" class="img-fluid border rounded" style="max-height: 300px;">
                        </div>
                        <div class="tab-pane fade" id="supportTab" role="tabpanel">
                            <img src="https://st.depositphotos.com/1186248/2751/i/450/depositphotos_27516565-stock-photo-free-sample.jpg" alt="Supporting Documents" class="img-fluid border rounded" style="max-height: 300px;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan History -->
            <div class="tab-pane fade" id="historyTab" role="tabpanel">
                <div class="mt-4 px-3 py-4 border rounded bg-light shadow-sm">
                    <h5 class="mb-4">
                        <i class="bi bi-journal-text me-2"></i> Loan History
                    </h5>

                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <ul class="list-unstyled ms-3">
                                <li><strong>Total Loans Taken:</strong> 3</li>
                                <li><strong>Total Loan Amount:</strong> ₱250,000</li>
                                <li><strong>Date of First Loan:</strong> January 15, 2024</li>
                                <li><strong>Referral:</strong> Maria Santos</li>
                                <br>
                                <li><strong>Violations:</strong> 1
                                    <div class="text-muted small ms-3">→ 3 consecutive months of no payment = 1 violation</div>
                                </li>
                                <li><strong>Penalties:</strong> 3
                                    <div class="text-muted small ms-3">→ Every 7 days after monthly due date = 1 penalty</div>
                                </li>

                                <li><strong>Last Loan Date: </strong>July 15, 2025</li><br>
                                <li><strong>Remarks:</strong> <span class="badge bg-success">Excellent</span></li>
                            </ul>
                        </li>
                    </ul>
                    <div class="text-center mt-4">
                        <a href="/admin/customer/123" class="btn btn-primary px-4">See More</a>
                    </div>
                </div>
            </div>

            <!-- Activity Log -->
            <div class="tab-pane fade" id="activityTab">
                <ul id="activityLogCustom" class="list-group mt-3">
                    <li class="list-group-item">2025-08-01: [Admin] change the status to [status]</li>
                    <li class="list-group-item">2025-08-05: [Admin] rejected [field name] from application ID</li>
                </ul>
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
            <button class="btn btn-primary" id="approveBtn">
                Approve
            </button>
        </div>
        <div class="text-end">
            <h6 class="mb-3">
                <strong>Pending Approval</strong>
                <span class="badge bg-secondary ms-2" id="approvalCounter">0/3</span>
            </h6>

            <div class="d-flex gap-3 justify-content-end">
                <div class="form-check">
                    <input class="form-check-input admin-approval" type="checkbox" id="admin1">
                    <label class="form-check-label" for="admin1">Admin 1</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input admin-approval" type="checkbox" id="admin2">
                    <label class="form-check-label" for="admin2">Admin 2</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input admin-approval" type="checkbox" id="admin3">
                    <label class="form-check-label" for="admin3">Admin 3</label>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- sample data muna -->
<!-- Lightbox2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('js/loanrequest.js') }}"></script>

@endsection

