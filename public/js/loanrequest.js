function parseDateInput(dateValue) {
    if (!dateValue) {
        return new Date(NaN);
    }

    const value = String(dateValue).trim();
    const ymdMatch = value.match(/^(\d{4})-(\d{2})-(\d{2})$/);
    if (ymdMatch) {
        return new Date(Number(ymdMatch[1]), Number(ymdMatch[2]) - 1, Number(ymdMatch[3]));
    }

    return new Date(value);
}

function getTodayInPhilippines() {
    const phNow = new Date(new Date().toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
    phNow.setHours(0, 0, 0, 0);
    return phNow;
}

function formatDateInPhilippines(date) {
    return new Intl.DateTimeFormat('en-US', {
        timeZone: 'Asia/Manila',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(date);
}

$(document).ready(function () {
    let loanData = [];
    const itemsPerPage = 10;
    let currentPage = 1;
    let filteredData = [];
    const loanRequestBasePath = '/admin/loan-request';

    function renderStatusBadge(status, status_name) {
        const classMap = {
            '1': 'secondary',
            '2': 'primary',
            '3': 'warning',
            '4': 'info',
            '5': 'success',
            '6': 'danger'
        };
        const badgeClass = classMap[status] || 'dark';
        return `<span class="badge bg-${badgeClass}">${status_name}</span>`;
    }

    window.renderTable = function() {
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const currentItems = filteredData.slice(start, end);

        const $tbody = $('#loanBody');
        $tbody.empty();

        if (currentItems.length === 0) {
            $('#emptyState').show();
        } else {
            $('#emptyState').hide();
            $.each(currentItems, function (_, item) {
                let scheduledDateObj = parseDateInput(item.scheduled_date);
                let twoDaysBefore = new Date(scheduledDateObj);
                twoDaysBefore.setDate(scheduledDateObj.getDate() - 2);

                let today = getTodayInPhilippines();

                let statusTd = '';

                if (item.loan_status == 4 && item.loan_type === 'Scheduled' && today < twoDaysBefore) {
                    statusTd = `<span class="badge bg-secondary">Scheduled</span>`;
                } else {
                    statusTd = renderStatusBadge(item.loan_status, item.loan_status_name);
                }

                const row = `
                                <tr data-loan_id="${item.id}" data-loan-status="${item.loan_status}" data-loan_type="${item.loan_type}" data-scheduled_date="${item.scheduled_date}">
                                    <td>${'LN-' + String(item.id).padStart(5, '0')}</td>
                                    <td>${item.loan_applicant}</td>
                                    <td>${item.loan_type || '-'}</td>
                                    <td>₱${parseFloat(item.loan_amount).toLocaleString()}</td>
                                    <td>${item.loan_tenure} months</td>
                                    <td>${item.interest_rate * 100}%</td>
                                    <td>${item.purpose_of_loan}</td>
                                    <td>${item.created_at}</td>
                                    <td>${item.referral || 'None'}</td>
                                    <td>${statusTd}</td>
                                </tr>
                            `;
                $tbody.append(row);
            });

        }

        $('#totalData').text(filteredData.length);
        $('#counter').text(`Showing ${currentItems.length} of ${filteredData.length}`);
        renderPagination();
    }

    function renderPagination() {
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        const $pagination = $('#pagination');
        const $paginationNav = $pagination.closest('nav');
        $pagination.empty();

        if (totalPages <= 1) {
            $paginationNav.hide();
            return;
        }

        $paginationNav.show();

        for (let i = 1; i <= totalPages; i++) {
            const pageItem = `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <button class="page-link page-btn" data-page="${i}">${i}</button>
                </li>
            `;
            $pagination.append(pageItem);
        }
    }

    function fetchLoanRequestData(loan_status = '') {
        $.ajax({
            url: '/admin/loan-request/data',
            method: 'GET',
            data: { loan_status: loan_status },
            dataType: 'json',
            success: function (data) {
                loanData = data;
                filteredData = [...loanData];
                currentPage = 1;
                window.renderTable();
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data:', error);
                $('#emptyState').show();
            }
        });
    }

    function initLoanRequestList() {
        if (!$('#loanBody').length) {
            return;
        }

        const $links = $('#loanSubNav a').filter(function () {
            return !$(this).is('[hidden]');
        });

        let $activeLink = $links.filter('.active').first();
        if (!$activeLink.length) {
            $activeLink = $links.first();
        }

        if (!$activeLink.length) {
            fetchLoanRequestData('');
            return;
        }

        $('#loanSubNav a').removeClass('active');
        $activeLink.addClass('active');
        fetchLoanRequestData($activeLink.data('loan_status') || '');
    }

    window.initLoanRequestList = initLoanRequestList;

    $(document).on('click', '.page-btn', function () {
        currentPage = parseInt($(this).data('page'));
        window.renderTable();
    });

    $(document).off('input', '#search').on('input', '#search', function () {
        const query = $(this).val().toLowerCase();
        filteredData = loanData.filter(item =>
            ('LN-' + String(item.id).padStart(5, '0')).toLowerCase().includes(query) ||
            item.loan_applicant.toLowerCase().includes(query) ||
            (item.referral || '').toLowerCase().includes(query)
        );
        currentPage = 1;
        window.renderTable();
    });

    $(document).off('click', '#loanSubNav a').on('click', '#loanSubNav a', function (e) {
        e.preventDefault();
        $('#loanSubNav a').removeClass('active');
        $(this).addClass('active');
        fetchLoanRequestData($(this).data('loan_status') || '');
    });

    initLoanRequestList();

    $(document).on('admin:content-loaded', function (event, url) {
        if (typeof url === 'string' && !url.startsWith(loanRequestBasePath)) {
            return;
        }
        initLoanRequestList();
    });
});

$(document).ready(function () {


    $(document).on('click', '#loanBody tr', function () {

        let loan_status = $(this).attr('data-loan-status');

        $('.navbar-custom').css('z-index', 0);
        let loan_id = $(this).attr('data-loan_id');
        let complete_loan_id = 'LN-'+String(loan_id).padStart(5, '0');

        let loan_type = $(this).attr('data-loan_type');
        let scheduled_date = $(this).attr('data-scheduled_date');
        let scheduledDateObj = parseDateInput(scheduled_date);

        // Kunin ang "2 days before" ng scheduled date
        let twoDaysBefore = new Date(scheduledDateObj);
        twoDaysBefore.setDate(scheduledDateObj.getDate() - 2);

        // Today
        let today = getTodayInPhilippines();

        if (parseInt(loan_status) == 4 && loan_type == 'Scheduled' && today < twoDaysBefore) {
            Swal.fire({
                icon: 'info',
                title: 'Transfer Not Available Yet',
                text: 'Transfer money will only be available 2 days before the scheduled date (' + formatDateInPhilippines(scheduledDateObj) + ').',
                confirmButtonText: 'OK'
            });
        } else if (parseInt(loan_status) == 4 || (loan_type == 'Scheduled' && today > twoDaysBefore)) {
            $.ajax({
                url: '/admin/loan-request/get-bank-details',
                method: 'POST',
                data: {
                    "loan_id": loan_id
                },
                dataType: 'json',
                success: function (ress) {

                    let rqrCode = ress.upload_qr_code_img;

                    let rqrContent = rqrCode && rqrCode.trim() !== ""
                        ? `
                            <div>
                                <div class="p-1 border rounded-3 bg-light d-inline-block shadow-sm">
                                    <a href="${rqrCode}" data-lightbox="bank-qr-${loan_id}" data-title="Bank QR Code">
                                        <img src="${rqrCode}" alt="Bank QR Code" width="160" height="160" style="cursor: zoom-in;">
                                    </a>
                                </div>
                            </div>
                        `
                        : ``;
                    let content = ` 
                        <!-- Tabs -->
                        <ul class="nav nav-tabs" id="simpleTabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#bankTab" type="button">
                                    Bank Details
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transferTab" type="button">
                                    Transfer Money
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activityTransferTab" type="button">
                                    Activity Log
                                </button>
                            </li>
                        </ul>
                        <!-- Tab Content -->
                        <div class="tab-content mt-4">
                            <!-- Bank Tab -->
                            <div class="tab-pane fade show active" id="bankTab" role="tabpanel">
                                <div class="card shadow-sm border-0 rounded-4 p-5 text-center">
                                    <h4 class="fw-bold text-primary mb-4"><i class="ri-bank-line"></i> Bank Details</h4>
                                    <div class="justify-content-center gap-5 mb-4 flex-wrap">
                                        <div>
                                            <p class="fs-5 mb-0">${ress.bank_name}</p>
                                            <label class="form-label fw-semibold d-block text-muted">Bank Name</label>
                                        </div>
                                    </div>
                                    ${rqrContent}
                                    <div class="justify-content-center gap-5 mt-4 flex-wrap">
                                        <div>
                                            <p class="fs-5 mb-0">${ress.account_number}</p>
                                            <label class="form-label fw-semibold d-block text-muted">Account Number</label>
                                        </div>
                                        <div>
                                            <p class="fs-5 mb-0">₱ <span class="disburse_amount">${ress.loan_amount}</span></p>
                                            <label class="form-label fw-semibold d-block text-muted">Amount</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Transfer Tab -->
                            <div class="tab-pane fade" id="transferTab" role="tabpanel">
                            <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="fw-bold text-success mb-0">Transfer Money</h4>
                                </div>

                                <form id="transferForm" enctype="multipart/form-data">
                                <!-- Image file capture for screenshot -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Upload Screenshot</label>
                                    <input type="file" class="form-control" id="screenshot" name="screenshot" accept="image/*" capture="environment" required>
                                    <div class="form-text">Upload a proof-of-transfer.</div>
                                </div>

                                <!-- Reference number -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Reference Number</label>
                                    <input type="text" class="form-control" id="refNumber" name="ref_number" placeholder="Enter reference number" required>
                                </div>

                                <!-- Date field (autopopulate after clicking Transfer Money) -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Transfer Date</label>
                                    <input type="date" class="form-control" id="transferDate" name="transfer_date" readonly>
                                    
                                </div>

                                <!-- Processed by (autopopulate after clicking Transfer Money) -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Processed By</label>
                                    <input type="text" class="form-control" id="processedBy" name="processed_by" value="${ress.processed_by}" readonly>
                                    
                                </div>

                                <!-- Monthly due date: autopopulate; allow custom with calendar picker -->
                                <div class="mb-2">
                                <label class="form-label fw-semibold">Monthly Due Date</label>
                                <input 
                                    type="date" 
                                    class="form-control" 
                                    id="monthlyDueDate" 
                                    name="monthly_due_date" 
                                    onfocus="this.showPicker()"
                                >
                                </div>

                                <!-- Remarks -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Optional remarks..."></textarea>
                                </div>

                                <div class="d-flex justify-content-center mt-4">
                                    <button type="button" class="btn btn-success px-4 transfer_money" data-bank_amount="${ress.data.money}" data-loan_id="${loan_id}">Transfer Money<i class="ri-arrow-right-line ms-2"></i></button>
                                </div>
                                </form>
                            </div>
                            </div>

                            <div class="tab-pane fade" id="activityTransferTab" role="tabpanel">
                                <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5">
                                    <h4 class="fw-bold text-primary mb-3">Activity Log</h4>
                                    <ul id="transferActivityLog" class="list-group">
                                        <li class="list-group-item text-muted">Loading activity logs...</li>
                                    </ul>
                                </div>
                            </div>
                        </div>`;
                    
                    // Show confirm dialog with content
                    $.confirm({
                        title: complete_loan_id,
                        columnClass: 'large',
                        content: content,
                        buttons: {
                            cancel: {
                                text: 'Close',
                                btnClass: 'btn-secondary close_transfer'
                            }
                        },
                        onContentReady: function () {
                            // Helper: format date to YYYY-MM-DD
                            function toYMD(date) {
                                const y = date.getFullYear();
                                const m = String(date.getMonth() + 1).padStart(2, '0');
                                const d = String(date.getDate()).padStart(2, '0');
                                return `${y}-${m}-${d}`;
                            }

                            // Helper: get next month same day
                            // function nextMonthSameDay(base) {
                            //     const y = base.getFullYear();
                            //     const m = base.getMonth(); // 0-11
                            //     const d = base.getDate();

                            //     // Tentative next month
                            //     const next = new Date(y, m + 1, 1);
                            //     // Days in target month
                            //     const daysInTarget = new Date(next.getFullYear(), next.getMonth() + 1, 0).getDate();
                            //     const day = Math.min(d, daysInTarget);
                            //     next.setDate(day);
                            //     return next;
                            // }

                            function nextMonthPlusOneDay(base) {
                                const y = base.getFullYear();
                                const m = base.getMonth(); // 0-11
                                const d = base.getDate();

                                // Tentative next month
                                const next = new Date(y, m + 1, 1);

                                // Days in target month
                                const daysInTarget = new Date(next.getFullYear(), next.getMonth() + 1, 0).getDate();
                                const day = Math.min(d, daysInTarget);

                                next.setDate(day + 1); // dito yung dagdag na +1
                                return next;
                            }

                            function nextMonthMinusOneDay(base) {
                                const y = base.getFullYear();
                                const m = base.getMonth(); // 0-11
                                const d = base.getDate();

                                // Tentative next month
                                const next = new Date(y, m + 1, 1);

                                // Days in target month
                                const daysInTarget = new Date(next.getFullYear(), next.getMonth() + 1, 0).getDate();
                                const day = Math.min(d, daysInTarget);

                                next.setDate(day - 1); // dito yung bawas na -1
                                return next;
                            }
                            const $modalContent = this.$content;
                            // Autopopulate Monthly Due Date when modal content is ready
                            const now = new Date();
                            const due = nextMonthMinusOneDay(now);
                            $modalContent.find('#monthlyDueDate').val(toYMD(due));
                            $modalContent.find('#transferDate').val(toYMD(now));

                            const renderTransferActivityLogs = function () {
                                const $logList = $modalContent.find('#transferActivityLog');
                                if ($logList.attr('data-loaded') === '1') {
                                    return;
                                }

                                const logs = Array.isArray(ress.logs) ? ress.logs : [];
                                let html = '';

                                if (!logs.length) {
                                    html = '<li class="list-group-item text-muted">No activity logs found.</li>';
                                } else {
                                    logs.forEach(log => {
                                        html += `<li class="list-group-item">${log.created_at}: [${log.user_name}] ${log.description}</li>`;
                                    });
                                }

                                $logList.html(html).attr('data-loaded', '1');
                            };

                            $modalContent.find('button[data-bs-target="#activityTransferTab"]')
                                .off('click.transferLog')
                                .on('click.transferLog', function () {
                                    renderTransferActivityLogs();
                                });
                        },


                    });
                }
            });
        } else {
            $('#loanStatusCustom').val('Pending');
            $('#customLoanPopup').removeClass('d-none');
            let firstTabEl = document.querySelector('#loanTabs button:first-child');
            let firstTab = new bootstrap.Tab(firstTabEl);
            firstTab.show();

            $('#customLoanPopup').attr('data-loan_id', loan_id);
            $('.alr_loan_id').text(complete_loan_id);

            $.ajax({
                url: '/admin/loan-request/get-loan-data',
                method: 'POST',
                data: {
                    "loan_id": loan_id
                },
                dataType: 'json',
                success: function (r) {

                    let loan = r.data;
                    let logs = r.logs;
                    let admins = r.admins;
                    let loan_status = r.loan_status;
                    let loan_stat_access = r.loan_request_access[0];

                    let approvedAdmins = r.approved_by;    
                    let disapprovedAdmins = r.disapproved_by; 
                    let grade = r.grade; 

                    if (parseInt(loan.loan_status) > 1) {
                        $('#approveBtn').attr('hidden', true);
                    } else {
                        $('#approveBtn').removeAttr('hidden');
                    }

                    $('#approvalCounter').text(`${approvedAdmins.length}/3`);
                    $('.form-check-input.admin-approval').each(function () {
                        let adminId = $(this).val();
                        let $status = $(this).closest('.form-check').find('.status_approval');

                        if (approvedAdmins.includes(adminId)) {
                            $(this).prop('checked', true);
                            $status.text('Approved').removeClass().addClass('text-success status_approval');
                        }
                        else if (disapprovedAdmins.includes(adminId)) {
                            $(this).prop('checked', false);
                            $status.text('Rejected').removeClass().addClass('text-danger status_approval');
                        }
                        else {
                            $(this).prop('checked', false);
                            $status.text('Pending').removeClass().addClass('text-secondary status_approval');
                        }
                    });

                    let loanStatusDropdowns = "";
                    $.each(loan_status, function (index, status) {
                        let ls_hidden = "";
                        let ls_is_disabled = "";
                        if (status.id == 1) {
                            ls_hidden = loan_stat_access.pending == 1 ? '' : 'hidden';

                        } else if (status.id == 2) {
                            ls_hidden = loan_stat_access.for_interview == 1 ? '' : 'hidden';

                        } else if (status.id == 3) {
                            ls_hidden = loan_stat_access.for_revision == 1 ? '' : 'hidden';
                            ls_is_disabled = "disabled";
                        } else if (status.id == 4) {
                            ls_hidden = loan_stat_access.waiting == 1 ? '' : 'hidden';

                        } else if (status.id == 5) {
                            ls_hidden = loan_stat_access.transferred_and_processed == 1 ? '' : 'hidden';

                        } else if (status.id == 6) {
                            ls_hidden = loan_stat_access.rejected == 1 ? '' : 'hidden';

                        } else if (status.id == 7) {
                            ls_hidden = loan_stat_access.closed == 1 ? '' : 'hidden';

                        }
                        if (status.id == 5) {
                            var is_hidden_opt = 'hidden';

                        }

                        loanStatusDropdowns += `<option value="${status.id}" ${loan.loan_status == status.id ? "selected" : ""} ${ls_hidden} ${ls_is_disabled} ${is_hidden_opt}>${status.loan_status}</option>`;
                    });
                    let loan_detail_content = `<div class="card border-0 shadow-sm mt-2">
                                                    <div class="card-body">
                                                        <table class="table table-bordered table-sm align-middle">
                                                            <tr>
                                                                <th>Requested Amount</th>
                                                                <td>
                                                                    PHP <span class="changeEditLR">${loan.loan_amount && Math.floor(loan.loan_amount).toLocaleString('en-US')}</span>
                                                                    <i class="ri-pencil-fill text-danger ms-2 rejectData" style="cursor:pointer;" title="Reject this field" id="rejectAmount"></i>
                                                                </td>
                                                            </tr>
                                                            <tr><th>Loan Term</th><td>${parseInt(loan.loan_tenure)} months</td></tr>
                                                            <tr><th>Interest</th><td>${loan.interest_rate * 100}%</td></tr>
                                                            ${loan.loan_type == 'Scheduled' ? `<tr><th>Requested Date</th><td>${loan.scheduled_date}</td></tr>` : ''}
                                                            <tr><th>Purpose</th><td>${loan.purpose_of_loan}</td></tr>
                                                            <tr><th>Created At</th><td>${loan.created_at}</td></tr>
                                                            <tr><th>Last Updated</th><td>${loan.updated_at}</td></tr>
                                                            <tr><th>Referral</th><td>${loan.referral ?? 'N/A'}</td></tr>
                                                        </table>
                                                        <div class="mt-3 d-flex align-items-center" style="max-width:320px;">
                                                            <select id="loan_status_admin" 
                                                                    name="loan_status" 
                                                                    class="form-select flex-grow-1" disabled>
                                                                ${loanStatusDropdowns}
                                                            </select>
                                                            <i class="ri-pencil-fill text-success ms-2 updateStatus" 
                                                                style="cursor:pointer;" 
                                                                title="Update Loan Status" 
                                                            id="updateStatus" data-original_status="${loan.loan_status}"></i>
                                                        </div>
                                                    </div>
                                                </div>`;

                    $('.alr_loan_details_content').empty().append(loan_detail_content);

                    let loan_documents_content = `<div class="mt-3">
                                                        <p class="text-center fw-bold mb-4">${loan.loan_applicant}'s Documents.</p>
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
                                                                <a href="${loan.payslip_img}" data-lightbox="loan-docs-${loan.id}" data-title="Proof of Income">
                                                                    <img src="${loan.payslip_img}" alt="Proof of Income" class="img-fluid border rounded doc-preview-image" style="max-height: 300px; cursor: zoom-in;">
                                                                </a>
                                                                <div>
                                                                    <button 
                                                                        type="button" 
                                                                        class="btn btn-sm btn-outline-danger rejectData ms-2 mt-4" 
                                                                        id="rejectProofofIncome" 
                                                                        title="Reject this field">
                                                                        <i class="ri-pencil-fill"></i> Reject
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="tab-pane fade" id="qrTab" role="tabpanel">
                                                                
                                                                <a href="${loan.upload_qr_code_img}" data-lightbox="loan-docs-${loan.id}" data-title="QR Code">
                                                                    <img src="${loan.upload_qr_code_img}" alt="QR Code" class="img-fluid border rounded doc-preview-image" style="max-height: 300px; cursor: zoom-in;">
                                                                </a>
                                                                <div>
                                                                    <button 
                                                                        type="button" 
                                                                        class="btn btn-sm btn-outline-danger rejectData ms-2 mt-4" 
                                                                        id="rejectQRcode" 
                                                                        title="Reject this field">
                                                                        <i class="ri-pencil-fill"></i> Reject
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="idTab" role="tabpanel">
                                                                <a href="${loan.government_id_img}" data-lightbox="loan-docs-${loan.id}" data-title="Government ID">
                                                                    <img src="${loan.government_id_img}" alt="Government ID" class="img-fluid border rounded doc-preview-image" style="max-height: 300px; cursor: zoom-in;">
                                                                </a>
                                                                <div>
                                                                    <button 
                                                                        type="button" 
                                                                        class="btn btn-sm btn-outline-danger rejectData ms-2 mt-4" 
                                                                        id="rejectGovernmentID" 
                                                                        title="Reject this field">
                                                                        <i class="ri-pencil-fill"></i> Reject
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="supportTab" role="tabpanel">
                                                                <a href="${loan.billing_statement_img}" data-lightbox="loan-docs-${loan.id}" data-title="Supporting Documents">
                                                                    <img src="${loan.billing_statement_img}" alt="Supporting Documents" class="img-fluid border rounded doc-preview-image" style="max-height: 300px; cursor: zoom-in;">
                                                                </a>
                                                                <div>
                                                                    <button 
                                                                        type="button" 
                                                                        class="btn btn-sm btn-outline-danger rejectData ms-2 mt-4" 
                                                                        id="rejectBilling" 
                                                                        title="Reject this field">
                                                                        <i class="ri-pencil-fill"></i> Reject
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>`;

                    $('#documentsTab').empty().append(loan_documents_content);

                    let loan_history_content = `<div class="card border-0 shadow-sm mt-2">
                                                    <div class="card-body">
                                                        <h5 class="mb-3"><i class="bi bi-journal-text me-2"></i>Loan History</h5>
                                                        <table class="table table-bordered table-sm align-middle">
                                                            <tr><th>Total Loans Taken</th><td>${loan.loan_taken}</td></tr>
                                                            <tr><th>Total Loan Amount</th><td>PHP ${loan.total_loan_amount}</td></tr>
                                                            <tr><th>Date of First Loan</th><td>${loan.first_loan_date}</td></tr>
                                                            <tr><th>Last Loan Date</th><td>${loan.last_loan_date}</td></tr>
                                                            <tr><th>Referral</th><td>${loan.referral ?? 'N/A'}</td></tr>
                                                            <tr><th>Violations</th><td>${grade.violations} <small class="text-muted d-block">3 consecutive months of no payment = 1 violation</small></td></tr>
                                                            <tr><th>Penalties</th><td>${grade.penalties} <small class="text-muted d-block">Every 7 days after monthly due date = 1 penalty</small></td></tr>
                                                            <tr><th>Remarks</th><td><span class="badge bg-success">Excellent</span></td></tr>
                                                        </table>
                                                    </div>
                                                </div>`;

                    $('#historyTab').empty().append(loan_history_content);
                        
                    let activity_logs_content = `<div class="card border-0 shadow-sm mt-2">
                                                    <div class="card-body p-0">
                                                        <div id="activityLogCustom" class="table-responsive">
                                                            <table class="table table-bordered table-sm align-middle mb-0">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th style="width:24%;">Date</th>
                                                                        <th style="width:24%;">By</th>
                                                                        <th>Activity</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>`;
                    if (logs.length) {
                        logs.forEach(log => {
                            activity_logs_content += `<tr><td>${log.created_at}</td><td>${log.user_name}</td><td>${log.description}</td></tr>`;
                        });
                    } else {
                        activity_logs_content += `<tr><td colspan="3" class="text-center text-muted">No activity logs found.</td></tr>`;
                    }
                    activity_logs_content += `                 </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>`;

                    $('#activityTab').empty().append(activity_logs_content);

                },
                error: function (xhr, status, error) {
                    console.error('Error fetching data:', error);
                    $('#emptyState').show();
                }
            });
        }

    });


    $(document).on('click', '#closePopup', function () {
        $('#customLoanPopup').addClass('d-none');
        $('.navbar-custom').css('z-index', 2);
    });
});

// $(document).on('keyup', '#suggestedAmount', function () {
//     let val = $(this).val().replace(/,/g, ''); // tanggalin muna lahat ng comma
//     if (val !== "" && !isNaN(val)) {
//         $(this).val(val.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
//     }
// });

$(document).on('click', '#approveBtn', function () {

    let loan_id = $('#customLoanPopup').attr('data-loan_id');
    let admin_id = $(this).attr('admin_id');

    $.confirm({
        title: 'Confirmation',
        content: 'Please confirm whether you want to approve or reject this application.',
        type: 'green',
        boxWidth: '400px',
        useBootstrap: false,
        closeIcon: true,
        buttons: {
            Reject: {
                text: 'Reject',
                btnClass: 'btn-danger',
                action: function () {
                    $.confirm({
                        title: 'Confirm Rejection',
                        content: 'Are you sure you want to reject this loan request?',
                        type: 'red',
                        buttons: {
                            confirm: {
                                text: 'Yes, Reject',
                                btnClass: 'btn-danger',
                                action: function () {

                                    $.confirm({
                                        title: 'Confirm Rejection',
                                        content: '' +
                                            '<form action="" class="formName">' +
                                            '<div class="form-group">' +
                                            '<label>Enter your comment</label>' +
                                            '<textarea class="comment form-control" style="max-width: 100% !important;" rows="4" required></textarea>' +
                                            '</div>' +
                                            '</form>',
                                        type: 'red',
                                        buttons: {
                                            confirm: {
                                                text: 'Proceed',
                                                btnClass: 'btn-success',
                                                action: function () {
                                                    let comment = this.$content.find('.comment').val();
                                                    if (!comment) {
                                                        $.alert('You need to write a comment before proceeding.');
                                                        return false; // prevent closing
                                                    }
                                                    
                                                     $('#admin' + admin_id)
                                                        .prop('checked', false)
                                                        .trigger('change'); 

                                                    $('#admin' + admin_id)
                                                        .closest('.form-check')
                                                        .find('.status_approval')
                                                        .text('rejected')
                                                        .removeClass('text-success')
                                                        .addClass('text-danger');

                                                    $.ajax({
                                                        url: '/admin/loan-request/loan/' + loan_id + '/reject',
                                                        method: 'POST',
                                                        data: {
                                                            admin_id: admin_id,
                                                            comment: comment,
                                                        },
                                                        success: function(response) {
                                                            $('#closePopup').click();
                                                            $('.lrFirstReload').click();
                                                            $.alert({
                                                                title: 'Success',
                                                                content: 'The loan request has been rejected successfully.',
                                                                type: 'green'
                                                            });
                                                        }
                                                    });
                                                    
                                                }
                                            },
                                            cancel: {
                                                text: 'Cancel',
                                                btnClass: 'btn-secondary'
                                            }
                                        }
                                    });
                                }
                            },
                            cancel: {
                                text: 'Cancel',
                                btnClass: 'btn-secondary'
                            }
                        }
                    });
                }
            },
            Approve: {
                text: 'Approve',
                btnClass: 'btn-success',
                action: function () {
                    $.confirm({
                        title: 'Confirm Approval',
                        content: 'Are you sure you want to approve this loan?',
                        buttons: {
                            Yes: {
                                btnClass: 'btn-success',
                                action: function () {
                                    $('#admin' + admin_id)
                                        .prop('checked', true)
                                        .trigger('change');

                                    $('#admin' + admin_id)
                                        .closest('.form-check')
                                        .find('.status_approval')
                                        .text('approved')
                                        .removeClass('text-danger')
                                        .addClass('text-success');

                                    $.ajax({
                                        url: '/admin/loan-request/loan/' + loan_id + '/approve',
                                        method: 'POST',
                                        data: {
                                            admin_id: admin_id,
                                        },
                                        success: function (response) {
                                            $('#closePopup').click();
                                            $('.lrFirstReload').click();
                                            $.alert({
                                                title: 'Success',
                                                content: 'Loan approved successfully.',
                                                type: 'green'
                                            });
                                        }
                                    });
                                }
                            },
                            No: {
                                btnClass: 'btn-secondary'
                            }
                        }
                    });
                }
            }
        }
    });
});

$(document).on('click', '.rejectData', function() {
    let loan_id = $('#customLoanPopup').attr('data-loan_id');
    let id = $(this).attr('id');
    
    $.confirm({
        title: 'Reject Field',
        content: function () {
            // If it's loanAmount, add an input field to suggest amount
            if (id === 'rejectAmount') {
                return '' +
                    '<div>' +
                        '<label><strong>Suggested Loan Amount</strong></label>' +
                        '<input type="number" min="0" class="form-control mt-2" id="suggestedAmount" placeholder="Enter suggested amount">' +
                        '<label class="mt-3"><strong>Remarks</strong></label>' +
                        '<textarea class="form-control mt-2" id="remarks" rows="3" placeholder="Enter remarks"></textarea>' +
                    '</div>';
            }
            return 'Are you sure you want to reject this field?';
        },
        buttons: {
            confirm: {
                text: 'Yes',
                btnClass: 'btn-danger',
                action: function () {
                    let jc = this;
                    let dataToSend = { loan_id: loan_id, field_id: id };

                    // If rejecting loanAmount, also send suggested amount
                    if (id === 'rejectAmount') {
                        let suggestedAmount = this.$content.find('#suggestedAmount').val();
                        let remarks = this.$content.find('#remarks').val();
                        if (!suggestedAmount) {
                            $.alert('Please enter a suggested loan amount.');
                            return false; // stop confirm until valid
                        }

                        // 🔹 Show second confirmation
                        $.confirm({
                            title: 'Confirm Suggested Amount',
                            content: 'Are you sure you want to save these changes with amount <strong>' + suggestedAmount + '</strong>?',
                            buttons: {
                                yes: {
                                    text: 'Yes, Save',
                                    btnClass: 'btn-success',
                                    action: function () {
                                        dataToSend.suggested_amount = suggestedAmount;
                                        dataToSend.remarks = remarks;
                                        saveRejection(dataToSend);
                                        jc.close();
                                    }
                                },
                                no: {
                                    text: 'No',
                                    btnClass: 'btn-secondary'
                                }
                            }
                        });

                        return false; // prevent first confirm from continuing
                    }

                    // For other rejects (no amount)
                    saveRejection(dataToSend);
                }
            },
            cancel: {
                text: 'Cancel'
            }
        }
    });

    // ✅ Extracted save logic
    function saveRejection(data) {
        $.ajax({
            url: '/admin/loan-request/loan/' + loan_id + '/reject-field',
            method: 'POST',
            data: data,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Rejected!',
                    text: 'This field has been rejected'
                });
            }
        });
    }
});


