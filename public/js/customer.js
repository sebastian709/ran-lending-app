// Event handler for View More button
$(document).on('click', '.cp-view-more', function () {
    let userID = $(this).attr('data-user_id');
    let tabType = $(this).attr('data-tab_type');
    const formData = new FormData();
    formData.append('user_id', userID);
    formData.append('tab_type', tabType);

    $.ajax({
        url: '/admin/customer/cpas-view-more-info',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            // Sample static data
            var profileInfo = `<table class="table table-bordered table-sm">
                                    <tr><th>Date Joined</th><td>${res.user_info.created_at}</td></tr>
                                    <tr><th>Address</th><td>${res.user_info.address}</td></tr>
                                    <tr><th>Mobile</th><td>${res.user_info.contactno}</td></tr>
                                    <tr><th>Email</th><td>${res.user_info.email}</td></tr>
                                    <tr><th>Total Loans Taken</th><td>${res.total_loan_taken}</td></tr>
                                    <tr><th>Outstanding Balance</th><td>${res.outstanding_balance}</td></tr>
                                    <tr><th>Behavior Score</th><td>8.5 / 10</td></tr>
                                </table>`;

            // let loanHistoryTr = "";

            // if (res.loan_applications.length == 1) {
            //     $.each(res.loan_applications, function (key, val) {
            //         loanHistoryTr += `<tr>
            //                 <td>${val.id}</td>
            //                 <td>${val.created_at}</td>
            //                 <td>${val.loan_amount}</td>
            //                 <td>${val.loan_tenure}</td>
            //                 <td>${val.referral}</td>
            //                 <td>${val.loan_status_by_name}</td>
            //                 <td>${val.outstanding_balance ?? '-'}</td>
            //                 <td>${val.last_payment ?? '-'}</td>
            //                 <td>${val.next_due ?? '-'}</td>
            //                 <td>${val.score ?? '-'}</td>
            //               </tr>`;
            //     });
            // } else {
            //     loanHistoryTr += `<tr><td class="text-center" colspan="10">No Data</td></tr>`;
            // }

            let loanHistoryTr = `<tr>
                                    <td>Loan ID</td>
                                    <td>${res.loan_applications.id ? 'LN-' + String(res.loan_applications.id).padStart(5, '0') : '-'}</td>
                                </tr>
                                <tr>
                                    <td>Date Requested</td>
                                    <td>${res.loan_applications.created_at || '-'}</td>
                                </tr>
                                <tr>
                                    <td>Loan Amount</td>
                                    <td>${res.loan_applications.loan_amount || '-'}</td>
                                </tr>
                                <tr>
                                    <td>Tenure</td>
                                    <td>${res.loan_applications.loan_tenure || '-'}</td>
                                </tr>
                                <tr>
                                    <td>Referral</td>
                                    <td>${res.loan_applications.referral || '-'}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>${res.loan_applications.loan_status_by_name || '-'}</td>
                                </tr>
                                <tr>
                                    <td>Outstanding Balance</td>
                                    <td>${res.outstanding_balance || '-'}</td>
                                </tr>
                                <tr>
                                    <td>Last Payment</td>
                                    <td>${res.last_payments_date || '-'}</td>
                                </tr>
                                <tr>
                                    <td>Next Due</td>
                                    <td>${res.next_payment_date || '-'}</td>
                                </tr>
                                <tr>
                                    <td>Score</td>
                                    <td>${res.loan_applications.score || '-'}</td>
                                </tr>`;

                                    // <tr>
                                    //     <th>Loan ID</th>
                                    //     <th>Date Requested</th>
                                    //     <th>Loan Amount</th>
                                    //     <th>Tenure</th>
                                    //     <th>Referral</th>
                                    //     <th>Status</th>
                                    //     <th>Outstanding Balance</th>
                                    //     <th>Last Payment</th>
                                    //     <th>Next Due</th>
                                    //     <th>Score</th>
                                    // </tr>

            var loanHistory = `<table class="table table-bordered table-sm">
                                    ${loanHistoryTr}
                                </table>`;

            let paymentHistoryTr = "";

            if (res.payment_history.length > 0) {
                $.each(res.payment_history, function (key, val) {
                    paymentHistoryTr += `<tr>
                                            <td>${val.reference_code || "-"}</td>
                                            <td>${val.payment_date || "-"}</td>
                                            <td>${val.verification_date || "-"}</td>
                                            <td>${val.payment_method || "-"}</td>
                                            <td>${val.bank_account || "-"}</td>
                                            <td>
                                                <button class="btn btn-sm btn-info view-payment-details" data-payment_id="${val.payment_id}">View More</button>
                                            </td>
                                        </tr>`;
                });
            } else {
                paymentHistoryTr += `<tr><td class="text-center" colspan="10">No Data</td></tr>`;
            }

            var paymentHistory = `<table class="table table-bordered table-sm">
                                        <tr>
                                            <th>Ref No.</th>
                                            <th>Payment Date</th>
                                            <th>Verification Date</th>
                                            <th>Method</th>
                                            <th>Bank Acc.</th>
                                            <th>Action</th>
                                        </tr>
                                        ${paymentHistoryTr}
                                    </table>`;


            $.confirm({
                title: 'Borrower Information',
                columnClass: 'col-md-10 col-md-offset-1',
                content: `
                            <div class="tabs">
                                <div class="btn-group mb-3">
                                    <button class="btn btn-outline-primary tab-btn active" data-tab="profile">Profile Information</button>
                                    <button class="btn btn-outline-primary tab-btn" data-tab="loan">Loan History</button>
                                    <button class="btn btn-outline-primary tab-btn" data-tab="payment">Payment History</button>
                                </div>
                                <div class="tab-body"></div>
                            </div>
                        `,
                buttons: {
                    close: {
                        text: 'Close',
                        btnClass: 'btn-secondary'
                    }
                },
                onContentReady: function () {
                    var jc = this;
                    // Default load Profile Info
                    jc.$content.find('.tab-body').html(profileInfo);

                    // Tab click event
                    jc.$content.on('click', '.tab-btn', function () {
                        var tab = $(this).data('tab');
                        var body = jc.$content.find('.tab-body');

                        // remove active sa lahat
                        jc.$content.find('.tab-btn').removeClass('active');
                        // add active sa current
                        $(this).addClass('active');

                        body.empty();
                        if (tab === 'profile') body.append(profileInfo);
                        if (tab === 'loan') body.append(loanHistory);
                        if (tab === 'payment') body.append(paymentHistory);
                    });
                }
            });
        },
        error: function (xhr) {
            const response = xhr.responseJSON;
            if (response && response.errors) {
                Object.values(response.errors).forEach(msg => toastr.error(msg));
            } else {
                toastr.error('Something went wrong.');
            }
        }
    });
});

