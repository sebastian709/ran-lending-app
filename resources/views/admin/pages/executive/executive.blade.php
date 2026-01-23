@extends('admin.container')

@section('content')

<div class="container-fluid py-4">

    <!-- PAGE TITLE -->
    <div class="mb-4">
        <h3 class="fw-bold">Executive Investment <button class="btn btn-sm btn-primary money_status hidden">Hide</button></h3>
        <small class="text-muted">Admin Profile → Executive Investment</small>
    </div>

    <!-- ===================== -->
    <!-- 1. KEY METRICS -->
    <!-- ===================== -->
    <div class="row g-3 mb-4">
        <div class="col-md-3" title="Includes Interest and Penalty">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <h6>Total Fund</h6>
                    <h4>₱ <span class="total_fund">0.00</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <h6>Remaining Fund</h6>
                    <h4>₱ <span class="remaining_fund">0.00</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <h6>Ongoing Loan</h6>
                    <h4>₱ <span class="ongoing_fund">0.00</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <h6>Tithes</h6>
                    <h4>₱ <span class="tithes">0.00</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <h6>My Shared Fund</h6>
                    <h4>₱ <span class="shared_fund">0.00</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <h6>My Dividend</h6>
                    <h4>₱ <span class="dividend">0.00</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <h6>Divident %</h6>
                    <h4><span class="div_percent">0.00</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card shadow-sm">
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
            <table class="table table-striped table-bordered" id="investmentTable">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Notes</th>
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

