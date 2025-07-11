$(function () {
    const userId = $('#gb_user_id').val();
    let loan_applicantion_id = null;


    let currentStep = 'precheck';
    let formData = {
        referralType: '',
        loanAmount: 5000,
        tenure: 1
    };

    if (userId) {
        $.ajax({
            url: `/borrower/fetch-income/${userId}`,
            method: 'GET',
            success: function (res) {
                loan_application_id = res.loan_application_id;

                $('#occupation').val(res.occupation);
                $('#income').val(res.income);
                $(`input[name="employmentStatus"][value="${res.employment_status}"]`).prop('checked', true).prop('disabled', false);

                if (res.purpose_of_loan) $('#la_purpose').val(res.purpose_of_loan);
                if (res.referral) $(`input[name="referralType"][value="${res.referral}"]`).prop('checked', true);

                // 👇 Standard Referral Fields Only
                if (res.loan_amount) {
                    $('#loanAmountSlider').val(parseFloat(res.loan_amount));
                    $('#loan-amount-display').text(parseFloat(res.loan_amount).toLocaleString());
                    formData.loanAmount = parseFloat(res.loan_amount); // update formData too
                }

                if (res.loan_tenure) $('#standardTenure').val(res.loan_tenure);

                if (res.interest_rate) {
                    const percent = parseFloat(res.interest_rate) * 100;
                    $('.la_loan_interest').text(`${percent}%`);
                }

                if (res.total_amount) {
                    $('#summary-total').text(parseFloat(res.total_amount).toLocaleString());
                }

                // Recalculate loan summary
                updateLoanSummary();
            }
            ,
            error: function () {
                console.warn('No income data found for this user.');
            }
        });
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
        let load_step = 0;
        if (currentStep === "precheck") {
            load_step = 1;
        } else if (currentStep === "eligibility") {
            load_step = 2;
        } else if (currentStep === "full-loan-application") {
            load_step = 3;
        }

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
                load_step: load_step
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


    // Final application submit
    $(document).on('click', '.la_submit_final_application', function () {
        alert("Thank you for your loan application! We are currently reviewing your request. Our team will be in touch with you shortly for a brief interview to finalize the process. Please expect a call soon.");
    });

    // Terms checkbox
    $(document).on('change', '.la_terms_checkbox', function () {
        $('#submitFinalApplication').prop('disabled', !$(this).prop('checked'));
    });

    // File previews
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
                        const $img = $('<img>').attr('src', e.target.result);
                        $preview.append($img);
                    }
                    reader.readAsDataURL(file);
                } else {
                    $preview.html('<p class="text-muted">File preview not available (non-image).</p>');
                }
            });
        });
    }

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