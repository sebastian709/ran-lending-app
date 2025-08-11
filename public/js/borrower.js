$(function () {
    let current_routes = window.location.pathname;
    const userId = $('#gb_user_id').val();
    let loan_application_id = null;
    let gb_refferal_type = "friend";

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
        const month = amount * 1.05;
        const interest = month - amount;
        const total_interest = interest * tenure;
        const total = total_interest + amount;
        $('#summary-amount').text(amount.toLocaleString());
        $('#summary-tenure').text(tenure);
        $('#summary-total').text(Math.round(total).toLocaleString());
        formData.tenure = tenure;
    }

    function updateAdminLoanSummary() {
        const amount = parseInt($('#customAmount').val()) || 0;
        const tenure = parseInt($('#adminTenure').val());
        const month = amount * 1.05;
        const total = month * tenure;
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
        const occupation = $('#occupation').val();
        const income = $('#income').val();
        const employmentStatus = $('input[name="employmentStatus"]:checked').val();

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
                occupation: occupation,
                income: income,
                employmentStatus: employmentStatus,
                load_step: 1
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // add CSRF if needed
            },
            beforeSend: function () {
                proceedToEligibility();
            },
            success: function (r) {
                loan_application_id = r.loan_application_id;
                gb_refferal_type = r.referral_type;

                console.log(gb_refferal_type, loan_application_id);
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


    $(document).on('click', '.la_proceed_loan, .la_ad_proceed_loan', function () {
        const loanAmountRaw = gb_refferal_type == 'admin' ? $('#customAmount').val() : formData.loanAmount;
        const loanAmount = parseFloat(loanAmountRaw) || 0;
        const loanTenure = gb_refferal_type == 'admin' ? $('#adminTenure').val() : parseInt($('#standardTenure').val()) || 0;
        const interestText = gb_refferal_type == 'admin' ? $('.la_ad_loan_interest').text().trim() : $('.la_loan_interest').text().trim();


        const interestRate = parseFloat(interestText.replace('%', '')) / 100;

        const totalAmount = loanAmount + (loanAmount * interestRate);

        // if (!loan_application_id) {
        //     console.error('Missing loan_application_id', loan_application_id);
        //     return;
        // }

        $.ajax({
            url: '/borrower/update-loan-details',
            method: 'POST',
            data: {
                loan_application_id: loan_application_id,
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
                    if (res.loan_application_id) {
                        loan_application_id = res.loan_application_id; // store new one
                    }
                    showStep('full-loan-application');
                }
            },
            error: function () {
                alert('Failed to update loan details.');
            }
        });
    });


    $(document).on('click', '.la_submit_final_application', function () {
        const formData = new FormData();

        formData.append('loan_application_id', loan_application_id);
        formData.append('load_step', 3);
        formData.append('bank_name', $('.la_bank_name').val());
        formData.append('account_number', $('.la_account_number').val());
        formData.append('government_type_id', $('.la_government_id').val());

        // File inputs
        formData.append('payslip_img', $('#payslipInput')[0]?.files[0]);
        formData.append('qr_code_img', $('#qrInput')[0]?.files[0]);
        formData.append('government_id_img', $('#govIdInput')[0]?.files[0]);
        formData.append('billing_statement_img', $('#billingInput')[0]?.files[0]);

        // Signature
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Submitted!',
                        text: res.message || "Your application has been submitted.",
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "/loan-success";
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Submission Failed',
                        text: res.message || "Something went wrong.",
                    });
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();

                        for (const field in errors) {
                            const message = errors[field][0];
                            const selector = getFieldSelector(field);
                            const $input = $(selector);
                            $input.addClass('is-invalid');
                            $input.after(`<div class="invalid-feedback">${message}</div>`);
                        }

                        Swal.fire({
                            icon: 'warning',
                            title: 'Validation Error',
                            text: 'Please correct the highlighted fields.',
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Something went wrong. Please try again later.',
                    });
                    console.error(xhr.responseText);
                }
            }
        });
    });

    function getFieldSelector(field) {
        const map = {
            bank_name: '.la_bank_name',
            account_number: '.la_account_number',
            government_type_id: '.la_government_id',
            payslip_img: '#payslipInput',
            // qr_code_img: '#qrInput',
            government_id_img: '#govIdInput',
            billing_statement_img: '#billingInput',
            signature_img: '.signature-filled img' // visual only
        };
        return map[field] || `[name="${field}"]`;
    }

    // Terms checkbox
    $(document).on('change', '.la_terms_checkbox', function () {
        const img_checker = $('.signature-wrapper img').attr('src');

        if ($(this).is(':checked')) {
            if (img_checker) {
                $('#submitFinalApplication').prop('disabled', false);
            }
        } else {
            $('#submitFinalApplication').prop('disabled', true);
        }
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


$(document).ready(function () { // profile page
    let $form = $('#profile-form');
    let $inputs = $form.find('input');
    let $select = $form.find('select');
    let $changePhoto = $('#change-picture-btn');
    let $editBtn = $('#edit-profile-btn');
    let $updateControls = $('#update-controls');
    let $cancelBtn = $('#cancel-edit-btn');

    // Enable edit
    $editBtn.on('click', function () {
        $inputs.prop('disabled', false);
        $select.prop('disabled', false);
        $updateControls.removeClass('d-none');
        $changePhoto.removeAttr('hidden');
    });

    // Cancel edit
    $cancelBtn.on('click', function () {
        $inputs.each(function () {
            this.value = this.defaultValue;
        }).prop('disabled', true);
        $select.each(function () {
            const $el = $(this);
            $el.find('option').each(function () {
                if (this.defaultSelected) {
                    $el.val(this.value);
                }
            });
        }).prop('disabled', true);
        $changePhoto.attr('hidden', ' ');
        $updateControls.addClass('d-none');
    });

    // handle submit
    $form.on('submit', function (e) {
        e.preventDefault();

        $.confirm({
            title: 'Confirm Update',
            content: 'Are you sure you want to save these changes? They will be reflected on your account.',
            buttons: {
                cancel: function () { },
                confirm: {
                    text: 'Save',
                    btnClass: 'btn-primary',
                    action: function () {
                        const formData = new FormData($form[0]); // include all form fields and file

                        $.ajax({
                            url: '/update-profile',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (res) {
                                if (res.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Updated!',
                                        text: 'Profile updated successfully!',
                                        timer: 2000,
                                        showConfirmButton: false,
                                        timerProgressBar: true,
                                        didClose: () => {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: 'Error updating profile.',
                                        timer: 2000,
                                        showConfirmButton: false,
                                        timerProgressBar: true
                                    });
                                }
                            },
                            error: function () {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Server Error!',
                                    text: 'Something went wrong while saving.',
                                    timer: 2000,
                                    showConfirmButton: false,
                                    timerProgressBar: true
                                });
                            }
                        });
                    }
                }
            }
        });
    });


    // Profile picture preview
    $('#change-picture-btn').on('click', () => {
        $('#profile-picture-input').click();
    });

    $('#profile-picture-input').on('change', function () {
        const reader = new FileReader();
        reader.onload = function (e) {
            $('#profile-picture-preview').attr('src', e.target.result);
        };
        reader.readAsDataURL(this.files[0]);

        $("#profile-picture-preview").removeAttr('hidden')
        $(".user-avatar-profile-view").attr('hidden', ' ');
    });

    $('.close-profile-notif').on('click', function () {
        $(this).closest('.row').fadeOut(300, function () {
            $(this).remove();
        });
    });
});