$(document).on("click", "#updateStatus", function () {
    let approvalText = $("#approvalCounter").text().trim(); // e.g. "1/3"
    let parts = approvalText.split("/");

    let current = parseInt(parts[0], 10);
    let total = parseInt(parts[1], 10);

    $("#loan_status_admin").prop("disabled", false).focus();

    if (current === total && total === 3) {
        $("#loan_status_admin").find('option[value="2"]').removeAttr('disabled', 'true');
        $("#loan_status_admin").find('option[value="4"]').removeAttr('disabled', 'true');
        $("#loan_status_admin").find('option[value="5"]').removeAttr('disabled', 'true');
        $("#loan_status_admin").find('option[value="8"]').removeAttr('disabled', 'true');
    } else {
        $("#loan_status_admin").find('option[value="2"]').attr('disabled', 'true');
        $("#loan_status_admin").find('option[value="4"]').attr('disabled', 'true');
        $("#loan_status_admin").find('option[value="5"]').attr('disabled', 'true');
        $("#loan_status_admin").find('option[value="8"]').attr('disabled', 'true');
    }
});


$(document).on("change", "#loan_status_admin", function () {
    let originalStatus = $('#updateStatus').attr("data-original_status");
    let newStatus = $(this).val();
    let $select = $(this);
    let loan_id = $('#customLoanPopup').attr('data-loan_id');

    $.confirm({
        title: 'Confirm Status Change',
        content: 'Are you sure you want to change the loan status?',
        buttons: {
            Yes: {
                btnClass: 'btn-success',
                action: function () {
                    $.ajax({
                        url: '/admin/loan-request/update-loan-status',
                        method: 'POST',
                        data: {
                            loan_id: loan_id, 
                            status_id: newStatus
                        },
                        success: function (res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Loan status updated successfully!',
                                confirmButtonColor: '#3085d6'
                            });
                            $('#closePopup').click();
                            $('.lrFirstReload').click();
                            originalStatus = newStatus; // update stored value
                            $select.prop("disabled", true); // lock again if you want
                            setTimeout(function () {
                                $('#loanSubNav').click();
                            }, 2000); 
                        },
                        error: function () {
                            $.alert('Error updating loan status.');
                            $select.val(originalStatus); // revert on error
                        }
                    });
                }
            },
            No: {
                btnClass: 'btn-danger',
                action: function () {
                    $select.val(originalStatus);
                    $select.prop("disabled", true);
                }
            }
        }
    });
});

