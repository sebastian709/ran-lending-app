@extends('admin.container')

@section('content')

    <div class="container-fluid p-4 ran_dashboard">

        <div class="container-xl sticky-filters">
            <div class="row filter-section">
                <div class="col-6 filter-group">
                    <label>Date Filter</label>
                    <button class="btn-date" data-filter="week">Week</button>
                    <button class="btn-date active" data-filter="month">Month</button>
                    <button class="btn-date" data-filter="year">Year</button>
                </div>
                <div class="col-3 export-group">
                    <label style="margin-right: 0.5rem;">Export</label>
                    <a href="{{ route('applications.pdf') }}" class="btn-export pdf"><i class="bi bi-file-pdf"></i> PDF</a>
                    <a href="{{ route('applications.excel') }}" class="btn-export excel"><i class="bi bi-file-earmark-excel"></i> Excel</a>
                </div>
                <!-- <div class="col-2 calendar-container">
                    <button class="btn-date calendar-btn">
                        <i class="bi bi-calendar-date"></i> Calendar
                    </button>
                </div> -->
            </div>
        </div>
        <!-- Top KPIs -->
         
        <div class="section-title">Quick Statistics</div>
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: green"><i class="bi bi-currency-dollar"></i></div>
                    <div class="stat-label">Available Money</div>
                    <div class="stat-value quick__money"></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--success);">✓</div>
                    <div class="stat-label">Balance</div>
                    <div class="stat-value quick_balance"></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: orange"><i class="bi bi-bar-chart-fill"></i></div>
                    <div class="stat-label">Total Tithes</div>
                    <div class="stat-value quick_tithes"></div>
                    <small class="text-muted">Interest + Penalty</small>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: #d81c1c;"><i class="bi bi-graph-up-arrow"></i></div>
                    <div class="stat-label">Miscellaneous</div>
                    <div class="stat-value quick_misc"></div>
                    <small class="text-muted">Interest + Penalty</small>
                </div>
            </div>
        </div>


        <!-- Loan Applications -->
        <div class="section-title loan_for_date">Loan Applications (This Month)</div>
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
                    <div class="stat-value total_disbursed"></div>
                    <small class="text-muted">Principal Loan Amount</small>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--warning);">📊</div>
                    <div class="stat-label">Outstanding Balance</div>
                    <div class="stat-value outstanding_balance"></div>
                    <small class="text-muted">Unpaid Principal + Interest</small>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--success);">✓</div>
                    <div class="stat-label">Total Amount Repaid</div>
                    <div class="stat-value verified_amount"></div>
                    <small class="text-muted">All Verified Repayments</small>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: #8b5cf6;">📈</div>
                    <div class="stat-label">Next 30 Days Expected</div>
                    <div class="stat-value expected_amount"></div>
                    <small class="text-muted">Expected Repayments</small>
                </div>
            </div>
        </div>

        <!-- Added Payment Behaviour section with payment status metrics -->
        <div class="section-title">Payment Behaviour</div>
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                     <div class="stat-icon" style="color: var(--primary);">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-label">Active Borrowers</div>
                    <div class="stat-value active_borrower">4</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--success);">✓</div>
                    <div class="stat-label">On-time Payments</div>
                    <div class="stat-value ontime_payment">82%</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--warning);">⏰</div>
                    <div class="stat-label">Late Payments</div>
                    <div class="stat-value late_payment">12%</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: #06b6d4;">📦</div>
                    <div class="stat-label">Partial Payments</div>
                    <div class="stat-value partial_payment">6%</div>
                </div>
            </div>
        </div>

        <!-- Added Borrower Insight section with borrower statistics -->
        <div class="section-title">Borrower Insight (All)</div>
        <div class="row g-4 mb-5" id="insight">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                     <div class="stat-icon" style="color: var(--primary);">
                    <i class="bi bi-people-fill"></i>
                </div>
                    <div class="stat-label">Total Borrowers</div>
                    <div class="stat-value total_borrower"></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: #eded10">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div class="stat-label">Good Payers</div>
                    <div class="stat-value good_payer"></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: #06b6d4;">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <div class="stat-label">With Penalty</div>
                    <div class="stat-value with_penalty"></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color:red">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="stat-label">With Violations</div>
                    <div class="stat-value with_violations"></div>
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
    
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentFilter = 'month'; // default

    // When a date button is clicked
    document.querySelectorAll('.btn-date').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active from all
            document.querySelectorAll('.btn-date').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Update current filter
            currentFilter = this.dataset.filter;

            // Update export links
            document.querySelectorAll('.btn-export').forEach(link => {
                let url = new URL(link.href);
                url.searchParams.set('filter', currentFilter);
                link.href = url.toString();
            });
        });
    });

    // Initialize export links with default filter
    document.querySelectorAll('.btn-export').forEach(link => {
        let url = new URL(link.href);
        url.searchParams.set('filter', currentFilter);
        link.href = url.toString();
    });
});
</script>
@endsection