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
        const badgeClass = classMap[status] || 'light';
        return `<span class="badge bg-${badgeClass}">${status_name}</span>`;
    }

    function renderTable() {
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
                const row = `
                    <tr data-loan_id="${item.id}">
                        <td>${'LN-' + String(item.id).padStart(5, '0')}</td>
                        <td>${item.loan_applicant}</td>
                        <td>₱${parseFloat(item.loan_amount).toLocaleString()}</td>
                        <td>${item.loan_tenure} months</td>
                        <td>${item.interest_rate * 100}%</td>
                        <td>${item.created_at}</td>
                        <td>${item.referral || '-'}</td>
                        <td>${renderStatusBadge(item.loan_status, item.loan_status_name)}</td>
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
        renderTable();
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
        renderTable();
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
                renderTable();
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data:', error);
                $('#emptyState').show();
            }
        });
    });

    // ✅ Initial load (all loans)
    $.ajax({
        url: '/admin/loan-request/data',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            loanData = data;
            filteredData = [...loanData];
            renderTable();
        },
        error: function (xhr, status, error) {
            console.error('Error fetching data:', error);
            $('#emptyState').show();
        }
    });
});

$(document).ready(function () {
 

    $(document).on('click', '#loanBody tr', function () {
        $('.topbar').css('z-index', 0);
        $('#loanStatusCustom').val('Pending');
        $('#customLoanPopup').removeClass('d-none');
        $('#loanTabs button:first').tab('show'); // activate first tab

        let loan_id = $(this).attr('data-loan_id');
        let complete_loan_id = 'LN-'+String(loan_id).padStart(5, '0');
        $('#customLoanPopup').attr('data-loan_id',loan_id);
        $('.alr_loan_id').text(complete_loan_id);

        $.ajax({
        url: '/admin/loan-request/get-loan-data',
        method: 'POST',
        data : {
            "loan_id" : loan_id
        },
        dataType: 'json',
        success: function (r) {

            let loan = r.data;
            let logs = r.logs;
            let admins = r.admins;

            let approvedAdmins = r.approved_by;    
            let disapprovedAdmins = r.disapproved_by; 

            $('#approvalCounter').text(`${approvedAdmins.length}/3`);
            $('.form-check-input.admin-approval').each(function() {
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


            let loan_detail_content = `<div class="mt-4 px-3 py-4 border rounded bg-light shadow-sm">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <strong>Amount:</strong> ₱${parseFloat(loan.loan_amount).toFixed(2)}<i class="ri-pencil-fill text-danger ms-2 rejectData" style="cursor:pointer;" title="Reject this field" id="rejectAmount"></i><br>
                                                    <strong>Loan Term:</strong> ${parseInt(loan.loan_tenure)} months<br>
                                                    <strong>Interest:</strong> ${loan.interest_rate * 100}%
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Purpose:</strong> ${loan.purpose_of_loan}<br>
                                                    <strong>Request Date:</strong> ${loan.created_at}<br>
                                                    <strong>Last Updated:</strong> ${loan.updated_at}<br>
                                                    <strong>Referral:</strong> ${loan.referral}
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <strong>Status:</strong> ${loan.loan_status_name}
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
                                                        <li><strong>Violations:</strong> 1
                                                            <div class="text-muted small ms-3">→ 3 consecutive months of no payment = 1 violation</div>
                                                        </li>
                                                        <li><strong>Penalties:</strong> 3
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
    });


    $('#closePopup').on('click', function () {
        $('#customLoanPopup').addClass('d-none');
        $('.topbar').css('z-index', 2);
    });
});

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
                                            admin_id: admin_id
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
                    '</div>';
            }
            return 'Are you sure you want to reject this field?';
        },
        buttons: {
            confirm: {
                text: 'Yes',
                btnClass: 'btn-danger',
                action: function () {
                    let dataToSend = { loan_id: loan_id, field_id: id };

                    // If rejecting loanAmount, also send suggested amount
                    if (id === 'rejectAmount') {
                        let suggestedAmount = this.$content.find('#suggestedAmount').val();
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
                                        saveRejection(dataToSend);
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
                $.alert({
                    title: 'Success',
                    content: 'This field has been rejected.',
                    type: 'green'
                });
            }
        });
    }
});

