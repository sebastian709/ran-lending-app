document.addEventListener('DOMContentLoaded', function () {
    let currentStep = 'precheck';
    let formData = {
        referralType: '',
        loanAmount: 5000,
        tenure: 1
    };

    // Sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('show');
        });
    }

    // Handle referral type selection
    document.querySelectorAll('input[name="referralType"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const referralCodeSection = document.getElementById('referral-code-section');
            if (this.value === 'admin') {
                referralCodeSection.style.display = 'block';
            } else {
                referralCodeSection.style.display = 'none';
            }
            formData.referralType = this.value;
        });
    });

    function submitPrecheck() {
        // Show loading step
        showStep('loading');

        // Simulate processing time
        setTimeout(() => {
            showStep('eligibility');

            // Show appropriate result based on referral type
            if (formData.referralType === 'admin') {
                document.getElementById('admin-result').style.display = 'block';
                document.getElementById('standard-result').style.display = 'none';
            } else {
                document.getElementById('admin-result').style.display = 'none';
                document.getElementById('standard-result').style.display = 'block';
                updateLoanSummary();
            }
        }, 3000);
    }

    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.step-content').forEach(content => {
            content.classList.remove('active');
        });

        // Show current step
        document.getElementById(step + '-step').classList.add('active');
        currentStep = step;
    }

    function updateLoanAmount(value) {
        formData.loanAmount = parseInt(value);
        document.getElementById('loan-amount-display').textContent = parseInt(value).toLocaleString();
        updateLoanSummary();
    }

    function updateLoanSummary() {
        const amount = formData.loanAmount;
        const tenure = parseInt(document.getElementById('standardTenure').value);
        const interestRate = 0.05; // 5%
        const total = amount * (1 + interestRate);

        document.getElementById('summary-amount').textContent = amount.toLocaleString();
        document.getElementById('summary-tenure').textContent = tenure;
        document.getElementById('summary-total').textContent = Math.round(total).toLocaleString();

        formData.tenure = tenure;
    }

    function proceedWithLoan() {
        // alert('Proceeding with loan application...');
        showStep('full-loan-application');
    }

    function goBackToDashboard() {
        // This would redirect back to dashboard
        alert('Redirecting back to dashboard...');
        // window.location.href = 'dashboard.html';
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (event) {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');

        if (window.innerWidth <= 991.98) {
            if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        }
    });

    // Handle window resize
    window.addEventListener('resize', function () {
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth > 991.98) {
            sidebar.classList.remove('show');
        }
    });

    // Initialize loan summary
    updateLoanSummary();

    function updateAdminLoanSummary() {
        const amount = parseInt(document.getElementById('customAmount').value) || 0;
        const tenure = parseInt(document.getElementById('adminTenure').value);
        const interestRate = 0.05;
        const total = amount * (1 + interestRate);

        document.getElementById('admin-summary-amount').textContent = amount.toLocaleString();
        document.getElementById('admin-summary-tenure').textContent = tenure;
        document.getElementById('admin-summary-total').textContent = Math.round(total).toLocaleString();
    }

    // Attach event listeners
    if (document.getElementById('customAmount')) {
        document.getElementById('customAmount').addEventListener('input', updateAdminLoanSummary);
        document.getElementById('adminTenure').addEventListener('change', updateAdminLoanSummary);
    }

    function toggleSubmit() {
        document.getElementById('submitFinalApplication').disabled = !document.getElementById('termsCheckbox').checked;
    }

    function submitFinalLoanApplication() {
        alert("Thank you for your loan application! We are currently reviewing your request. Our team will be in touch with you shortly for a brief interview to finalize the process. Please expect a call soon.");
        // Optional: redirect or show back-to-home button
    }

    // Call once initially
    updateAdminLoanSummary();

    function previewImage(inputId, previewContainerId) {
        const input = document.getElementById(inputId);
        const previewContainer = document.getElementById(previewContainerId);

        if (!input || !previewContainer) return; // ⛔ Skip kung wala
        
        input.addEventListener('change', function () {
            previewContainer.innerHTML = ''; // Clear previous preview
            const files = input.files;

            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.innerHTML = '<p class="text-muted">File preview not available (non-image).</p>';
                }
            });
        });
    }

    // Initialize previews for relevant inputs
    previewImage('payslipInput', 'payslipPreview');
    previewImage('qrInput', 'qrPreview');
    previewImage('govIdInput', 'govIdPreview');
    previewImage('signatureInput', 'signaturePreview');
    previewImage('billingInput', 'billingPreview');

});