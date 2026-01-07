<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Executive Investment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32-ran.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16-ran.png') }}">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

    <!-- Date Range Picker -->
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
        .metric-card { border-left: 5px solid #0d6efd; }
        .cursor-pointer { cursor: pointer; }
    </style>
</head>
<body>
@extends('admin.container')
@section('content')

<div class="container-fluid py-4">

    <!-- PAGE TITLE -->
    <div class="mb-4">
        <h3 class="fw-bold">Executive Investment</h3>
        <small class="text-muted">Admin Profile → Executive Investment</small>
    </div>

    <!-- ===================== -->
    <!-- 1. KEY METRICS -->
    <!-- ===================== -->
    <div class="row g-3 mb-4">
    <div class="col-md-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <h6>My Shared Fund</h6>
                    <h4>₱ <span class="shared_fund">0.00</span></h4>
                    <small class="text-muted">My Investment Amount</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <h6>Total Loan Fund</h6>
                    <h4 class="loan_fund">₱ <span class="loan_fund">0.00</span></h4>
                    <small class="text-muted">Money allotted for lending</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <h6>Miscellaneous Expense</h6>
                    <h4>₱ <span class="expenses">0.00</span></h4>
                    <small class="text-muted">Outside business expenses</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <h6>My Dividend</h6>
                    <h4 class="dividend">₱ 0.00</h4>
                    <small class="text-muted">My Dividend</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <h6>My Shared Fund</h6>
                    <h4>₱ <span class="shared_fund">0.00</span></h4>
                    <small class="text-muted">My Investment Amount</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <h6>Total Loan Fund</h6>
                    <h4 class="loan_fund">₱ <span class="loan_fund">0.00</span></h4>
                    <small class="text-muted">Money allotted for lending</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <h6>Miscellaneous Expense</h6>
                    <h4>₱ <span class="expenses">0.00</span></h4>
                    <small class="text-muted">Outside business expenses</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <h6>My Dividend</h6>
                    <h4 class="dividend">₱ 0.00</h4>
                    <small class="text-muted">My Dividend</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ===================== -->
    <!-- 2. MY INVESTMENT -->
    <!-- ===================== -->
    <!-- <div class="card shadow-sm  mb-4">
        <div class="card-header">
            <h6 class="mb-0">My Investment</h6>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Total Investment:</strong> ₱ <span class="total_investment_me">0.00</span>
                </div>
                <div class="col-md-6">
                    <strong>Total Dividend:</strong> ₱ <span class="total_dividend_me">0.00</span>
                </div>
            </div>
 
        </div> -->
    <!-- </div> -->

    <!-- ===================== -->
    <!-- 3. FUND MANAGEMENT -->
    <!-- ===================== -->
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Fund Management</h6>
            <div class="d-flex gap-2">
                <button class="btn btn-primary btn-sm openAddInvestment">
                    <i class="bi bi-plus-circle"></i> Add Investment
                </button>
                <button class="btn btn-danger btn-sm openWithdraw">
                    <i class="bi bi-dash-circle"></i> Withdraw Funds
                </button>
            </div>
        </div>

        <div class="card-body">
            <!-- TABLE -->
            <table class="table table-striped table-bordered" id="investmentTable">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <!-- <th>Category</th> -->
                        <!-- <th>Actions</th> -->
                    </tr> 
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>
    </div>

</div>


@endsection

<!-- ===================== -->
<!-- SCRIPTS -->
<!-- ===================== -->

<!-- 1. jQuery first (no defer) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

<!-- 2. jQuery-confirm plugin -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-confirm@3.3.4/dist/jquery-confirm.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery-confirm@3.3.4/dist/jquery-confirm.min.js"></script>

<!-- 3. Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- 4. DataTables core (no defer) -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<!-- 5. Moment.js + Date Range Picker -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- 6. DataTables Buttons (no defer) -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

<!-- 7. Export dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- 8. Export buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>

$(function() {
        $(document).ready(function() {
            pull_data();

        });
        function pull_data(){
            
            $('#investmentTable').DataTable().clear().destroy();
            $.ajax({
                url: '/executive/pull_data',
                type: 'POST',
                dataType: 'json',
                headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                success: function (res) {
                    $('.loan_fund').text(res.data.lending_fund.amount)
                    $('.shared_fund').text(res.data.shared_fund.amount)

                    var fund_management;
                    $.each(res.data.fund_management, function (k, v) {

                        fund_management += `
                            <tr>
                                    <td>${v.name}</td>
                                    <td>₱ ${v.amount}</td>
                                    <td>${v.readable_date}</td>
                               
                                </tr>
                        `;
                        
                    });
                    
                    $('#investmentTable').find('tbody').append(fund_management);
                    $('#investmentTable').DataTable({
                        responsive: true,
                        pageLength: 10,
                        dom: 'Bfrtip', // Buttons on top
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                title: 'Loan Report',
                                className: 'btn'
                            },
                            {
                                extend: 'pdfHtml5',
                                title: 'Loan Report',
                                pageSize: 'A4',
                                className: 'btn'
                            }
                        ]
                    });  
                },
                error: function (xhr) {
                },
                complete: function () {
                }
            });


        }

        $(document).off('click', '.executive_add_investment').on('click', '.executive_add_investment', function (e) {
            e.preventDefault();

            const $btn = $(this);
            let hasError = false;

            const $amount   = $('.executive_add_amount');
            const $remarks  = $('.executive_add_remarks');
            const $category = $('.executive_add_category');

            // Reset validation
            $amount.add($remarks).add($category).removeClass('is-invalid');

            // Validation
            if (!$amount.val().trim()) {
                $amount.addClass('is-invalid');
                hasError = true;
            }

            if (!$category.val()) {
                $category.addClass('is-invalid');
                hasError = true;
            }

            if (hasError) {
                $.confirm({
                    title: 'Validation Error',
                    content: 'Please complete all required fields.',
                    type: 'red',
                    buttons: {
                        ok: {
                            btnClass: 'btn-danger',
                            action: function () {
                                $('.is-invalid:first').focus();
                            }
                        }
                    }
                });
                return;
            }

            // Confirmation before submit
            $.confirm({
                title: 'Confirm Investment',
                content: 'Are you sure you want to add this investment?',
                type: 'blue',
                buttons: {
                    confirm: {
                        text: 'Yes, Add',
                        btnClass: 'btn-primary',
                        action: function () {

                            // Disable button to prevent double submit
                            $btn.prop('disabled', true).html(
                                '<span class="spinner-border spinner-border-sm"></span> Saving...'
                            );

                            $.ajax({
                                url: '/executive/add_investment',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    amount: $amount.val(),
                                    remarks: $remarks.val(),
                                    category: $category.val()
                                },
                                success: function (res) {

                                    $.confirm({
                                        title: 'Success',
                                        content: 'Investment has been added successfully.',
                                        type: 'green',
                                        buttons: {
                                            ok: {
                                                btnClass: 'btn-success'
                                            }
                                        }
                                    });

                                    // Reset fields
                                    $amount.val('');
                                    $remarks.val('');
                                    pull_data();

                                },
                                error: function (xhr) {
                                    $.confirm({
                                        title: 'Error',
                                        content: 'Something went wrong. Please try again.',
                                        type: 'red',
                                        buttons: {
                                            ok: {
                                                btnClass: 'btn-danger'
                                            }
                                        }
                                    });
                                    console.error(xhr);
                                },
                                complete: function () {
                                    // Restore button
                                    $btn.prop('disabled', false).html('Add Investment');
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
        });

        $(document).on('click', '.openAddInvestment', function () {
            $.confirm({
                title: 'Add Investment',
                columnClass: 'large',
                type: 'blue',
                content: `
                    <div class="mb-2">
                        <label>Admin Name</label>
                        <input class="form-control" value="{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}" disabled>
                    </div>

                    <div class="mb-2">
                        <label>Amount</label>
                        <input type="number" step="0.01" class="form-control executive_add_amount">
                    </div>

                    <div class="mb-2">
                        <label>Date</label>
                        <input class="form-control" value="{{ date('Y-m-d') }}" disabled>
                    </div>

                    <div class="mb-2">
                        <label>Remarks</label>
                        <textarea class="form-control executive_add_remarks"></textarea>
                    </div>

                    <div class="mb-2">
                        <label>Category</label>
                        <select class="form-select executive_add_category">
                            <option value="">-- Select --</option>
                            <option value="1">Lending Fund</option>
                            <option value="2">Shared Fund</option>
                        </select>
                    </div>
                `,
                buttons: {
                    add: {
                        text: 'Add',
                        btnClass: 'btn-primary executive_add_investment',
                        action: function () {
                            return false;
                        }
                    },
                    cancel: {
                        text: 'Cancel',
                        btnClass: 'btn-secondary'
                    }
                }
            });
        });

        $(document).off('click', '.openWithdraw_submit').on('click', '.openWithdraw_submit', function (e) {
            e.preventDefault();

            const $btn = $(this);
            let hasError = false;

            const $amount   = $('.openWithdraw_amount');
            const $remarks  = $('.openWithdraw_remarks');
            const $category = $('.openWithdraw_category');

            // Reset validation
            $amount.add($remarks).add($category).removeClass('is-invalid');

            // Validation
            if (!$amount.val().trim()) {
                $amount.addClass('is-invalid');
                hasError = true;
            }

            if (!$category.val()) {
                $category.addClass('is-invalid');
                hasError = true;
            }

            if (hasError) {
                $.confirm({
                    title: 'Validation Error',
                    content: 'Please complete all required fields.',
                    type: 'red',
                    buttons: {
                        ok: {
                            btnClass: 'btn-danger',
                            action: function () {
                                $('.is-invalid:first').focus();
                            }
                        }
                    }
                });
                return;
            }

            // Confirmation before submit
            $.confirm({
                title: 'Confirm Fund Withrawal',
                content: 'Are you sure you want to withraw this amount?',
                type: 'blue',
                buttons: {
                    confirm: {
                        text: 'Yes, Add',
                        btnClass: 'btn-primary',
                        action: function () {

                            // Disable button to prevent double submit
                            $btn.prop('disabled', true).html(
                                '<span class="spinner-border spinner-border-sm"></span> Saving...'
                            );

                            $.ajax({
                                url: '/executive/add_investment',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    amount: $amount.val(),
                                    remarks: $remarks.val(),
                                    category: $category.val()
                                },
                                success: function (res) {

                                    $.confirm({
                                        title: 'Success',
                                        content: 'Investment has been added successfully.',
                                        type: 'green',
                                        buttons: {
                                            ok: {
                                                btnClass: 'btn-success'
                                            }
                                        }
                                    });

                                    // Reset fields
                                    $amount.val('');
                                    $remarks.val('');
                                    pull_data();

                                },
                                error: function (xhr) {
                                    $.confirm({
                                        title: 'Error',
                                        content: 'Something went wrong. Please try again.',
                                        type: 'red',
                                        buttons: {
                                            ok: {
                                                btnClass: 'btn-danger'
                                            }
                                        }
                                    });
                                    console.error(xhr);
                                },
                                complete: function () {
                                    // Restore button
                                    $btn.prop('disabled', false).html('Add Investment');
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
        });

        $(document).on('click', '.openWithdraw', function () {
        $.confirm({
            title: 'Withdraw Funds',
            type: 'red',
            content: `
                <input class="form-control mb-2" value="{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}" disabled>

                <input type="number" class="form-control mb-2 openWithdraw_amount" placeholder="Amount">

                <input class="form-control mb-2 openWithdraw_remarks" placeholder="Reason">

                <select class="form-select openWithdraw_category">
                    <option value="3">Expense</option>
                    <option value="4">Personal</option>
                </select>
            `,
            buttons: {
                submit: {
                    text: 'Submit',
                    btnClass: 'btn-danger openWithdraw_submit',
                    action: function () {
                        // Add your AJAX here if needed
                        console.log({
                            amount: $('.withdraw_amount').val(),
                            reason: $('.withdraw_reason').val(),
                            type: $('.withdraw_type').val()
                        });
                    }
                },
                cancel: {
                    btnClass: 'btn-secondary'
                }
            }
        });
    });
});
</script>

</body>
</html>
