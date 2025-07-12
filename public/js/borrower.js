$(function () {
    let current_routes = window.location.pathname;
    const userId = $('#gb_user_id').val();
    let loan_applicantion_id = null;


    let currentStep = 'precheck';
    let formData = {
        referralType: '',
        loanAmount: 5000,
        tenure: 1
    };
    if (current_routes === '/apply-loan') {
        if (userId) {
            $.ajax({
                url: `/borrower/fetch-income/${userId}`,
                method: 'GET',
                success: function (res) {
                    loan_application_id = res.loan_application_id;
                    // auto loads
                    $('#occupation').val(res.occupation);
                    $('#income').val(res.income);
                    $(`input[name="employmentStatus"][value="${res.employment_status}"]`).prop('checked', true).prop('disabled', false);
                    $('.c-applicant-name-1').text(res.fullname);
                    $('.c-applicant-position-1').text(res.occupation);

                    // 1st step
                    if (res.purpose_of_loan) $('#la_purpose').val(res.purpose_of_loan);
                    if (res.referral) $(`input[name="referralType"][value="${res.referral}"]`).prop('checked', true);

                    // 2nd step
                    if (res.loan_amount) {
                        $('#loanAmountSlider').val(parseFloat(res.loan_amount));
                        $('#loan-amount-display').text(parseFloat(res.loan_amount).toLocaleString());
                        formData.loanAmount = parseFloat(res.loan_amount);
                    }

                    if (res.loan_tenure) $('#standardTenure').val(res.loan_tenure);

                    if (res.interest_rate) {
                        const percent = parseFloat(res.interest_rate) * 100;
                        $('.la_loan_interest').text(`${percent}%`);
                    }

                    if (res.total_amount) {
                        $('#summary-total').text(parseFloat(res.total_amount).toLocaleString());
                    }

                    // Final step
                    if (res.payslip_img) {
                        $('#payslipPreview').html(`<a href="/storage/${res.payslip_img}" target="_blank">View Payslip</a>`);
                    }

                    if (res.bank_name) {
                        $('.la_bank_name').val(res.bank_name);
                    }

                    if (res.account_number) {
                        $('.la_account_number').val(res.account_number);
                    }

                    if (res.upload_qr_code_img) {
                        $('#qrPreview').html(`<img src="/storage/${res.upload_qr_code_img}" class="img-thumbnail" style="max-width:150px;">`);
                    }

                    if (res.government_type_id) {
                        $('.la_government_id').val(res.government_type_id);
                    }

                    if (res.government_id_img) {
                        $('#govIdPreview').html(`<img src="/storage/${res.government_id_img}" class="img-thumbnail" style="max-width:150px;">`);
                    }

                    if (res.billing_statement_img) {
                        $('#billingPreview').html(`<a href="/storage/${res.billing_statement_img}" target="_blank">View Billing Statement</a>`);
                    }

                    if (res.signature_img) {
                        const signatureURL = `/storage/${res.signature_img}`;

                        const $signatureTarget = $('.signature-target');

                        // Clear the placeholder content
                        $signatureTarget.empty();

                        // Mark as filled
                        $signatureTarget.addClass('filled');

                        // Create image element
                        const img = `<div class="position-relative d-inline-block">
                                    <img src="${signatureURL}" alt="Signature" class="la_signature_img">
                                </div>`;

                        // Add download button
                        const downloadBtn = `<a href="${signatureURL}" 
                            download="signature.png" 
                            class="signature-download-btn position-absolute"
                            onclick="event.stopPropagation();" 
                            style="top: 0.25rem; right: 0.25rem;" 
                            data-bs-toggle="tooltip"
                            title="Download Signature">
                            <i class="bi bi-download fs-5"></i>
                        </a>`;

                        // Append to target
                        $signatureTarget.append(img);
                        $signatureTarget.append(downloadBtn);
                    }


                    updateLoanSummary();
                },
                error: function () {
                    console.warn('No income data found for this user.');
                }
            });
        }
    }

    // la_go_home
    $(document).on('click', '.la_back_step', function () {
        if (currentStep === "eligibility") {
            showStep('precheck');
            $('.la_go_home').removeClass('d-none');
            $(this).addClass('d-none');
        } else if (currentStep === "full-loan-application") {
            showStep('eligibility');
        }
    });

    // Sidebar toggle for mobile
    $(document).on('click', '#la_sidebar_toggle', function () {
        $('#sidebar').toggleClass('show');
    });

    // Click outside sidebar on mobile
    $(document).on('click', function (e) {
        if ($(window).width() <= 991.98) {
            const sidebar = $('#sidebar');
            const sidebarToggle = $('#la_sidebar_toggle');
            if (!sidebar.is(e.target) && sidebar.has(e.target).length === 0 &&
                !sidebarToggle.is(e.target) && sidebarToggle.has(e.target).length === 0) {
                sidebar.removeClass('show');
            }
        }
    });

    // Resize handler to reset sidebar
    $(window).on('resize', function () {
        if ($(window).width() > 991.98) {
            $('#sidebar').removeClass('show');
        }
    });

    // Handle referral type change
    $(document).on('change', 'input[name="referralType"]', function () {
        const val = $(this).val();
        formData.referralType = val;

        if (val === 'admin') {
            $('#referral-code-section').show();
        } else {
            $('#referral-code-section').hide();
        }
    });

    function showStep(step) {
        $('.step-content').removeClass('active');
        $('#' + step + '-step').addClass('active');
        currentStep = step;
    }

    function updateLoanSummary() {
        const amount = formData.loanAmount;
        const tenure = parseInt($('#standardTenure').val());
        const total = amount * 1.05;
        $('#summary-amount').text(amount.toLocaleString());
        $('#summary-tenure').text(tenure);
        $('#summary-total').text(Math.round(total).toLocaleString());
        formData.tenure = tenure;
    }

    function updateAdminLoanSummary() {
        const amount = parseInt($('#customAmount').val()) || 0;
        const tenure = parseInt($('#adminTenure').val());
        const total = amount * 1.05;
        $('#admin-summary-amount').text(amount.toLocaleString());
        $('#admin-summary-tenure').text(tenure);
        $('#admin-summary-total').text(Math.round(total).toLocaleString());
    }

    // Admin loan input & dropdown
    $(document).on('input', '#customAmount', updateAdminLoanSummary);
    $(document).on('change', '#adminTenure', updateAdminLoanSummary);

    // Loan amount slider
    $(document).on('input', '.la_loan_amount_slider', function () {
        const amount = parseInt(this.value);
        $(this).val(amount);
        formData.loanAmount = amount;
        $('#loan-amount-display').text(amount.toLocaleString());
        updateLoanSummary();
    });

    // Standard loan tenure
    $(document).on('change', '.la_standard_tenure', function () {
        updateLoanSummary();
    });

    $(document).on('click', '.la_submit_precheck', function () {
        const userId = $('#gb_user_id').val();
        const purpose = $('#la_purpose').val();
        const referral = $('input[name="referralType"]:checked').val() || null;

        // Optional: you can skip AJAX if both purpose and referral are empty
        if (!purpose && !referral) {
            proceedToEligibility();
            return;
        }

        $.ajax({
            url: '/borrower/save-precheck',
            method: 'POST',
            data: {
                user_id: userId,
                purpose_of_loan: purpose,
                referral: referral,
                load_step: 1
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // add CSRF if needed
            },
            beforeSend: function () {
                proceedToEligibility();
            },
            success: function (r) {
                loan_applicantion_id = r.loan_applicantion_id;
            },
            error: function () {
                console.error('Failed to save precheck data.');
                proceedToEligibility(); // still continue even if saving failed
            }
        });
    });

    function proceedToEligibility() {
        showStep('loading');
        setTimeout(() => {
            showStep('eligibility');
            $('.la_go_home').addClass('d-none');
            $('.la_back_step').removeClass('d-none');
            if (formData.referralType === 'admin') {
                $('#admin-result').show();
                $('#standard-result').hide();
            } else {
                $('#admin-result').hide();
                $('#standard-result').show();
                updateLoanSummary();
            }
        }, 3000);
    }


    $(document).on('click', '.la_proceed_loan', function () {
        const loanAmount = formData.loanAmount; // from slider
        const loanTenure = parseInt($('#standardTenure').val()) || 0;

        // Get interest from DOM span (e.g. "5%") and convert to decimal
        const interestText = $('.la_loan_interest').text().trim();
        const interestRate = parseFloat(interestText.replace('%', '')) / 100;

        const totalAmount = loanAmount + (loanAmount * interestRate);

        if (!loan_applicantion_id) {
            console.error('Missing loan_application_id');
            return;
        }

        $.ajax({
            url: '/borrower/update-loan-details',
            method: 'POST',
            data: {
                loan_application_id: loan_applicantion_id,
                load_step: 2,
                loan_amount: loanAmount.toFixed(2),
                loan_tenure: loanTenure,
                interest_rate: interestRate.toFixed(3),
                total_amount: totalAmount.toFixed(2)
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                if (res.success) {
                    showStep('full-loan-application');
                }
            },
            error: function () {
                alert('Failed to update loan details.');
            }
        });
    });


    $(document).on('click', '.la_submit_final_application', function () {
        console.log({
            id: loan_applicantion_id,
            bank: $('.la_bank_name').val(),
            signature: $('.signature-filled img').attr('src'),
            payslip: $('#payslipInput')[0].files[0],
        });
        const formData = new FormData();

        formData.append('loan_application_id', loan_applicantion_id);
        formData.append('load_step', 3);
        formData.append('bank_name', $('.la_bank_name').val());
        formData.append('account_number', $('.la_account_number').val());
        formData.append('government_type_id', $('.la_government_id').val());

        // File inputs
        formData.append('payslip_img', $('#payslipInput')[0].files[0]);
        formData.append('qr_code_img', $('#qrInput')[0].files[0]);
        formData.append('government_id_img', $('#govIdInput')[0].files[0]);
        formData.append('billing_statement_img', $('#billingInput')[0].files[0]);

        // Signature (base64 from <img src>)
        const signatureBase64 = $('.signature-filled img').attr('src');
        formData.append('signature_img', signatureBase64);

        $.ajax({
            url: '/borrower/final-submit',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                if (res.success) {
                    window.location.href = "/loan-success";
                } else {
                    alert(res.message || "Submission failed.");
                }
            },
            error: function (xhr) {
                alert("Something went wrong during submission.");
                console.log(xhr.responseText);
            }
        });
    });

    // Terms checkbox
    $(document).on('change', '.la_terms_checkbox', function () {
        $('#submitFinalApplication').prop('disabled', !$(this).prop('checked'));
    });

    function previewImage(inputId, previewContainerId) {
        const $input = $('#' + inputId);
        const $preview = $('#' + previewContainerId);

        if (!$input.length || !$preview.length) return;

        $input.on('change', function () {
            $preview.empty();
            const files = this.files;

            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const $img = $('<img>')
                            .attr('src', e.target.result)
                            .addClass('h-32 w-auto object-contain mx-auto rounded-md shadow'); // 👈 SAKTO LANG

                        $preview.append($img);
                    };
                    reader.readAsDataURL(file);
                } else {
                    $preview.html('<p class="text-muted">File preview not available (non-image).</p>');
                }
            });
        });
    }


    // Apply to each input-preview pair
    previewImage('payslipInput', 'payslipPreview');
    previewImage('qrInput', 'qrPreview');
    previewImage('govIdInput', 'govIdPreview');
    previewImage('signatureInput', 'signaturePreview');
    previewImage('billingInput', 'billingPreview');


    // Init on load
    updateLoanSummary();
    updateAdminLoanSummary();
});

//Set current time
$(document).ready(function () {
    function updateDateTime() {
        const now = new Date();
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };
        const formattedDateTime = now.toLocaleString('en-US', options);
        $('#current-date').text(formattedDateTime);
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
});