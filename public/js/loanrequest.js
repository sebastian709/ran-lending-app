$(document).ready(function () {
    let loanData = [];
    const itemsPerPage = 10;
    let currentPage = 1;
    let filteredData = [];

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
                let scheduledDateObj = new Date(item.scheduled_date);
                let twoDaysBefore = new Date(scheduledDateObj);
                twoDaysBefore.setDate(scheduledDateObj.getDate() - 2);

                let today = new Date();
                today.setHours(0, 0, 0, 0);

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
        $pagination.empty();

        for (let i = 1; i <= totalPages; i++) {
            const pageItem = `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <button class="page-link page-btn" data-page="${i}">${i}</button>
                </li>
            `;
            $pagination.append(pageItem);
        }
    }

    $(document).on('click', '.page-btn', function () {
        currentPage = parseInt($(this).data('page'));
        window.renderTable();
    });

    // ✅ Fixed search (matches your actual DB fields)
    $('#search').on('input', function () {
        const query = $(this).val().toLowerCase();
        filteredData = loanData.filter(item =>
            ('LN-' + String(item.id).padStart(5, '0')).toLowerCase().includes(query) ||
            item.loan_applicant.toLowerCase().includes(query) ||
            (item.referral || '').toLowerCase().includes(query)
        );
        currentPage = 1;
        window.renderTable();
    });

    // ====== Load data with filter when nav clicked ======
    $(document).off('click', '#loanSubNav a').on('click', '#loanSubNav a', function (e) {
        e.preventDefault();
        $('#loanSubNav a').removeClass('active');
        $(this).addClass('active');

        let loan_status = $(this).data('loan_status') || '';

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
    });

    // // ✅ Initial load (all loans)
    // $.ajax({
    //     url: '/admin/loan-request/data',
    //     method: 'GET',
    //     dataType: 'json',
    //     success: function (data) {
    //         loanData = data;
    //         filteredData = [...loanData];
    //         window.renderTable();
    //     },
    //     error: function (xhr, status, error) {
    //         console.error('Error fetching data:', error);
    //         $('#emptyState').show();
    //     }
    // });
});

