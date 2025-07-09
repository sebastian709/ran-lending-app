@extends('layouts.app')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0056b3;
            --secondary-color: #ff8c00;
        }
        
        :where([class^="ri-"])::before { 
            content: "\f3c2"; 
        }
        
        .font-pacifico {
            font-family: 'Pacifico', cursive;
        }
        
        .bg-primary-custom {
            background-color: var(--primary-color) !important;
        }
        
        .bg-secondary-custom {
            background-color: var(--secondary-color) !important;
        }
        
        .text-primary-custom {
            color: var(--primary-color) !important;
        }
        
        .text-secondary-custom {
            color: var(--secondary-color) !important;
        }
        
        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            color: white;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            background-color: rgba(0, 86, 179, 0.9);
            border-color: rgba(0, 86, 179, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 86, 179, 0.3);
            color: white;
        }
        
        .btn-outline-primary-custom {
            color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
        }
        
        .btn-outline-primary-custom:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .rounded-custom {
            border-radius: 8px !important;
        }
        
        .navbar-custom {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background-color: white !important;
        }
        
        .sidebar {
            background-color: #f8f9fa;
            min-height: calc(100vh - 76px);
            border-right: 1px solid #e9ecef;
        }
        
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-nav li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #6c757d;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .sidebar-nav a:hover {
            background-color: rgba(0, 86, 179, 0.1);
            color: var(--primary-color);
        }
        
        .sidebar-nav a.active {
            background-color: var(--primary-color);
            color: white;
        }
        
        .sidebar-nav a i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f3f4;
            transition: all 0.3s ease;
        }
        
        .dashboard-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        
        .main-content {
            background-color: #fafbfc;
            min-height: calc(100vh - 76px);
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .step-content {
            display: none;
        }
        
        .step-content.active {
            display: block;
        }
        
        .loading-spinner {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            animation: spin 2s linear infinite;
        }
        
        .loading-spinner i {
            font-size: 3rem;
            color: white;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .progress-bar-animated {
            animation: progress-animation 3s ease-in-out;
        }
        
        @keyframes progress-animation {
            0% { width: 0%; }
            100% { width: 60%; }
        }
        
        .eligibility-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .eligibility-success {
            background-color: #28a745;
        }
        
        .eligibility-info {
            background-color: var(--primary-color);
        }
        
        .form-range::-webkit-slider-thumb {
            background: var(--primary-color);
        }
        
        .form-range::-moz-range-thumb {
            background: var(--primary-color);
            border: none;
        }
        
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                top: 76px;
                left: -250px;
                width: 250px;
                z-index: 1000;
                transition: left 0.3s ease;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .main-content {
                margin-left: 0 !important;
            }

        }

        .preview-container img {
            max-width: 150px;
            max-height: 150px;
            border-radius: 8px;
            margin-right: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
          }
    </style>
@endsection


@section('content')
    <div class="d-flex">
        <!-- Sidebar -->
        @include('sidebar')

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <div class="container-fluid p-4" style="max-width: 1200px;">
                <!-- Header with Back Button -->
                 <button class="btn btn-outline-primary-custom me-3 mb-3" onclick="goBackToDashboard()">
                    <i class="ri-arrow-left-line me-2"></i>
                    Back
                </button>
                <div class="d-flex align-items-center mb-4">
                   
                    <div class="">
                        <h1 class="h3 fw-bold mb-1 text-primary-custom">Loan Application</h1>
                        <p class="text-muted mb-0">Complete your loan application in simple steps</p>
                    </div>
                </div>

                <!-- Pre-check Verification Step -->
                <div id="precheck-step" class="step-content active">
                    <div class="dashboard-card">
                        <div class="card-header bg-primary-custom text-white rounded-top p-2">
                            <h4 class="mb-0">
                                <i class="ri-shield-check-line me-2"></i>
                                Pre-check Verification
                            </h4>
                        </div>
                        <div class="card-body p-4">
                            <form id="precheck-form">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="occupation" class="form-label fw-medium">Occupation</label>
                                        <input type="text" class="form-control" id="occupation" value="Software Developer">
                                        <div class="form-text">Auto-populated from your profile</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="income" class="form-label fw-medium">Current Income (₱)</label>
                                        <input type="number" class="form-control" id="income" value="50000">
                                        <div class="form-text">Auto-populated from your profile</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-medium">Employment Status</label>
                                    <div class="form-text mb-2">Auto-populated from your profile</div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="employmentStatus" id="employed" value="employed" checked>
                                        <label class="form-check-label" for="employed">Employed</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="employmentStatus" id="self-employed" value="self-employed">
                                        <label class="form-check-label" for="self-employed">Self Employed</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="employmentStatus" id="none" value="none">
                                        <label class="form-check-label" for="none">None</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="purpose" class="form-label fw-medium">Purpose of Loan (Optional)</label>
                                    <textarea class="form-control" id="purpose" rows="3" placeholder="Tell us how you plan to use this loan..."></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-medium">Select your referral (if any):</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="referralType" id="admin" value="admin">
                                        <label class="form-check-label" for="admin">Admin</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="referralType" id="friend" value="friend">
                                        <label class="form-check-label" for="friend">Friend</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="referralType" id="others" value="others">
                                        <label class="form-check-label" for="others">Others</label>
                                    </div>
                                    
                                    <!-- Referral Code Input (hidden by default) -->
                                    <div id="referral-code-section" class="mt-3" style="display: none;">
                                        <label for="referralCode" class="form-label fw-medium">Referral Code</label>
                                        <input type="text" class="form-control" id="referralCode" placeholder="Enter referral code provided by admin">
                                    </div>
                                </div>

                                <button type="button" class="btn btn-primary-custom btn-md w-100" onclick="submitPrecheck()">
                                    <i class="ri-send-plane-line me-2"></i>
                                    Submit Application
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Loading Step -->
                <div id="loading-step" class="step-content">
                    <div class="dashboard-card">
                        <div class="card-body p-5 text-center">
                            <div class="loading-spinner">
                                <i class="ri-time-line"></i>
                            </div>
                            <h2 class="h3 fw-bold mb-3">Processing Your Application</h2>
                            <p class="text-muted fs-5 mb-4">
                                Please wait while we are conducting a preliminary eligibility result for your loan request
                            </p>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary-custom progress-bar-animated" role="progressbar" style="width: 60%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Eligibility Result Step -->
                <div id="eligibility-step" class="step-content">
                    <div class="dashboard-card">
                        <div class="card-header bg-success text-white rounded-top p-2">
                            <h4 class="mb-0">
                                <i class="ri-shield-check-line me-2"></i>
                                Eligibility Result
                            </h4>
                        </div>
                        <div class="card-body p-4">
                            <!-- Admin Referral Result -->
                            <div id="admin-result" style="display: none;">
                                <div class="text-center p-4 bg-success bg-opacity-10 rounded mb-4">
                                    <div class="eligibility-icon eligibility-success">
                                        <i class="ri-money-dollar-circle-line text-white" style="font-size: 2.5rem;"></i>
                                    </div>
                                    <h3 class="h4 fw-bold text-success mb-2">Congratulations! You're pre-approved!</h3>
                                    <p class="text-success">As an admin referral, you can request a custom loan amount.</p>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="customAmount" class="form-label fw-medium">Desired Loan Amount (₱)</label>
                                        <input type="number" class="form-control form-control-lg" id="customAmount" value="5000" placeholder="Enter your desired amount">
                                        <div class="form-text">You can input any amount based on your needs</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="adminTenure" class="form-label fw-medium">Loan Tenure (months)</label>
                                        <select class="form-select form-select-lg" id="adminTenure">
                                            <option value="1">1 month</option>
                                            <option value="2">2 months</option>
                                            <option value="3">3 months</option>
                                            <option value="4">4 months</option>
                                            <option value="5">5 months</option>
                                            <option value="6">6 months</option>
                                            <option value="7">7 months</option>
                                            <option value="8">8 months</option>
                                            <option value="9">9 months</option>
                                            <option value="10">10 months</option>
                                            <option value="11">11 months</option>
                                            <option value="12">12 months</option>
                                        </select>
                                    </div>
                                </div>
                                 <div class="p-3 bg-light rounded mb-4">
                                    <h5 class="fw-bold mb-3">Loan Summary</h5>
                                    <div class="row small">
                                        <div class="col-6">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Loan Amount:</span>
                                                <span class="fw-medium">₱<span id="admin-summary-amount">5,000</span></span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Interest Rate:</span>
                                                <span class="fw-medium">5%</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Tenure:</span>
                                                <span class="fw-medium"><span id="admin-summary-tenure">1</span> month(s)</span>
                                            </div>
                                            <div class="d-flex justify-content-between border-top pt-1">
                                                <span class="fw-bold">Total Amount:</span>
                                                <span class="fw-bold">₱<span id="admin-summary-total">5,250</span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary-custom btn-md w-100" onclick="proceedWithLoan()">
                                    <i class="ri-arrow-right-line me-2"></i>
                                    Proceed with Application
                                </button>
                            </div>

                            <!-- Standard Referral Result -->
                            <div id="standard-result">
                                <div class="text-center p-4 bg-primary bg-opacity-10 rounded mb-4">
                                    <div class="eligibility-icon eligibility-info">
                                        <i class="ri-money-dollar-circle-line text-white" style="font-size: 2.5rem;"></i>
                                    </div>
                                    <h3 class="h4 fw-bold text-primary-custom mb-2">
                                        You're eligible for a loan up to ₱5,000 with an interest rate of 5%.
                                    </h3>
                                    <p class="text-primary-custom">You can borrow up to ₱15,000 on your next loan!</p>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-medium mb-3">
                                        Choose your loan amount: ₱<span id="loan-amount-display">5000</span>
                                    </label>
                                    <input type="range" class="form-range" id="loanAmountSlider" min="1000" max="5000" step="500" value="5000" oninput="updateLoanAmount(this.value)">
                                    <div class="d-flex justify-content-between small text-muted">
                                        <span>₱1,000</span>
                                        <span>₱5,000</span>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="standardTenure" class="form-label fw-medium">Loan Tenure (months)</label>
                                        <select class="form-select" id="standardTenure" onchange="updateLoanSummary()">
                                            <option value="1">1 month</option>
                                            <option value="2">2 months</option>
                                            <option value="3">3 months</option>
                                            <option value="4">4 months</option>
                                            <option value="5">5 months</option>
                                            <option value="6">6 months</option>
                                            <option value="7">7 months</option>
                                            <option value="8">8 months</option>
                                            <option value="9">9 months</option>
                                            <option value="10">10 months</option>
                                            <option value="11">11 months</option>
                                            <option value="12">12 months</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="p-3 bg-light rounded mb-4">
                                    <h5 class="fw-bold mb-3">Loan Summary</h5>
                                    <div class="row small">
                                        <div class="col-6">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Loan Amount:</span>
                                                <span class="fw-medium">₱<span id="summary-amount">5,000</span></span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Interest Rate:</span>
                                                <span class="fw-medium">5%</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Tenure:</span>
                                                <span class="fw-medium"><span id="summary-tenure">1</span> month(s)</span>
                                            </div>
                                            <div class="d-flex justify-content-between border-top pt-1">
                                                <span class="fw-bold">Total Amount:</span>
                                                <span class="fw-bold">₱<span id="summary-total">5,250</span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-primary-custom btn-md w-100" onclick="proceedWithLoan()">
                                    <i class="ri-arrow-right-line me-2"></i>
                                    Proceed
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Full Loan Application Step -->
                <div id="full-loan-application-step" class="step-content">
                  <div class="dashboard-card">
                    <div class="card-header bg-primary-custom text-white rounded-top p-2">
                      <h4 class="mb-0">
                        <i class="ri-edit-box-line me-2"></i>
                        Full Loan Application
                      </h4>
                    </div>
                    <div class="card-body p-4">
                      
                      <!-- Proof of Income -->
                      <div class="mb-4">
                        <label class="form-label fw-medium">Upload your payslip from the last 30 days</label>
                        <input type="file" class="form-control" accept="image/*,application/pdf" id="payslipInput">
                        <div id="payslipPreview" class="mt-2 preview-container"></div>
                      </div>

                      <!-- Bank Info & QR Code -->
                      <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                          <label class="form-label fw-medium">Bank Name</label>
                          <input type="text" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                          <label class="form-label fw-medium">Account Number</label>
                          <input type="text" class="form-control">
                        </div>
                        <div class="col-12">
                          <label class="form-label fw-medium">Upload QR Code (GCash/Bank)</label>
                          <input type="file" class="form-control" accept="image/*" id="qrInput">
                          <div id="qrPreview" class="mt-2 preview-container"></div>
                        </div>
                      </div>

                      <!-- Government ID -->
                      <div class="mb-4">
                        <label class="form-label fw-medium">Government ID</label>
                        <select class="form-select mb-2">
                          <option selected disabled>Select your ID</option>
                          <option>Driver’s License</option>
                          <option>PhilSys</option>
                          <option>Philippine Passport</option>
                          <option>Unified Multi-Purpose ID (UMID)</option>
                          <option>Postal ID</option>
                          <option>SSS (Social Security System) ID</option>
                          <option>Work ID</option>
                        </select>
                        <div class="form-text text-muted mb-3">
                          <i class="ri-information-line"></i>
                          Upload an ID that includes both your photo and signature.
                        </div>
                        <label class="form-label fw-medium">Upload ID Image</label>
                        <input type="file" class="form-control mb-2" accept="image/*" id="govIdInput">
                        <div id="govIdPreview" class="mt-2 preview-container"></div>
                        <div class="form-text">
                          Upload a clear photo of yourself holding your ID for verification.
                        </div>
                      </div>

                      <!-- Billing Statement -->
                      <div class="mb-4">
                        <label class="form-label fw-medium">Billing Statement</label>
                        <input type="file" class="form-control" accept="application/pdf,image/*" id="billingInput">
                        <div id="billingPreview" class="mt-2 preview-container"></div>
                      </div>

                      <!-- Terms & Conditions -->
                      <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="termsCheckbox" onchange="toggleSubmit()">
                        <label class="form-check-label" for="termsCheckbox">
                          I agree to the <a href="#">Terms and Conditions</a>
                        </label>
                      </div>

                      <!-- Signature Section -->
                      <div class="mb-4">
                        <label class="form-label fw-medium">Signature</label>
                        <p class="text-muted">Click the button below to sign or upload your handwritten signature.</p>
                        <div class="d-flex flex-column flex-md-row gap-3">
                          <button class="btn btn-outline-primary-custom">Sign</button>
                          <input type="file" class="form-control" accept="image/*" id="signatureInput" style="max-width: 300px;">
                        </div>
                        <div id="signaturePreview" class="mt-2 preview-container"></div>
                      </div>

                      <!-- Submit Button -->
                      <button class="btn btn-primary-custom w-100" id="submitFinalApplication" disabled onclick="submitFinalLoanApplication()">
                        Submit Application
                      </button>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let currentStep = 'precheck';
    let formData = {
        referralType: '',
        loanAmount: 5000,
        tenure: 1
    };

    // Sidebar toggle for mobile
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('show');
    });

    // Handle referral type selection
    document.querySelectorAll('input[name="referralType"]').forEach(radio => {
        radio.addEventListener('change', function() {
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
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        if (window.innerWidth <= 991.98) {
            if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
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
</script>
@endsection