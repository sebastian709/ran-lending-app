$(document).ready(function () {
    let loanData = [];
    const itemsPerPage = 10;
    let currentPage = 1;
    let filteredData = [];

    function renderStatusBadge(status, status_name) {
        const classMap = {
            '1' : 'secondary',
            '2' : 'primary',
            '3' : 'warning',
            '4' : 'info',
            '5' : 'success',
            '6' : 'danger'
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
                        <td>${'LN-'+String(item.id).padStart(5, '0')}</td>
                        <td>${item.loan_applicant}</td>
                        <td>₱${parseFloat(item.loan_amount).toLocaleString()}</td>
                        <td>${item.loan_tenure} months</td>
                        <td>${item.interest_rate * 100}%</td>
                        <td>${item.created_at}</td>
                        <td>${item.referral || '-'}</td>
                        <td>${renderStatusBadge(item.loan_status,item.loan_status_name)}</td>
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

    $('#search').on('input', function () {
        const query = $(this).val().toLowerCase();
        filteredData = loanData.filter(item =>
            item.loan_id.toLowerCase().includes(query) ||
            item.borrower_name.toLowerCase().includes(query) ||
            (item.referral || '').toLowerCase().includes(query)
        );
        currentPage = 1;
        renderTable();
    });

    // ====== Load data from Laravel backend ======
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

        $('.alr_loan_id').text(complete_loan_id);

        $.ajax({
        url: '/admin/loan-request/get-loan-data',
        method: 'POST',
        data : {
            "loan_id" : loan_id
        },
        dataType: 'json',
        success: function (r) {
            let loan_detail_content = `<div class="mt-4 px-3 py-4 border rounded bg-light shadow-sm">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <strong>Amount:</strong> ₱${parseFloat(r.loan_amount).toFixed(2)}<br>
                                                    <strong>Loan Term:</strong> ${parseInt(r.loan_tenure)} months<br>
                                                    <strong>Interest:</strong> ${r.interest_rate * 100}%
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Purpose:</strong> ${r.purpose_of_loan}<br>
                                                    <strong>Request Date:</strong> ${r.created_at}<br>
                                                    <strong>Last Updated:</strong> ${r.purpose_of_loan}<br>
                                                    <strong>Referral:</strong> Code
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <strong>Status:</strong> Pending Approval
                                            </div>
                                        </div>`;

            $('.alr_loan_details_content').empty().append(loan_detail_content);
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
    const accountId = $(this).data('id');

    $.confirm({
        title: 'Confirmation',
        content: 'Are you sure you want to approve this application?',
        type: 'green',
        boxWidth: '400px',
        useBootstrap: false,
        buttons: {
            edit: {
                text: 'Edit',
                btnClass: 'btn-warning',
                action: function () {
                }
            },
            no: {
                text: 'No',
                btnClass: 'btn-danger',
                action: function () {
                    
                }
            },
            yes: {
                text: 'Yes',
                btnClass: 'btn-success',
                action: function () {
                    $.alert('Loan approved!');
                }
            }
        }
    });
});
