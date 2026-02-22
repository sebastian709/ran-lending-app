@extends('admin.container')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin/payment-page.css') }}">

<div class="container-fluid p-4">
  <div class="section-title d-flex align-items-center">
    <i class="ri-wallet-3-line me-2"></i> Payment Page
  </div>
  <p class="text-muted mb-4">Manage all payment transactions with clear status tracking.</p>

  <!-- Tabs Navigation -->
  <ul class="nav nav-pills justify-content-center mb-4 shadow-sm p-2 rounded" id="pills-tab" role="tablist">
    <li class="nav-item px-2" role="presentation">
      <button class="nav-link active" id="pending-tab" data-bs-toggle="pill" data-bs-target="#pending" type="button" role="tab">
        ⏳ Pending
      </button>
    </li>
    <li class="nav-item px-2" role="presentation">
      <button class="nav-link" id="verified-tab" data-bs-toggle="pill" data-bs-target="#verified" type="button" role="tab">
        ✅ Verified
      </button>
    </li>
    <li class="nav-item px-2" role="presentation">
      <button class="nav-link" id="rejected-tab" data-bs-toggle="pill" data-bs-target="#rejected" type="button" role="tab">
        ❌ Rejected
      </button>
    </li>
  </ul>

  <!-- Tabs Content -->
  <div class="tab-content" id="pills-tabContent">

    <!-- Pending -->
    <div class="tab-pane fade show active" id="pending" role="tabpanel">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
          <h5 class="mb-3 text-warning"><i class="bi bi-hourglass-split"></i> Pending Transactions</h5>
          <div style="width:100%" class="table-responsive">
            <table style="width:100%" id="table_pending" class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Loan ID</th>
                  <th>Borrowers Name</th>
                  <th>Payment</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Verified -->
    <div class="tab-pane fade" id="verified" role="tabpanel">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
          <h5 class="mb-3 text-success"><i class="bi bi-check-circle-fill"></i> Verified Transactions</h5>
          <div style="width:100%" class="table-responsive">
            <table style="width:100%" id="table_verified" class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Loan ID</th>
                  <th>Borrowers Name</th>
                  <th>Payment Date</th>
                  <th>Verification Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Rejected -->
    <div class="tab-pane fade" id="rejected" role="tabpanel">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
          <h5 class="mb-3 text-danger"><i class="bi bi-x-circle-fill"></i> Rejected Transactions</h5>
          <div style="width:100%" class="table-responsive">
            <table style="width:100%" id="table_rejected" class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Loan ID</th>
                  <th>Borrowers Name</th>
                  <th>Submission Date</th>
                  <th>Rejection Date</th>
                  <th>Reference Number</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>



