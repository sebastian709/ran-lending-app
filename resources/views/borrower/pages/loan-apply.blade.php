@extends('borrower.app')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
@endsection


@section('content')
    <div class="d-flex">
        <!-- Sidebar -->
        @include('borrower.layouts.sidebar')

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <div class="container-fluid p-4" style="max-width: 1200px;">
                <!-- Header with Back Button -->
                <button class="btn btn-outline-primary-custom me-3 mb-3 la_back_step d-none">
                    <i class="ri-arrow-left-line me-2"></i>
                    Back
                </button>
                <button class="btn btn-outline-primary-custom me-3 mb-3 la_go_home" data-url="/home">
                    <i class="ri-home-line me-2"></i>
                    Go Home
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
                                        <input type="text" class="form-control" id="occupation">
                                        
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="income" class="form-label fw-medium">Current Income (₱)</label>
                                        <input type="text" class="form-control" id="income">
                                        
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-medium">Employment Status</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="employmentStatus" id="employed" value="1">
                                        <label class="form-check-label" for="employed">Employed</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="employmentStatus" id="self-employed" value="2">
                                        <label class="form-check-label" for="self-employed">Self Employed</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="employmentStatus" id="none" value="3">
                                        <label class="form-check-label" for="none">None</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="purpose" class="form-label fw-medium">Purpose of Loan (Optional)</label>
                                    <textarea class="form-control" id="la_purpose" rows="3" placeholder="Tell us how you plan to use this loan..."></textarea>
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
                                        <input type="text" class="form-control" data-is_valid="0" data-referral_code_id="0" id="referralCode" placeholder="Enter referral code provided by admin">
                                    </div>
                                </div>

                                <button type="button" class="btn btn-primary-custom btn-md w-100 la_submit_precheck">
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
                                                <span>Monthly Interest:</span>
                                                <span class="fw-medium la_ad_loan_interest">5%</span>
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
                                <button class="btn btn-primary-custom btn-md w-100 la_ad_proceed_loan">
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
                                    <input type="range" class="form-range la_loan_amount_slider" id="loanAmountSlider" min="1000" max="5000" step="500" value="5000">
                                    <div class="d-flex justify-content-between small text-muted">
                                        <span>₱1,000</span>
                                        <span>₱5,000</span>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="standardTenure" class="form-label fw-medium">Loan Tenure (months)</label>
                                        <select class="form-select la_standard_tenure" id="standardTenure">
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
                                                <span>Monthly Interest:</span>
                                                <span class="fw-medium la_loan_interest">5%</span>
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

                                <button class="btn btn-primary-custom btn-md w-100 la_proceed_loan">
                                    <i class="ri-arrow-right-line me-2"></i>
                                    Proceed
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Full Loan Application Step -->
                <div id="full-loan-application-step" class="step-content">
                    <div class="dashboard-card border rounded shadow-sm overflow-hidden">
                        <div class="card-header bg-primary-custom text-white rounded-top p-2">
                        <h4 class="mb-0 d-flex align-items-center gap-2">
                            <i class="ri-edit-box-line"></i>
                            Full Loan Application
                        </h4>
                        </div>

                        <div class="card-body p-4">

                        <!-- Proof of Income -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Upload your payslip from the last 30 days</label>
                            <input type="file" class="form-control la_payslip_img" accept="image/*,application/pdf" id="payslipInput">
                            <div id="payslipPreview" class="mt-2 border border-dashed rounded p-3 bg-light text-center text-muted preview-container">
                            No preview
                            </div>
                        </div>

                        <!-- Bank Info & QR Code -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Bank Name</label>
                            <input type="text" class="form-control la_bank_name">
                            </div>
                            <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Account Number</label>
                            <input type="text" class="form-control la_account_number">
                            </div>
                            <div class="col-12">
                            <label class="form-label fw-medium">Upload QR Code (GCash/Bank)</label>
                            <input type="file" class="form-control la_qr_code" accept="image/*" id="qrInput">
                            <div id="qrPreview" class="mt-2 border border-dashed rounded p-3 bg-light text-center text-muted preview-container">
                                No preview
                            </div>
                            </div>
                        </div>

                        <!-- Government ID -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Government ID</label>
                            <select class="form-select mb-2 la_government_id">
                                <option selected disabled>Select your ID</option>
                                @foreach($government_type as $gov)
                                    <option value="{{ $gov->id }}">{{ $gov->government_id_type }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted mb-3 d-flex align-items-start gap-1">
                            <i class="ri-information-line mt-1"></i>
                            <span>Upload an ID that includes both your photo and signature.</span>
                            </div>

                            <label class="form-label fw-medium">Upload ID Image</label>
                            <input type="file" class="form-control mb-2 la_government_id_img" accept="image/*" id="govIdInput">
                            <div id="govIdPreview" class="mt-2 border border-dashed rounded p-3 bg-light text-center text-muted preview-container">
                            No preview
                            </div>
                            <div class="form-text">
                            Upload a clear photo of yourself holding your ID for verification.
                            </div>
                        </div>

                        <!-- Billing Statement -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Billing Statement</label>
                            <input type="file" class="form-control la_billing_statement" accept="application/pdf,image/*" id="billingInput">
                            <div id="billingPreview" class="mt-2 border border-dashed rounded p-3 bg-light text-center text-muted preview-container">
                            No preview
                            </div>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="form-check mb-4">
                            <input class="form-check-input la_terms_checkbox" type="checkbox" id="termsCheckbox">
                            <label class="form-check-label" for="termsCheckbox">
                            I agree to the <a href="#">Terms and Conditions</a>
                            </label>
                        </div>

                        <!-- Signature Section -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Signature</label>
                            <p class="text-muted">Click the button below to sign or upload your handwritten signature.</p>
                            <div class="w-100" style="max-width: 300px;">
                            @include('components.signature-box', [
                                'nameClass' => 'c-applicant-name-1',
                                'positionClass' => 'c-applicant-position-1'
                                
                            ])
                            </div>
                            <!-- 'customWidth' => '500px' -->
                        </div>

                        <!-- Submit Button -->
                        <button class="btn btn-primary-custom w-100 la_submit_final_application" id="submitFinalApplication" disabled>
                            Submit Application
                        </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