$(function () {
    // Toggle password visibility
    $('.toggle-password').on('click', function () {
        const targetInput = $($(this).data('target'));
        const icon = $(this).find('i');
        const type = targetInput.attr('type') === 'password' ? 'text' : 'password';
        targetInput.attr('type', type);
        icon.toggleClass('ri-eye-line ri-eye-off-line');
    });

    // Live password validation
    $('#new_password').on('input', function () {
        const val = $(this).val();

        const criteria = {
            length: val.length >= 8,
            upperlower: /[a-z]/.test(val) && /[A-Z]/.test(val),
            number: /\d/.test(val),
            special: /[\W_]/.test(val),
        };

        $('#password-criteria li').each(function () {
            const key = $(this).data('criteria');
            if (criteria[key]) {
                $(this).addClass('valid').find('i')
                    .removeClass('ri-checkbox-blank-circle-line')
                    .addClass('ri-checkbox-circle-fill');
            } else {
                $(this).removeClass('valid').find('i')
                    .removeClass('ri-checkbox-circle-fill')
                    .addClass('ri-checkbox-blank-circle-line');
            }
        });
    });

    // Submit handler
    // Submit handler
    $(document).on('submit', '#change-password-form', function (e) {
        e.preventDefault();

        // Check if all criteria are marked as valid
        const allValid = $('#password-criteria li').length === $('#password-criteria li.valid').length;

        if (!allValid) {
            Swal.fire({
                icon: 'error',
                title: 'Weak Password',
                text: 'Please meet all the password requirements before submitting.',
            });
            return; // ⛔ prevent submit
        }

        const form = $(this);
        const actionUrl = form.data('action');
        const logoutUrl = form.data('logout');

        $.ajax({
            url: actionUrl,
            method: "POST",
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Password Changed',
                    text: response.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    if (response.logout) {
                        $('#logout-form').submit();
                    }
                });
            },
            error: function (xhr) {
                let errorMessage = "Something went wrong.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).map(arr => arr.join(', ')).join('\n');
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: errorMessage
                });
            }
        });
    });
});


