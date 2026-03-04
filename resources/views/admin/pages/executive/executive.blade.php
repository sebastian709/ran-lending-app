@extends('admin.container')

@section('content')

<div class="container-fluid p-4 executive-page"
    data-admin-name="{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}"
    data-current-date="{{ date('Y-m-d') }}">

    <!-- PAGE TITLE -->
    <div class="section-title d-flex align-items-center justify-content-between">
        <span><i class="ri-bank-line me-2"></i> Executive Investment</span>
        <button class="btn btn-sm btn-outline-primary-custom money_status hidden">Hide</button>
    </div>

    <!-- ===================== -->
    <!-- 1. KEY METRICS -->
    <!-- ===================== -->
    <div class="row g-3 mb-4">
        <div class="col-md-3" title="Includes Interest and Penalty">
            <div class="card metric-card">
                <div class="card-body">
                    <h6>Total Fund</h6>
                    <h4>₱ <span class="total_fund">0.00</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card">
                <div class="card-body">
                    <h6>Remaining Fund</h6>
                    <h4>₱ <span class="remaining_fund">0.00</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card">
                <div class="card-body">
                    <h6>Ongoing Loan</h6>
                    <h4>₱ <span class="ongoing_fund">0.00</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card">
                <div class="card-body">
                    <h6>Tithes</h6>
                    <h4>₱ <span class="tithes">0.00</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card">
                <div class="card-body">
                    <h6>My Shared Fund</h6>
                    <h4>₱ <span class="shared_fund">0.00</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card">
                <div class="card-body">
                    <h6>My Dividend</h6>
                    <h4>₱ <span class="dividend">0.00</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card">
                <div class="card-body">
                    <h6>Divident %</h6>
                    <h4><span class="div_percent">0.00</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card">
                <div class="card-body">
                    <h6>Interest & Penalty Earned</h6>
                    <h4>₱ <span class="misc_fund">0.00</span></h4>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ===================== -->
    <!-- 3. FUND MANAGEMENT -->
    <!-- ===================== -->
    <div class="table-card executive-fund-card mb-4">
        <div class="table-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1">Fund Management</h3>
                <p class="mb-0">Track capital entries and withdrawals in one place.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary-custom openAddInvestment">
                    <i class="bi bi-plus-circle"></i> Add Investment
                </button>
                <button class="btn btn-sm btn-outline-danger openWithdraw">
                    <i class="bi bi-dash-circle"></i> Withdraw Funds
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped table-bordered" id="investmentTable">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Notes</th>
                        <th>Attachment</th>
                        <th>Date</th>
                    </tr> 
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