$(document).on("click", ".transfer_money", function () {

    let loan_id          = $(this).attr('data-loan_id');
    let screenshot       = $('#screenshot')[0].files[0];  // file input
    let refNumber        = $('#refNumber').val(); 
    let transferDate     = $('#transferDate').val();
    let processedBy      = $('#processedBy').val();
    let monthlyDueDate   = $('#monthlyDueDate').val();
    let remarks          = $('#remarks').val();
    let amount           = $('.disburse_amount').text();
    let bank             = $(this).attr('data-bank_amount');

    if (parseFloat(bank) < parseFloat(amount)) {

        Swal.fire({
            icon: 'warning',
            title: 'Warning',
            text: 'Insufficient Fund !',
            confirmButtonColor: '#3085d6'
        });
        return false;
    }

    let formData = new FormData();
    formData.append('loan_id', loan_id);
    formData.append('screenshot', screenshot);
    formData.append('ref_number', refNumber);
    formData.append('transfer_date', transferDate);
    formData.append('processed_by', processedBy);
    formData.append('monthly_due_date', monthlyDueDate);
    formData.append('remarks', remarks);

     let isValid = true;

    $('#screenshot, #refNumber, #remarks').removeClass('is-invalid');

    // Validation
    if (!screenshot) {
        $('#screenshot').addClass('is-invalid');
        isValid = false;
    }
    if (refNumber === "") {
        $('#refNumber').addClass('is-invalid');
        isValid = false;
    }
    if (remarks === "") {
        $('#remarks').addClass('is-invalid');
        isValid = false;
    }

    if (!isValid) {
        return; 
    }

    $.confirm({
        title: 'Are you sure you want to transfer this amount?',
        columnClass: 'medium',
        content: 'This action cannot be undone.',
        buttons: {
            Yes: {
                text: 'Yes',
                btnClass: 'btn-success',
                action: function () {
                    $.ajax({
                        url: '/admin/loan-request/transfer-money',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            if (res.success) {
                                $('.close_transfer').click();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: res.message,
                                    confirmButtonColor: '#198754', // Bootstrap success green
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // close modal, refresh table, etc.
                                    $('#transferModal').modal('hide'); 
                                    location.reload(); 
                                });
                            }else{
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Failed',
                                    text: 'Insufficient Fund !',
                                    confirmButtonColor: '#3085d6'
                                });
                            }
                        }
                    });
                }
            },
            No: {
                text: 'No',
                btnClass: 'btn-secondary'
            }
        }
    });
});

