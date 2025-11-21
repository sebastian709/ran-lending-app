@extends('admin.container')

@section('content')

    <div class="container-fluid p-4 ran_dashboard">

        <div class="container-xl sticky-filters">
            <div class="filter-section">
                <div class="filter-group">
                    <label>Date Filter</label>
                    <button class="btn-date">Week</button>
                    <button class="btn-date active">Month</button>
                    <button class="btn-date">Year</button>
                </div>
                <div class="export-group">
                    <label style="margin-right: 0.5rem;">Export</label>
                    <button class="btn-export pdf">
                        <i class="bi bi-file-pdf"></i> PDF
                    </button>
                    <button class="btn-export excel">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                    </button>
                </div>
            </div>
        </div>
        <!-- Top KPIs -->
         
        <div class="section-title">Quick Statistics</div>
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: green"><i class="bi bi-currency-dollar"></i></div>
                    <div class="stat-label">Available Money</div>
                    <div class="stat-value">₱2,450,000</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--success);">✓</div>
                    <div class="stat-label">Balance</div>
                    <div class="stat-value">₱1,275,000</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: orange"><i class="bi bi-bar-chart-fill"></i></div>
                    <div class="stat-label">Total Tithes</div>
                    <div class="stat-value">10%</div>
                    <small class="text-muted">Interest + Penalty</small>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: #d81c1c;"><i class="bi bi-graph-up-arrow"></i></div>
                    <div class="stat-label">Miscellaneous</div>
                    <div class="stat-value">10%</div>
                    <small class="text-muted">Interest + Penalty</small>
                </div>
            </div>
        </div>


        <!-- Loan Applications -->
        <div class="section-title">Loan Applications (This Month)</div>
        <div class="row g-4 mb-5" id="total_applications">
                      
        </div>


        <div class="row g-4">

            <!-- Scheduled Loans -->
            <div class="col-lg-6 col-md-12">
                <div class="section-title">Scheduled Loans</div>
                <div class="table-card" id="scheduled_div">
                   
                </div>
            </div>

            <!-- Recent Loan Applications -->
            <div class="col-lg-6 col-md-12">
                <div class="section-title">Recent Loan Applications</div>
                <div class="table-card mb-5" id="recent_div">
                
                </div>
            </div>

        </div>


        <!-- Added Recent Payments table -->
        <div class="section-title">Recent Payments</div>
        <div class="table-card mb-5" id="recentPayments_div">
            
        </div>

        <!-- Added Loan Insight section with detailed financial metrics -->
        <div class="section-title">Loan Insight</div>
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--primary);">💳</div>
                    <div class="stat-label">Total Amount Disbursed</div>
                    <div class="stat-value">₱2,450,000</div>
                    <small class="text-muted">Principal Loan Amount</small>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--warning);">📊</div>
                    <div class="stat-label">Outstanding Balance</div>
                    <div class="stat-value">₱1,275,000</div>
                    <small class="text-muted">Unpaid Principal + Interest</small>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--success);">✓</div>
                    <div class="stat-label">Total Amount Repaid</div>
                    <div class="stat-value">₱1,175,000</div>
                    <small class="text-muted">All Verified Repayments</small>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: #8b5cf6;">📈</div>
                    <div class="stat-label">Next 30 Days Expected</div>
                    <div class="stat-value">₱600,000</div>
                    <small class="text-muted">Expected Repayments</small>
                </div>
            </div>
        </div>

        <!-- Added Payment Behaviour section with payment status metrics -->
        <div class="section-title">Payment Behaviour</div>
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--success);">✓</div>
                    <div class="stat-label">On-time Payments</div>
                    <div class="stat-value">82%</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--warning);">⏰</div>
                    <div class="stat-label">Late Payments</div>
                    <div class="stat-value">12%</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: #06b6d4;">📦</div>
                    <div class="stat-label">Partial Payments</div>
                    <div class="stat-value">6%</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--danger);">✕</div>
                    <div class="stat-label">Defaulted Loans</div>
                    <div class="stat-value">4</div>
                </div>
            </div>
        </div>

        <!-- Added Borrower Insight section with borrower statistics -->
        <div class="section-title">Borrower Insight</div>
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--primary);">👥</div>
                    <div class="stat-label">Total Borrowers</div>
                    <div class="stat-value">540</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: #06b6d4;">🔄</div>
                    <div class="stat-label">Active Borrowers</div>
                    <div class="stat-value">220</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--success);">⭐</div>
                    <div class="stat-label">Good Payers</div>
                    <div class="stat-value">185</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--danger);">⚠️</div>
                    <div class="stat-label">With Violations</div>
                    <div class="stat-value">35</div>
                </div>
            </div>
        </div>

        <!-- Added Top 5 Borrowers section with single card highlighting top borrower -->
        <div class="section-title">Top 5 Borrower</div>
        <div class="row g-4 mb-5" id="top_borrowers">
        </div>

        <!-- Added Financial Overview section with revenue and interest metrics -->
        <div class="section-title">Financial Overview</div>
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--success);">💵</div>
                    <div class="stat-label">Interest Earned</div>
                    <div class="stat-value interest_earned"></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--danger);">🚨</div>
                    <div class="stat-label">Penalties Collected</div>
                    <div class="stat-value penalty_earned"></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--primary);">📈</div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value revenue_earned"></div>
                </div>
            </div>
        </div>
        <!-- Floating Calendar Icon -->
        <div id="calendar-float-icon">
            <i class="bi bi-calendar3"></i>
        </div>

        <!-- Floating Calendar Popup -->
        <div id="calendar-overlay" class="hidden"></div>
        <div id="calendar-popup">
            <div id="calendar"></div>
        </div>        
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/home.js') }}"></script>

@endsection