@push('sb-scripts')
<script>
$(function() {

    function formatMoney(value, currency = 'PHP', locale = 'en-PH') {
        if (value === null || value === undefined || isNaN(value)) return '0.00';
        return new Intl.NumberFormat(locale, {
            currency: currency,
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(value);
    }

    function pull_data(){
        $('#investmentTable').DataTable().clear().destroy();
        $.ajax({
            url: '/executive/pull_data',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            success: function(res) {

                if (parseInt(res.data.hide_money.hide_money) === 1) {
                    $('.money_status').removeClass('hidden').text('Show Money');
                    $('.dividend,.total_fund,.shared_fund,.remaining_fund,.tithes,.ongoing_fund,.misc_fund,.div_percent').text('***.***');
                } else {

                    let div = res.data.shared_fund.amount / res.data.total_fund.amount;
                    let gain = ((res.data.data.interest + res.data.data.penalty) + res.data.withrawn_fund.amount) - res.data.data.tithes;

                    $('.money_status').removeClass('hidden').text('Hide Money');
                    $('.total_fund').text(formatMoney(res.data.total_fund.amount + res.data.data.misc ));
                    $('.shared_fund').text(formatMoney(res.data.shared_fund.amount));
                    $('.remaining_fund').text(formatMoney(res.data.data.remaining_money));
                    $('.tithes').text(formatMoney(res.data.data.tithes));
                    $('.ongoing_fund').text(formatMoney(res.data.data.remaining));
                    $('.misc_fund').text(formatMoney(gain));
                    $('.misc_fund').closest('.card-body').attr('title', formatMoney(res.data.data.interest + res.data.data.penalty));
                    $('.penalty_fund').text(formatMoney(res.data.data.penalty));
                    $('.dividend').text(formatMoney(div * gain));
                    $('.div_percent').text((div * 100).toFixed(0) + '%');
                }    

                let fund_management = '';
                $.each(res.data.fund_management, function (k, v) {
                    let btn = v.category == 1 
                        ? '<span class="btn btn-sm btn-primary" style="pointer-events:none">Add Investment</span>' 
                        : '<span class="btn btn-sm btn-danger" style="pointer-events:none">Withraw Fund</span>';

                    fund_management += `
                        <tr>
                            <td>${v.id}</td>
                            <td>${v.name}</td>
                            <td><b>₱ ${formatMoney(v.amount)}</b></td>
                            <td>${btn}</td>
                            <td>${v.remarks ?? 'N/A'}</td>
                            <td>${v.readable_date}</td>
                        </tr>
                    `;
                });
                $('#investmentTable tbody').append(fund_management);
                $('#investmentTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    dom: 'Bfrtip',
                    order: [[1, 'desc']],
                    columnDefs: [{ className: "dt-center", targets: "_all" }],
                    buttons: [
                        { extend: 'excelHtml5', title: 'Loan Report', className: 'btn' },
                        { extend: 'pdfHtml5', title: 'Loan Report', pageSize: 'A4', className: 'btn' }
                    ]
                });  
            }
        });
    }

    pull_data();

    // ADD INVESTMENT MODAL
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

            `,
            buttons: {
                add: { text: 'Add', btnClass: 'btn-primary executive_add_investment', action: function() { return false; } },
                cancel: { btnClass: 'btn-secondary' }
            }
        });
    });

    // ADD INVESTMENT AJAX
    $(document).on('click', '.executive_add_investment', function (e) {
        e.preventDefault();
        const $btn = $(this);
        let hasError = false;

        const $amount   = $('.executive_add_amount');
        const $remarks  = $('.executive_add_remarks');

        $amount.add($remarks).removeClass('is-invalid');

        if (!$amount.val().trim()) { $amount.addClass('is-invalid'); hasError = true; }

        if (hasError) {
            $.confirm({
                title: 'Validation Error',
                content: 'Please complete all required fields.',
                type: 'red',
                buttons: { ok: { btnClass: 'btn-danger', action: function() { $('.is-invalid:first').focus(); } } }
            });
            return;
        }

        $.confirm({
            title: 'Confirm Investment',
            content: 'Are you sure you want to add this investment?',
            type: 'blue',
            buttons: {
                confirm: {
                    text: 'Yes, Add',
                    btnClass: 'btn-primary',
                    action: function () {
                        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');

                        $.ajax({
                            url: '/executive/add_investment',
                            type: 'POST',
                            dataType: 'json',
                            data: { amount: $amount.val(), remarks: $remarks.val()},
                            success: function () {
                                $.confirm({ title: 'Success', content: 'Investment has been added successfully.', type: 'green', buttons: { ok: { btnClass: 'btn-success' } } });
                                $amount.val(''); $remarks.val('');
                                pull_data();
                            },
                            error: function (xhr) {
                                $.confirm({ title: 'Error', content: 'Something went wrong. Please try again.', type: 'red', buttons: { ok: { btnClass: 'btn-danger' } } });
                                console.error(xhr);
                            },
                            complete: function () { $btn.prop('disabled', false).html('Add Investment'); }
                        });
                    }
                },
                cancel: { text: 'Cancel', btnClass: 'btn-secondary' }
            }
        });
    });

    // WITHDRAWAL MODAL & AJAX
    $(document).on('click', '.openWithdraw', function () {
        $.confirm({
            title: 'Withdraw Funds',
            type: 'red',
            boxWidth: '400px',
            useBootstrap: true,
            content: `
                <input class="form-control mb-2" value="{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}" disabled>
                <input type="number" class="form-control mb-2 withdraw_amount" placeholder="Amount Deducted">
                <input class="form-control mb-2 withdraw_reason" placeholder="Reason">
            `,
            buttons: {
                submit: {
                    text: 'Submit',
                    btnClass: 'btn-danger',
                    action: function () {
                        const amount = parseFloat(this.$content.find('.withdraw_amount').val());
                        const reason = this.$content.find('.withdraw_reason').val().trim();
                        if (isNaN(amount)) { $.alert('Amount is required'); return false; }
                        if (!reason) { $.alert('Reason is required'); return false; }

                        $.confirm({
                            title: 'Confirm Withdrawal',
                            type: 'red',
                            content: `<b>Amount:</b> ${amount}<br><b>Reason:</b> ${reason}<br><br>Are you sure you want to proceed?`,
                            buttons: {
                                confirm: {
                                    text: 'Yes, Withdraw',
                                    btnClass: 'btn-danger',
                                    action: function () {
                                        $.ajax({
                                            url: '/executive/withraw_investment',
                                            method: 'POST',
                                            data: { amount, reason, _token: $('meta[name="csrf-token"]').attr('content') },
                                            success: function () {
                                                $.confirm({ title: 'Success', type: 'green', content: 'Withdrawal submitted successfully', buttons: { ok: { btnClass: 'btn-success' } } });
                                                pull_data();
                                            },
                                            error: function (xhr) {
                                                $.confirm({ title: 'Error', type: 'red', content: xhr.responseJSON?.message || 'Something went wrong', buttons: { ok: { btnClass: 'btn-danger' } } });
                                            }
                                        });
                                    }
                                },
                                cancel: { btnClass: 'btn-secondary' }
                            }
                        });
                        return false;
                    }
                },
                cancel: { btnClass: 'btn-secondary' }
            }
        });
    });

    // TOGGLE MONEY STATUS
    $(document).on('click', '.money_status', function (e) {
        e.preventDefault();
        $.ajax({ url: '/executive/money_status', type: 'POST', dataType: 'json', data: {}, complete: function() { pull_data(); } });
    });

});
</script>
@endpush
