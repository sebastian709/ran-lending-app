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

            <!-- Standard Referral Result -->
                <input type="text" class="steps_val d-none" value="{{ $step }}"> 
                <div id="standard-result" class="dashboard-card border rounded shadow-sm overflow-hidden p-4 {{ $loan_amount_rejected == 0 ? 'd-none' : '' }}">
                    <div class="text-center p-4 bg-primary bg-opacity-10 rounded mb-4">
                        <div class="eligibility-icon eligibility-info">
                            <i class="ri-money-dollar-circle-line text-white" style="font-size: 2.5rem;"></i>
                        </div>
                        <h3 class="h4 fw-bold text-primary-custom mb-2">
                            We’re sorry, you are only eligible to borrow up to ₱{{ $max_amount }}.
                        </h3>
                        <p class="text-primary-custom">Please choose an amount within this limit and click Continue to proceed with your application.</p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium mb-3">
                            Choose your loan amount: ₱<span id="loan-amount-display">{{ $first_amount }}</span>
                        </label>

                        <input type="range" class="form-range la_loan_amount_slider new_slider" 
                            id="loanAmountSlider"
                            min="1000" 
                            max="{{ $max_amount }}" 
                            step="1000" 
                            value="{{ $first_amount }}">

                        <div class="d-flex justify-content-between small text-muted">
                            <span>₱1000</span>
                            <span>₱{{ $max_amount }}</span>
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
                                    <span class="fw-medium">₱<span id="summary-amount">{{ $max_amount }}</span></span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Interest Rate:</span>
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
                                    <span class="fw-bold">₱<span id="summary-total">{{ $summary_total }}</span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary-custom btn-md w-100 la_proceed_loan_update_new" data-loan_id="{{ $loan_id }}">
                        <i class="ri-arrow-right-line me-2"></i>
                        Proceed
                    </button>
                </div>

                <!-- Loading Step -->
                <div id="loading-steps" class="step-contents d-none">
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
               
                <div id="full-loan-application-step" class="step-cntent document_step {{ $step == 2 ? '' : 'd-none' }}" >
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
                            <!-- @include('components.signature-box', [
                                'nameClass' => 'c-applicant-name-1',
                                'positionClass' => 'c-applicant-position-1'
                                
                            ]) -->
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

