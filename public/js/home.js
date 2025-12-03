var calendarInitialized = false;
$(document).ready(function() {

     $('.btn-date').off('click').on('click', function() {
        
        $('.btn-date').removeClass('active');

        
        $(this).addClass('active');

        let filter = $(this).text().trim().toLowerCase(); // 'week', 'month', or 'year'
        
        if (filter === 'week'){
            var date_text = 'Loan Applications (This Week)'
        }
        if (filter === 'year'){
            var date_text = 'Loan Applications (This Year)'
        }
         if (filter === 'month'){
            var date_text = 'Loan Applications (This Month)'
        }

        $('.loan_for_date').empty().text(date_text);

        load_dashboard(filter);
    });

    var scrollTimer;  
    const stickyFilter = document.querySelector('.sticky-filters');

    window.addEventListener('scroll', function() {

        clearTimeout(scrollTimer);

        stickyFilter.style.top = '-70px';

        scrollTimer = setTimeout(function() {
            stickyFilter.style.top = '100px';
        }, 150); 
    });

    load_dashboard('month');
    
    function load_dashboard(filter) {
        quick_statistics(filter)
        total_applications(filter);
        scheduled_loans();
        recent_applications();
        recent_payments();
        financial_overview(filter);
        top_borrowers(filter);
        borrower_insight(filter);
        loan_insight(filter);
    }



    function total_applications(filter = 'month') {

        $('#total_applications').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>Loading Data...</div>
            </div>
        `);
        
        let date_text = '';
        if (filter === 'week') date_text = "Week";
        else if (filter === 'month') date_text = "Month";
        else if (filter === 'year') date_text = "Year";
        else date_text = "";
        

        $.ajax({
            url: "/admin/get-total-applications",
            method: "GET",
            data: {filter: filter},
            dataType: "json",
            success: function(response) {
                let html = `
                    <div class="col-12">
                        <div class="stat-card">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center">
                                    <div class="stat-icon" style="color: red; margin-bottom: 0;"> 
                                        <i class="bi bi-clipboard"></i>
                                    </div>
                                    <div class="stat-label">Total Applications</div>
                                    <div class="stat-value">${response.grand_total}</div>
                                </div>
                                <div class="col-md-9">
                                    <div class="status-grid">
                                        <div class="status-item pending">
                                            <div class="status-count">${response.status_totals.pending}</div>
                                            <div class="status-label">Pending</div>
                                        </div>
                                        <div class="status-item approved">
                                            <div class="status-count">${Number(response.status_totals.for_interview) + Number(response.status_totals.transferred)}</div>
                                            <div class="status-label">Approved</div>
                                        </div>
                                        <div class="status-item scheduled">
                                            <div class="status-count">${response.status_totals.scheduled}</div>
                                            <div class="status-label">Scheduled</div>
                                        </div>
                                        <div class="status-item rejected">
                                            <div class="status-count">${response.status_totals.rejected}</div>
                                            <div class="status-label">Rejected</div>
                                        </div>
                                        <div class="status-item cancelled">
                                            <div class="status-count">${response.status_totals.cancelled}</div>
                                            <div class="status-label">Cancelled</div>
                                        </div>
                                        <div class="status-item revision">
                                            <div class="status-count">${response.status_totals.for_revision}</div>
                                            <div class="status-label">For Revision</div>
                                        </div>
                                        <div class="status-item closed">
                                            <div class="status-count">${response.status_totals.closed}</div>
                                            <div class="status-label">Closed</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <canvas id="loanApplicationsChart" height="150"></canvas>
                            </div>
                        </div>
                    </div> 
                `;
                $('#total_applications').html(html);

                // Create chart AFTER canvas exists
                const ctx = document.getElementById('loanApplicationsChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Pending', 'Approved', 'Scheduled', 'Rejected', 'Cancelled', 'For Revision', 'Closed'],
                        datasets: [{
                            label: 'Number of Applications',
                            data: [
                                response.status_totals.pending,
                                response.status_totals.for_interview + response.status_totals.transferred,
                                response.status_totals.scheduled,
                                response.status_totals.rejected,
                                response.status_totals.cancelled,
                                response.status_totals.for_revision,
                                response.status_totals.closed
                            ],
                            backgroundColor: [
                                '#ffc107', // Pending - yellow
                                '#28a745', // Approved - green
                                '#17a2b8', // Scheduled - blue
                                '#dc3545', // Rejected - red
                                '#6c757d', // Cancelled - gray
                                '#fd7e14', // For Revision - orange
                                '#6f42c1'  // Closed - purple
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            title: {
                                display: true,
                                text: `Loan Applications Status (This ${date_text})`,
                                font: { size: 16 }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: { display: true, text: 'Applications Count' }
                            },
                            x: {
                                title: { display: true, text: 'Status' }
                            }
                        }
                    }
                });
            },
            error: function(xhr) {
                console.log("Error:", xhr);
            }
        });
    }


    function scheduled_loans() {
        $('#scheduled_div').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>Loading Data...</div>
            </div>
        `);

        $.ajax({
            url: "/admin/get-scheduled-loans",
            method: "GET",
            dataType: "json",
            success: function(response) {

                if (!response || response.length === 0) {
                    $('#scheduled_div').html(`
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #6c757d;"></i>
                            <p class="mt-3 text-muted">No scheduled loans found</p>
                        </div>
                    `);
                    return;
                }

                let html = `
                    <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                        <p>Upcoming loan payments that require attention</p>
                        <button class="btn-view-more" onclick="window.location.href='#'">
                            <i class="bi bi-arrow-right"></i> View More
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Due Date</th>
                                    <th>Referral</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                response.forEach(function(loan) {
                    // Format the date
                    let dateObj = new Date(loan.tenure_date);
                    let formattedDate = dateObj.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    // Badge color based on referral
                    let badgeClass = 'bg-info';
                    if (loan.referral.toLowerCase().includes('friend')) badgeClass = 'bg-warning text-dark';
                    if (loan.referral.toLowerCase().includes('employee')) badgeClass = 'bg-success';

                    html += `
                        <tr>
                            <td>${loan.full_name}</td>
                            <td>${formattedDate}</td>
                            <td><span class="badge ${badgeClass}">${loan.referral}</span></td>
                        </tr>
                    `;
                });

                html += `
                            </tbody>
                        </table>
                    </div>
                `;

                $('#scheduled_div').html(html);
            },
            error: function(xhr) {
                console.log("Error:", xhr);
            }
        });

    }

    function recent_applications() {
        $('#recent_div').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>Loading Data...</div>
            </div>
        `);

        $.ajax({
            url: "/admin/get-recent-application", 
            method: "GET",
            dataType: "json",
            success: function(response) {

                if (!response || response.length === 0) {
                    $('#recent_div').html(`
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #6c757d;"></i>
                            <p class="mt-3 text-muted">No Recent loans found</p>
                        </div>
                    `);
                    return;
                }

                let html = `
                    <div class="table-header">
                        <p>Latest loan applications submitted</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Borrower</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Referral</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                response.forEach(function(app) {
                // Format the date
                let dateObj = new Date(app.created_at);
                let formattedDate = dateObj.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                let statusText = '';
                let statusClass = 'bg-secondary';
                switch (app.loan_status) {
                    case 1:
                        statusText = 'Pending for Approval';
                        statusClass = 'bg-warning text-dark';
                        break;
                    case 2:
                        statusText = 'For Interview';
                        statusClass = 'bg-primary text-white';
                        break;
                    case 3:
                        statusText = 'For Revision';
                        statusClass = 'bg-orange text-dark';
                        break;
                    case 4:
                        statusText = 'Waiting for disbursement';
                        statusClass = 'bg-info text-white';
                        break;
                    case 5:
                        statusText = 'Transferred and Processed';
                        statusClass = 'bg-success text-white';
                        break;
                    case 6:
                        statusText = 'Rejected';
                        statusClass = 'bg-danger text-white';
                        break;
                    case 7:
                        statusText = 'Closed';
                        statusClass = 'bg-secondary text-white';
                        break;
                    case 8:
                        statusText = 'Scheduled';
                        statusClass = 'bg-info text-white';
                        break;
                    case 9:
                        statusText = 'Cancelled';
                        statusClass = 'bg-dark text-white';
                        break;
                    default:
                        statusText = 'Unknown';
                        statusClass = 'bg-secondary text-white';
                }

                // Referral badge color
                let referralClass = 'bg-info';
                if (app.referral && app.referral.toLowerCase().includes('friend')) referralClass = 'bg-warning text-dark';
                else if (app.referral && app.referral.toLowerCase().includes('employee')) referralClass = 'bg-success';

                // Format amount
                let formattedAmount = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(app.loan_amount);

                html += `
                    <tr>
                        <td>${formattedDate}</td>
                        <td>${app.full_name}</td>
                        <td>${formattedAmount}</td>
                        <td><span class="badge ${statusClass}">${statusText}</span></td>
                        <td><span class="badge ${referralClass}">${app.referral}</span></td>
                    </tr>
                `;
            });


                html += `
                            </tbody>
                        </table>
                    </div>
                `;

                $('#recent_div').html(html);
            },
            error: function(xhr) {
                console.log("Error:", xhr);
            }
        });
    }

    function recent_payments() {
        $('#recentPayments_div').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>Loading Data...</div>
            </div>
        `);

        $.ajax({
            url: "/admin/get-recent-payments",
            method: "GET",
            dataType: "json",
            success: function(response) {


                if (!response || response.length === 0) {
                    $('#recentPayments_div').html(`
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #6c757d;"></i>
                            <p class="mt-3 text-muted">No payments found</p>
                        </div>
                    `);
                    return;
                }

                let rows = "";

                response.forEach(item => {
                    let badgeClass = "bg-secondary";

                    switch(item.status) {
                        case "For Verification": badgeClass = "bg-info text-dark"; break;
                        case "For Correction":   badgeClass = "bg-warning text-dark"; break;
                        case "Verified":         badgeClass = "bg-success"; break;
                        case "Rejected":         badgeClass = "bg-danger"; break;
                        case "For Revision":     badgeClass = "bg-warning text-dark"; break;
                        case "For Appeal":       badgeClass = "bg-primary text-light"; break;
                    }


                    rows += `
                        <tr>
                            <td>${item.created_at}</td>
                            <td>${item.reference_code ?? ''}</td>
                            <td>${item.full_name}</td>
                            <td>${item.coverage}</td>
                            <td>₱${Number(item.amount_sent).toLocaleString()}</td>
                            <td><span class="badge ${badgeClass}">${item.status}</span></td>
                        </tr>
                    `;
                });

                let html = `
                    <div class="table-header">
                        <h3>Recent Payments</h3>
                        <p>Latest loan payment transactions</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Reference Number</th>
                                    <th>Borrower Name</th>
                                    <th>Payment Type</th>
                                    <th>Amount Paid</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${rows}
                            </tbody>
                        </table>
                    </div>
                `;

                $('#recentPayments_div').html(html);
            },
            error: function(xhr) {
                console.log("Error:", xhr);
            }
        });
    }

    function financial_overview(filter = 'month') {
        $('.interest_earned, .penalty_earned, .revenue_earned').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>Loading Data...</div>
            </div>
        `);

        $.ajax({
            url: "/admin/get-financial-overview",
            method: "GET",
            data: {filter:filter},
            dataType: "json",
            success: function(response) {
                $('.interest_earned').html(response.interest_earned);
                $('.penalty_earned').html(response.penalties_collected);
                $('.revenue_earned').html(response.revenue);
            
            },
            error: function(xhr) {
                console.log("Error:", xhr);
            }
        });
    }

    function top_borrowers(filter = 'month') {
        $('#top_borrowers').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>Loading Data...</div>
            </div>
        `);

        $.ajax({
            url: "/admin/get-top-borrowers",
            method: "GET",
            data: { filter: filter },
            dataType: "json",
            success: function(response) {

                if (!response || response.length === 0) {
                    $('#top_borrowers').html(`
                        <div class="text-center py-5 w-100">
                            <i class="bi bi-person-x" style="font-size: 3rem; color: #6c757d;"></i>
                            <p class="mt-3 text-muted">No top borrowers found</p>
                        </div>
                    `);
                    return;
                }


                let html = '';

                response.forEach(item => {
                    // Get initials
                    let names = item.full_name.split(' ');
                    let initials = '';
                    if (names.length >= 2) {
                        initials = names[0].charAt(0).toUpperCase() + names[1].charAt(0).toUpperCase();
                    } else if (names.length === 1) {
                        initials = names[0].charAt(0).toUpperCase();
                    }

                    html += `
                    <div class="col-md-2 col-lg-2">
                        <div class="stat-card">
                            <div class="stat-icon" style="background-color: #0056b3; color: #fff; border-radius: 50%; width: 60px; height: 60px; display:flex; align-items:center; justify-content:center; font-weight:bold;">
                                ${initials}
                            </div>
                            <div class="stat-label">Top Borrower</div>
                            <div class="stat-value" style="font-size: 1.25rem;">${item.full_name}</div>
                            <div style="font-size: 1.5rem; font-weight: 700; color: var(--success); margin-top: 0.5rem;">₱${parseFloat(item.loan_amount).toLocaleString()}</div>
                        </div>
                    </div>`;
                });


                $('#top_borrowers').html(html);
            },
            error: function(xhr) {
                console.log("Error:", xhr);
            }
        });
    }

    function borrower_insight(filter = 'month') {

        $('.total_borrower, .active_borrower, .with_violations, .good_payer').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>Loading Data...</div>
            </div>
        `);

        $.ajax({
            url: "/admin/get-insight",
            method: "GET",
            data: { filter: filter },
            dataType: "json",
            success: function(response) {
                
                let html = '';

                $('.total_borrower').html(response.total_borrowers);
                $('.active_borrower').html(response.active_borrowers);
                $('.with_violations').html(response.violations);
                $('.good_payer').html(response.good_payer);
            },
            error: function(xhr) {
                console.log("Error:", xhr);
            }
        });
    }

    function loan_insight(filter = 'month') {

        $('.total_disbursed, .outstanding_balance, .verified_amount, .expected_amount').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>Loading Data...</div>
            </div>
        `);

        $.ajax({
            url: "/admin/get-loan-insight",
            method: "GET",
            data: { filter: filter },
            dataType: "json",
            success: function(response) {
                
                let html = '';

            $('.total_disbursed').html('₱ ' + Number(response.total_disburse).toLocaleString());
            $('.outstanding_balance').html('₱ ' + Number(response.total_balance).toLocaleString());
            $('.verified_amount').html('₱ ' + Number(response.verified_payments).toLocaleString());
            $('.expected_amount').html('₱ ' + Number(response.upcoming_balance).toLocaleString());
            },
            error: function(xhr) {
                console.log("Error:", xhr);
            }
        });
    }

    function quick_statistics(filter = 'month') {

        $('.quick__money, .quick_balance, .quick_tithes, .quick_misc').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>Loading Data...</div>
            </div>
        `);

        $.ajax({
            url: "/admin/get-statistics",
            method: "GET",
            data: { filter: filter },
            dataType: "json",
            success: function(response) {
                
                let html = '';

            $('.quick__money').html('₱ ' + Number(response.available_money).toLocaleString());
            $('.quick_balance').html('₱ ' + Number(response.balance).toLocaleString());
            $('.quick_tithes').html('₱ ' + Number(response.tithes).toLocaleString());
            $('.quick_misc').html('₱ ' + Number(response.misc).toLocaleString());
            },
            error: function(xhr) {
                console.log("Error:", xhr);
            }
        });
    }

    $(document).ready(function() {
        $('#calendar-float-icon').on('click', function() {
            $('#calendar-popup').fadeToggle(200);
            $('#calendar-overlay').fadeToggle(200);
            $('.sticky-filters').fadeToggle(200);

            if (!calendarInitialized) {
                ensureFullCalendarLoaded(initFullCalendar);
                calendarInitialized = true; // prevent re-initialization
            }
        });
    });

    function ensureFullCalendarLoaded(callback) {
        if (window.FullCalendar) {
            callback();
            return;
        }
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js';
        script.onload = () => {
            console.log('FullCalendar loaded.');
            callback();
        };
        script.onerror = () => console.error('Failed to load FullCalendar.');
        document.head.appendChild(script);
    }

    function initFullCalendar() {
        const calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: '/admin/get-calendar',
                    method: 'GET',
                    data: { start: fetchInfo.startStr, end: fetchInfo.endStr },
                    dataType: 'json',
                    success: function(events) { successCallback(events); },
                    error: function(xhr) { failureCallback(xhr); }
                });
            },
            editable: true,
            selectable: true,
            eventContent: function(arg) {
                // Split title into name and amount
                const parts = arg.event.title.split('₱'); // split by currency symbol
                const name = parts[0].trim(); // "Alejandro Bermudo"
                const amount = parts[1] ? '₱' + parts[1].trim() : '';

                // Get initials from name only
                let initials = name
                    .split(' ')
                    .map(word => word.charAt(0).toUpperCase())
                    .join('');

                return { 
                    html: `
                            <div class="fc-event-left">
                                <div class="fc-event-initials">${initials}</div>
                            </div>
                            <div class="fc-event-right">
                                <div class="fc-event-name">${name}</div>
                                <div class="fc-event-amount">${amount}</div>
                            </div>
                    ` 
                };

            }

        });

        calendar.render();
    }

});