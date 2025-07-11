$(function () {
    let currentStep = 'precheck';
    let formData = {
        referralType: '',
        loanAmount: 5000,
        tenure: 1
    };

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
        formData.loanAmount = amount;
        $('#loan-amount-display').text(amount.toLocaleString());
        updateLoanSummary();
    });

    // Standard loan tenure
    $(document).on('change', '.la_standard_tenure', function () {
        updateLoanSummary();
    });

    // Submit precheck
    $(document).on('click', '.la_submit_precheck', function () {
        showStep('loading');
        setTimeout(() => {
            showStep('eligibility');
            if (formData.referralType === 'admin') {
                $('#admin-result').show();
                $('#standard-result').hide();
            } else {
                $('#admin-result').hide();
                $('#standard-result').show();
                updateLoanSummary();
            }
        }, 3000);
    });

    // Proceed with loan
    $(document).on('click', '.la_proceed_loan', function () {
        showStep('full-loan-application');
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