// Loan list modal
$(document).ready(function () {
    $('.view-loan-btn').on('click', function () {
        const loanId = $(this).data('id');

        $.confirm({
            title: `<i class="bi bi-file-earmark-text me-2"></i> Loan Details`,
            content: `
                <div class="text-start fs-6">
                    <div class="mb-2"><strong>Loan ID:</strong> ${loanId}</div>
                    <div class="mb-2"><strong>Total Amount:</strong> <span class="text-success">₱50,000.00</span></div>
                    <div class="mb-2"><strong>Loan Tenure:</strong> 12 months</div>
                    <div class="mb-2"><strong>Date of Payment:</strong> July 30, 2025</div>
                    <div class="mb-2"><strong>Monthly Amount Due:</strong> ₱4,500.00</div>
                    <div class="mb-3"><strong>Penalty:</strong> ₱0.00</div>
                    <hr class="my-2">
                    <div class="mb-2"><strong>Total Payment:</strong> ₱54,000.00</div>
                    <div class="mb-1"><strong>Breakdown:</strong></div>
                    <ul class="ps-4">
                        <li>Principal: ₱50,000.00</li>
                        <li>Interest: ₱4,000.00</li>
                        <li>Penalty: ₱0.00</li>
                    </ul>
                </div>
            `,
            type: 'blue',
            columnClass: 'medium',
            icon: 'bi bi-info-circle-fill',
            buttons: {
                close: {
                    text: 'Close',
                    btnClass: 'btn-secondary',
                }
            }
        });
    });
});

(function () {
    const totalPages = 50;
    let currentPage = 1;
    const maxVisible = 5;

    function updateEntriesInfo(start, end, total) {
        $('.entries-info').text(`Showing ${start} to ${end} of ${total} entries`);
    }

    function createPageItem(text, page, disabled = false, active = false) {
        const li = $('<li>').addClass('page-item');
        if (disabled) li.addClass('disabled');
        if (active) li.addClass('active');

        const a = $('<a>')
            .addClass('page-link')
            .attr('href', '#')
            .attr('data-page', page)
            .html(text);

        li.append(a);
        return li;
    }

    function getVisiblePages(current, total, max) {
        const pages = [];

        if (total <= max + 2) {
            for (let i = 1; i <= total; i++) pages.push(i);
            return pages;
        }

        if (current <= max) {
            for (let i = 1; i <= max; i++) pages.push(i);
            pages.push('...');
            pages.push(total);
        } else if (current >= total - max + 1) {
            pages.push(1);
            pages.push('...');
            for (let i = total - max + 1; i <= total; i++) pages.push(i);
        } else {
            pages.push(1);
            pages.push('...');
            const middleStart = current - Math.floor(max / 2);
            const middleEnd = current + Math.floor(max / 2);
            for (let i = middleStart; i <= middleEnd; i++) pages.push(i);
            pages.push('...');
            pages.push(total);
        }

        return pages;
    }

    function renderPagination() {
        const $pagination = $('.pagination');
        $pagination.empty();

        // Previous button
        $pagination.append(createPageItem('&lt; Previous', 'prev', currentPage === 1));

        // Page numbers
        const pages = getVisiblePages(currentPage, totalPages, maxVisible);
        pages.forEach(function (item) {
            if (item === '...') {
                const li = $('<li>').addClass('page-item disabled')
                    .html('<span class="page-link">...</span>');
                $pagination.append(li);
            } else {
                const li = createPageItem(item, item, false, item === currentPage);
                $pagination.append(li);
            }
        });

        // Next button
        $pagination.append(createPageItem('Next &gt;', 'next', currentPage === totalPages));

        // Update entry info
        const start = (currentPage - 1) * 10 + 1;
        const end = Math.min(start + 9, 412);
        updateEntriesInfo(start, end, 412);
    }

    // Delegated event (your format)
    $(document).on('click', '.pagination .page-link', function (e) {
        e.preventDefault();
        const $this = $(this);
        const page = $this.data('page');

        if ($this.parent().hasClass('disabled') || $this.parent().hasClass('active')) return;

        if (page === 'prev') {
            if (currentPage > 1) currentPage--;
        } else if (page === 'next') {
            if (currentPage < totalPages) currentPage++;
        } else {
            currentPage = parseInt(page);
        }

        renderPagination();
    });

    // Initialize pagination immediately
    renderPagination();
})();