// $(document).ready(function () {
//     // get loan_id from query param
//     const urlParams = new URLSearchParams(window.location.search);
//     const loan_id = urlParams.get('loan_id');

//     if (loan_id) {
//         // find the matching row and trigger click
//         $(`#loanBody tr[data-loan_id="${loan_id}"]`).trigger('click');
//     }
// });


$(document).on('click', '.document_notifs', function () {

    let loan_id = $(this).attr('value');

    window.location.href = '/admin/loan-request';
});

$(document).on("click", ".appeal_notifs", function () {
    let loan_id = $(this).attr("value"); 
    window.location.href = "/admin/view-appeal/" + loan_id;
});

$(document).on("click", ".received_appeal", function () {
    let appeal_id = $(this).attr("value");

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to mark this appeal as received?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745", // green
        cancelButtonColor: "#d33",     // red
        confirmButtonText: "Yes, mark as received",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            
            $.ajax({
                url: "/admin/appeal/mark-received",
                type: "POST",
                data: {
                    id: appeal_id
                },
                 headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                success: function (res) {
                    Swal.fire(
                        "Marked!",
                        "The appeal has been marked as received.",
                        "success"
                    );
                    // optional reload table
                    location.reload();
                },
                error: function () {
                    Swal.fire(
                        "Error!",
                        "Something went wrong, please try again.",
                        "error"
                    );
                }
            });
        }
    });
});