$(document).ready(function () {


    $(document).on('click', '#loanBody tr', function () {

        let loan_status = $(this).attr('data-loan-status');

        console.log('loan_status: ',loan_status)
        $('.navbar-custom').css('z-index', 0);
        let loan_id = $(this).attr('data-loan_id');
        let complete_loan_id = 'LN-'+String(loan_id).padStart(5, '0');

        let loan_type = $(this).attr('data-loan_type');
        let scheduled_date = $(this).attr('data-scheduled_date');
        let scheduledDateObj = new Date(scheduled_date);

        // Kunin ang "2 days before" ng scheduled date
        let twoDaysBefore = new Date(scheduledDateObj);
        twoDaysBefore.setDate(scheduledDateObj.getDate() - 2);

        // Today
        let today = new Date();
        today.setHours(0, 0, 0, 0);

        // Format function para maging "September 21, 2025"
        function formatDate(date) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        }

        if (parseInt(loan_status) == 4 && loan_type == 'Scheduled' && today < twoDaysBefore) {
            Swal.fire({
                icon: 'info',
                title: 'Transfer Not Available Yet',
                text: 'Transfer money will only be available 2 days before the scheduled date (' + formatDate(scheduledDateObj) + ').',
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
                    console.log(ress);

                    let rqrCode = ress.upload_qr_code_img;

                    let rqrContent = rqrCode && rqrCode.trim() !== ""
                        ? `
                            <div>
                                <div class="p-1 border rounded-3 bg-light d-inline-block shadow-sm">
                                    <img src="${rqrCode}" alt="Bank QR Code" width="160" height="160">
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
                        </ul>
                        <!-- Tab Content -->
                        <div class="tab-content mt-4">
                            <!-- Bank Tab -->
                            <div class="tab-pane fade show active" id="bankTab">
                                <div class="card shadow-sm border-0 rounded-4 p-5 text-center">
                                    <h4 class="fw-bold text-primary mb-4"><i class="ri-bank-line"></i> Bank Details</h4>
                                    <div class="justify-content-center gap-5 mb-4 flex-wrap">
                                        <div>
                                            <p class="fs-5 mb-0">${ress.bank_name}</p>
                                            <label class="form-label fw-semibold d-block text-muted">Bank Name</label>
                                        </div>
                                    </div>
                                    ${rqrContent}
                                    <div>
                                        <div class="p-1 border rounded-3 bg-light d-inline-block shadow-sm">
                                            <img src="${ress.upload_qr_code_img}" alt="Bank QR Code" width="160" height="160">
                                        </div>
                                    </div>
                                    <div class="justify-content-center gap-5 mt-4 flex-wrap">
                                        <div>
                                            <p class="fs-5 mb-0">${ress.account_number}</p>
                                            <label class="form-label fw-semibold d-block text-muted">Account Number</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Transfer Tab -->
                            <div class="tab-pane fade" id="transferTab">
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
                                </div>

                                <!-- Remarks -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Optional remarks..."></textarea>
                                </div>

                                <div class="d-flex justify-content-center mt-4">
                                    <button type="button" class="btn btn-success px-4 transfer_money" data-loan_id="${loan_id}">Transfer Money<i class="ri-arrow-right-line ms-2"></i></button>
                                </div>
                                </form>
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
                            // Autopopulate Monthly Due Date when modal content is ready
                            const now = new Date();
                            const due = nextMonthMinusOneDay(now);
                            $('#monthlyDueDate').val(toYMD(due));
                            $('#transferDate').val(toYMD(now));
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

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
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

                    // console.log('test', loan_status)
                    if (parseInt(loan.loan_status) == 5) {
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

                            console.log(loan_stat_access.pending)
                        } else if (status.id == 2) {
                            ls_hidden = loan_stat_access.for_interview == 1 ? '' : 'hidden';

                            console.log(loan_stat_access.for_interview)
                        } else if (status.id == 3) {
                            ls_hidden = loan_stat_access.for_revision == 1 ? '' : 'hidden';
                            ls_is_disabled = "disabled";
                            console.log(loan_stat_access.for_revision)
                        } else if (status.id == 4) {
                            ls_hidden = loan_stat_access.waiting == 1 ? '' : 'hidden';

                            console.log(loan_stat_access.waiting)
                        } else if (status.id == 5) {
                            ls_hidden = loan_stat_access.transferred_and_processed == 1 ? '' : 'hidden';

                            console.log(loan_stat_access.transferred_and_processed)
                        } else if (status.id == 6) {
                            ls_hidden = loan_stat_access.rejected == 1 ? '' : 'hidden';

                            console.log(loan_stat_access.rejected)
                        } else if (status.id == 7) {
                            ls_hidden = loan_stat_access.closed == 1 ? '' : 'hidden';

                            console.log(loan_stat_access.closed)
                        }

                        loanStatusDropdowns += `<option value="${status.id}" ${loan.loan_status == status.id ? "selected" : ""} ${ls_hidden} ${ls_is_disabled}>${status.loan_status}</option>`;
                    });
                    let rlt_request_date = `<strong>Requested Date:</strong> ${loan.scheduled_date}`
                    let loan_detail_content = `<div class="mt-4 px-3 py-4 border rounded bg-light shadow-sm">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <strong>Requested Amount:</strong> ₱<span class="changeEditLR">${loan.loan_amount && Math.floor(loan.loan_amount).toLocaleString('en-US')}</span> <i class="ri-pencil-fill text-danger ms-2 rejectData" style="cursor:pointer;" title="Reject this field" id="rejectAmount"></i><br>
                                                            <strong>Loan Term:</strong> ${parseInt(loan.loan_tenure)} months<br>
                                                            <strong>Interest:</strong> ${loan.interest_rate * 100}% <br>
                                                            ${loan.loan_type == 'Scheduled' ? rlt_request_date : ''}
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Purpose:</strong> ${loan.purpose_of_loan}<br>
                                                            <strong>Created At:</strong> ${loan.created_at}<br>
                                                            <strong>Last Updated:</strong> ${loan.updated_at}<br>
                                                            <strong>Referral:</strong> ${loan.referral}
                                                        </div>
                                                    </div>
                                                    <div class="mt-3 d-flex align-items-center" style="max-width:30%;">
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
                                                </div>`;

                    $('.alr_loan_details_content').empty().append(loan_detail_content);

                    let qrCodess = loan.upload_qr_code_img;

                    let qrContent = qrCodess && qrCodess.trim() !== ""
                        ? `
                            <img src="${qrCodess}" alt="QR Code" class="img-fluid border rounded" style="max-height: 300px;">
                            <div>
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-outline-danger rejectData ms-2 mt-4" 
                                    id="rejectQRcode" 
                                    title="Reject this field">
                                    <i class="ri-pencil-fill"></i> Reject
                                </button>
                            </div>
                        `
                        : `<p>No QR Available</p>`;

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
                                                                <img src="${loan.payslip_img}" alt="Proof of Income" class="img-fluid border rounded" style="max-height: 300px;">
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
                                                                
                                                                <img src="${loan.upload_qr_code_img}" alt="QR Code" class="img-fluid border rounded" style="max-height: 300px;">
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
                                                                <img src="${loan.government_id_img}" alt="Government ID" class="img-fluid border rounded" style="max-height: 300px;">
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
                                                                <img src="${loan.billing_statement_img}" alt="Supporting Documents" class="img-fluid border rounded" style="max-height: 300px;">
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

                    let loan_history_content = `<div class="mt-4 px-3 py-4 border rounded bg-light shadow-sm">
                                                    <h5 class="mb-4">
                                                        <i class="bi bi-journal-text me-2"></i> Loan History
                                                    </h5>

                                                    <ul class="list-unstyled">
                                                        <li class="mb-3">
                                                            <ul class="list-unstyled ms-3">
                                                                <li><strong>Total Loans Taken:</strong> ${loan.loan_taken}</li>
                                                                <li><strong>Total Loan Amount:</strong> ₱${loan.total_loan_amount}</li>
                                                                <li><strong>Date of First Loan:</strong> ${loan.first_loan_date}</li>
                                                                <li><strong>Referral:</strong> ${loan.referral}</li>
                                                                <br>
                                                                <li><strong>Violations:</strong>  ${grade.violations}
                                                                    <div class="text-muted small ms-3">→ 3 consecutive months of no payment = 1 violation</div>
                                                                </li>
                                                                <li><strong>Penalties:</strong> ${grade.penalties}
                                                                    <div class="text-muted small ms-3">→ Every 7 days after monthly due date = 1 penalty</div>
                                                                </li>

                                                                <li><strong>Last Loan Date: </strong>${loan.last_loan_date}</li><br>
                                                                <li><strong>Remarks:</strong> <span class="badge bg-success">Excellent</span></li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                    <div class="text-center mt-4">
                                                        <a href="/admin/customer/123" class="btn btn-primary px-4">See More</a>
                                                    </div>
                                                </div>`;

                    $('#historyTab').empty().append(loan_history_content);
                        
                    let activity_logs_content = `<ul id="activityLogCustom" class="list-group mt-3">`;
                            logs.forEach(log => {
                                activity_logs_content += `<li class="list-group-item">${log.created_at}: [${log.user_name}] ${log.description}</li>`;
                            });
                    activity_logs_content += `</ul>`;

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
                                            '<textarea class="comment form-control" rows="4" required></textarea>' +
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
                                                            $.alert({
                                                                title: 'Success',
                                                                content: 'The loan request has been rejected successfully.',
                                                                type: 'green'
                                                            });
                                                            console.log('Updated disapproved_by_admins:', response.disapproved_by_admins);
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
                                            console.log('Updated approved_by_admins:', response.approved_by_admins);
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
                            originalStatus = newStatus; // update stored value
                            $select.prop("disabled", true); // lock again if you want
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