<!-- Modal -->
<div class="modal fade" id="loanModal" tabindex="-1" aria-labelledby="loanModalLabel" aria-hidden="true" data-bs-backdrop="false">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="payment_modal modal-content shadow-lg border-0 rounded-4 overflow-hidden">

      <!-- Header -->
      <div class="modal-header bg-primary text-white py-3">
        <h5 class="modal-title fw-bold" id="loanModalLabel">
          <i class="bi bi-credit-card me-2"></i> Loan Information
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4" id="loanTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active px-4" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
              Profile
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link px-4" id="loan-tab" data-bs-toggle="tab" data-bs-target="#loan" type="button" role="tab">
              Loan Details
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link px-4" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab">
              Payment Details
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link px-4" id="status-tab" data-bs-toggle="tab" data-bs-target="#status" type="button" role="tab">
              Status History
            </button>
          </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">

          <!-- Profile Tab -->
          <div class="tab-pane fade show active" id="profile" role="tabpanel">
            <div class="card border-0 shadow-sm">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table align-middle">
                    <tbody>
                      <tr>
                        <td class="fw-semibold text-secondary">Name</td>
                        <td class="profile_name fw-bold text-dark"></td>
                      </tr>
                      <tr>
                        <td class="fw-semibold text-secondary">Email</td>
                        <td class="profile_email fw-bold text-dark"></td>
                      </tr>
                      <tr>
                        <td class="fw-semibold text-secondary">Phone</td>
                        <td class="profile_phone fw-bold text-dark"></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Loan Details Tab -->
          <div class="tab-pane fade" id="loan" role="tabpanel">
            <div class="card border-0 shadow-sm">
              <div class="card-body">
                <h5 class="text-primary border-bottom pb-2 mb-4 text-center fw-bold">Loan Details</h5>

                <div class="row justify-content-center">
                  <div class="col-10">

                    <div class="row mb-3">
                      <div class="col-6 text-secondary">Loan Amount:</div>
                      <div class="col-6 text-end fw-bold loan_amount"></div>
                    </div>

                    <div class="row mb-3">
                      <div class="col-6 text-secondary">Total Interest:</div>
                      <div class="col-6 text-end fw-bold interest_amount"></div>
                    </div>

                    <div class="row mb-3">
                      <div class="col-6 text-secondary">Loan Term:</div>
                      <div class="col-6 text-end fw-bold loan_term"></div>
                    </div>

                    <div class="row mb-3">
                      <div class="col-6 text-secondary">Payment Progress:</div>
                      <div class="col-6 text-end fw-bold loan_progress"></div>
                    </div>

                    <div class="row mb-3">
                      <div class="col-6 text-secondary">Next Payment Due:</div>
                      <div class="col-6 text-end fw-bold loan_due"></div>
                    </div>

                    <div class="row mb-3">
                      <div class="col-6 text-secondary">Monthly Payment:</div>
                      <div class="col-6 text-end fw-bold loan_monthly"></div>
                    </div>

                    <div class="row mb-3">
                      <div class="col-6 text-secondary">Interest Rate:</div>
                      <div class="col-6 text-end fw-bold loan_rate"></div>
                    </div>

                    <div class="row mb-3">
                      <div class="col-6 text-secondary">Late Payment Penalty:</div>
                      <div class="col-6 text-end fw-bold loan_penalty"></div>
                    </div>

                  </div>
                </div>

              </div>
            </div>
          </div>

          <!-- Payment Details Tab -->
          <div class="tab-pane fade" id="payment" role="tabpanel">
            <div class="card border-0 shadow-sm">
              <div class="card-body">
                <h4 class="fw-bold mb-4 text-primary">Payment Details</h4>

                <div class="table-responsive">
                  <table class="table table-striped align-middle">
                    <tbody>
                      <tr>
                        <th>Date of Payment</th>
                        <td class="pt_date"></td>
                      </tr>
                      <tr>
                        <th>Time of Payment</th>
                        <td class="pt_time"></td>
                      </tr>
                      <tr>
                        <th>Payment Type</th>
                        <td class="pt_type"></td>
                      </tr>
                      <tr>
                        <th>Month Coverage</th>
                        <td class="pt_coverage"></td>
                      </tr>
                      <tr>
                        <th>Amount Paid</th>
                        <td><strong class="pt_amount"></strong></td>
                      </tr>

                      <tr class="table-primary">
                        <th colspan="2" class="text-center">Breakdown</th>
                      </tr>

                      <tr>
                        <th>Interest</th>
                        <td class="pt_interest"></td>
                      </tr>

                      <tr>
                        <th>Principal</th>
                        <td class="pt_principal"></td>
                      </tr>

                      <tr>
                        <th>Penalty</th>
                        <td class="pt_penalty">₱0.00</td>
                      </tr>

                      <tr class="table-warning hidden">
                        <th>Outstanding Balance</th>
                        <td class="pt_outstanding"></td>
                      </tr>

                      <tr>
                        <th>Reference Number</th>
                        <td class="pt_ref"></td>
                      </tr>

                      <tr>
                        <th>Proof of Payment</th>
                        <td><button class="btn btn-sm btn-outline-primary view_payment_attachment">View File</button></td>
                      </tr>

                      <tr>
                        <th>Remarks</th>
                        <td class="pt_rem"></td>
                      </tr>

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Status History Tab -->
          <div class="tab-pane fade" id="status" role="tabpanel">
            <div class="card border-0 shadow-sm">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table align-middle">
                    <thead class="table-light">
                      <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Remarks</th>
                      </tr>
                    </thead>
                    <tbody class="approve_history"></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-primary rounded-pill" id="pending_verify">Verify</button>
        <button type="button" class="btn btn-danger rounded-pill" id="pending_reject">Reject</button>
        <button type="button" class="btn btn-secondary rounded-pill" id="pending_close" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<div id="customBackdrop"></div>
@endsection