$(document).on("click", ".viewComment", function () {
    $.ajax({
        url: "/admin/get-rejected-comments",
        type: "POST",
        data: {},
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        success: function (res) {
            // Clear and create container
            $("#commentTab").html(`
                <div class="mt-4 px-3 py-4 border rounded bg-light shadow-sm" 
                     id="commentContainer" 
                     style="max-height:400px; overflow-y:auto; text-align:center;"></div>
            `);

            let container = $("#commentContainer");
            let currentUser = res.current_user;

            if (res.comment.length === 0) {
                // No comments found
                container.html(`
                    <div class="text-muted fst-italic">No comments yet.</div>
                `);
            } else {
                // Loop through comments
                res.comment.forEach(c => {
                    let side = (c.user_id === currentUser) ? "me" : "other";

                    if (side === "me") {
                        // My own comment (right side, no name)
                        container.append(`
                            <div class="comment ${side}">
                                ${c.description}
                                <div class="c_date small text-muted mt-1">${c.formatted_date}</div>
                            </div>
                        `);
                    } else {
                        // Others' comments (left side, with name)
                        container.append(`
                            <div class="comment-wrapper">
                                <div class="comment-name">${c.name}</div>
                                <div class="comment ${side}">
                                    ${c.description}
                                    <div class="c_date small text-muted mt-1">${c.formatted_date}</div>
                                </div>
                            </div>
                        `);
                    }
                });

                // Auto-scroll to bottom
                let domContainer = document.getElementById("commentContainer");
                domContainer.scrollTop = domContainer.scrollHeight;
            }
        },
        error: function () {
            Swal.fire(
                "Error!",
                "Something went wrong, please try again.",
                "error"
            );
        }
    });
});
