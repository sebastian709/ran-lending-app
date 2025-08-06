$(document).ready(function () {
    const loanData = [
        { loan_id: 'LN-001', borrower_name: 'Juan Dela Cruz', loan_amount: 50000, loan_tenure: 12, interest_rate: 5.0, request_date: '2025-08-01', referral: 'Mark Santos', status: 'Pending Approval' },
        { loan_id: 'LN-002', borrower_name: 'Maria Clara', loan_amount: 100000, loan_tenure: 24, interest_rate: 6.5, request_date: '2025-07-15', referral: 'John Doe', status: 'For Interview' },
        { loan_id: 'LN-003', borrower_name: 'Andres Bonifacio', loan_amount: 75000, loan_tenure: 18, interest_rate: 4.5, request_date: '2025-07-20', referral: '', status: 'Waiting for Disbursement' },
        { loan_id: 'LN-004', borrower_name: 'Emilio Aguinaldo', loan_amount: 60000, loan_tenure: 6, interest_rate: 3.0, request_date: '2025-06-28', referral: 'Jose Rizal', status: 'Processed' },
        { loan_id: 'LN-005', borrower_name: 'Melchora Aquino', loan_amount: 80000, loan_tenure: 9, interest_rate: 5.5, request_date: '2025-08-02', referral: 'Heneral Luna', status: 'Rejected' },
        { loan_id: 'LN-006', borrower_name: 'Apolinario Mabini', loan_amount: 120000, loan_tenure: 24, interest_rate: 6.0, request_date: '2025-08-03', referral: '', status: 'For Revision' },
        { loan_id: 'LN-007', borrower_name: 'Gregoria De Jesus', loan_amount: 45000, loan_tenure: 12, interest_rate: 4.2, request_date: '2025-07-10', referral: 'Diego Silang', status: 'Pending Approval' },
        { loan_id: 'LN-008', borrower_name: 'Manuel Quezon', loan_amount: 90000, loan_tenure: 18, interest_rate: 5.8, request_date: '2025-06-12', referral: 'Antonio Luna', status: 'For Interview' },
        { loan_id: 'LN-009', borrower_name: 'Sergio Osmeña', loan_amount: 110000, loan_tenure: 24, interest_rate: 7.0, request_date: '2025-05-22', referral: 'Jose Abad Santos', status: 'Waiting for Disbursement' },
        { loan_id: 'LN-010', borrower_name: 'Josefa Llanes Escoda', loan_amount: 65000, loan_tenure: 10, interest_rate: 4.9, request_date: '2025-08-05', referral: '', status: 'Processed' }
    ];

    const itemsPerPage = 10;
    let currentPage = 1;
    let filteredData = [...loanData];

    function renderStatusBadge(status) {
        const classMap = {
            'Pending Approval': 'secondary',
            'For Interview': 'primary',
            'For Revision': 'warning',
            'Waiting for Disbursement': 'info',
            'Processed': 'success',
            'Rejected': 'danger'
        };
        const badgeClass = classMap[status] || 'light';
        return `<span class="badge bg-${badgeClass}">${status}</span>`;
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
                    <tr>
                        <td>${item.loan_id}</td>
                        <td>${item.borrower_name}</td>
                        <td>₱${parseFloat(item.loan_amount).toLocaleString()}</td>
                        <td>${item.loan_tenure} months</td>
                        <td>${item.interest_rate}%</td>
                        <td>${item.request_date}</td>
                        <td>${item.referral || '-'}</td>
                        <td>${renderStatusBadge(item.status)}</td>
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

    renderTable();
});

$(document).ready(function () {
 

    $(document).on('click', '#loanBody tr', function () {
        $('.topbar').css('z-index', 0);
        $('#loanStatusCustom').val('Pending');
        $('#customLoanPopup').removeClass('d-none');
        $('#loanTabs button:first').tab('show'); // activate first tab
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