// Event for "View More" button sa Payment History
$(document).on('click', '.view-payment-details', function () {
    let payment_id = $(this).attr('data-payment_id');
    const formData = new FormData();
    formData.append('payment_id', payment_id);

    $.ajax({
        url: '/admin/customer/cpa-payment-details',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            var paymentDetails = `<table class="table table-bordered table-sm">
                                        <tr><th>Amount Paid</th><td>₱${res[0].amount_sent || 0}</td></tr>
                                        <tr><th>Breakdown</th><td>₱${res[0].principal || 0} Principal / ₱${res[0].interest || 0} Interest / ₱${res[0].penalty || 0} Penalty</td></tr>
                                        <tr><th>Coverage Month(s)</th><td>${res[0].coverage_month || '-'}</td></tr>
                                        <tr><th>Internal Remarks</th><td>${res[0].remarks || '-'}</td></tr>
                                        <tr><th>Proof of Payment</th><td><button class="btn btn-primary btn-sm"><i class="ri-eye-line"></i> View Attachment</button></td></tr>
                                  </table>`;

            $.alert({
                title: 'Payment Details - ' + res[0].reference_code,
                content: paymentDetails,
                columnClass: 'col-md-6 col-md-offset-3',
                buttons: {
                    ok: {
                        text: 'Close',
                        btnClass: 'btn-primary'
                    }
                }
            });
        },
        error: function (xhr) {
            const response = xhr.responseJSON;
            if (response && response.errors) {
                Object.values(response.errors).forEach(msg => toastr.error(msg));
            } else {
                toastr.error('Something went wrong.');
            }
        }
    });
});
