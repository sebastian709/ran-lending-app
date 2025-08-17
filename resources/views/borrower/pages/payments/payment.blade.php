@extends('borrower.app')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
 <link rel="stylesheet" href="css/payment.css">
<style>
   
</style>
@endsection

@section('content')
<div class="d-flex">
    @include('borrower.layouts.sidebar')
    <div class="container container-payment payment_form_1">
        <!-- <button type="button" data-url="/profile" class="btn small  start-0 top-50 translate-middle-y p-0">
            <i class="ri-home-line me-2"></i> Back to home
        </button> -->
        <h2 class="header-title">Payment</h2>

        <!-- PAYMENT TYPE -->
        <div class="card-section">
            <span class="section-title">Total Amount to Pay</span>
            <div class="amount-display" id="totalAmount" data-amount="">₱ <span></span></div>
        </div>
        
        <!-- NEXT PAYMENT -->
        <div class="card-section">
            <span class="section-title">Next Payment Due</span>
            <div class="list-group next_payment">

                @foreach($data['to_pay'] as $index => $to_pay)
                    <div class="next_par">
                        <label class="list-group-item d-flex align-items-center justify-content-between gap-3 mb-2 payment-option-next position-relative">
                            <div class="d-flex align-items-center gap-3">
                                <input class="form-check-input flex-shrink-0 due_payment" type="checkbox" data-tenure_count="{{ $to_pay->count }}" data-tenure_id="{{ $to_pay->id }}" data-id="{{ $to_pay->id }}" data-amount="{{ $to_pay->total }}" data-principal="{{ $to_pay->principal }}" data-interest="{{ $to_pay->interest }}" data-penalty="{{ $to_pay->penalty }}" checked @if($loop->first) disabled @endif>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">Due Date: {{ \Carbon\Carbon::parse($to_pay->date)->format('F j, Y') }}</span>
                                    <small class="text-muted">{{ $to_pay->count }}/{{ $to_pay->months }} Payment</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="fw-bold text-success">₱ {{ number_format($to_pay->total,2) }}</div>
                                
                                <button title="Partial Payment" class="togglePartials togglePartials-{{ $to_pay->id }} btn btn-outline-primary btn-sm  @if(!$loop->last) d-none @endif" aria-label="Toggle partial payments">
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                            </div>
                        </label>

                        <div class="list-group partial-months mb-4 p-4 px-4  border rounded bg-light shadow-sm" style="display: none;">
                            <!-- Interest -->
                            <label class="d-flex align-items-center justify-content-between gap-3 mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <input class="form-check-input partially-pay partial-interest" type="checkbox" data-amount="{{ $to_pay->interest }}" data-category="Interest">
                                <div class="d-flex flex-column">
                                <span class="fw-semibold mb-0">Interest</span>
                                <small class="text-muted">Accrued interest amount</small>
                                </div>
                            </div>
                            <div class="fw-bold text-success fs-6">₱ {{ number_format($to_pay->interest, 2) }}</div>
                            </label>

                            <!-- Penalty -->
                            <label class="d-flex align-items-center justify-content-between gap-3 mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <input class="form-check-input partially-pay partial-penalty" type="checkbox" data-amount="{{ $to_pay->penalty ?? 0 }}" data-category="Penalty">
                                <div class="d-flex flex-column">
                                <span class="fw-semibold mb-0">Penalty</span>
                                <small class="text-muted">Late payment charges</small>
                                </div>
                            </div>
                            <div class="fw-bold text-success fs-6">₱ {{ number_format($to_pay->penalty ?? 0, 2) }}</div>
                            </label>

                            <!-- Principal -->
                            <label class="d-flex align-items-center justify-content-between gap-3 mb-0">
                            <div class="d-flex align-items-center gap-3">
                                <input class="form-check-input partially-pay partial-principal" type="checkbox" data-amount="{{ $to_pay->principal }}" data-category="Principal">
                                <div class="d-flex flex-column">
                                <span class="fw-semibold mb-0">Principal</span>
                                <small class="text-muted">Remaining loan balance</small>
                                </div>
                            </div>
                            <div class="fw-bold text-success fs-6">₱ {{ number_format($to_pay->principal, 2) }}</div>
                            </label>
                        </div>
                    </div>
                @endforeach

                </div>
                <button class="btn btn-sm btn-outline-primary pay-partial-btn d-none">Pay Partial</button>
                <button class="btn btn-sm btn-outline-secondary pay-partial-btn_close close-partial d-none">Close Partial</button>
            </div>       

        <!-- ADVANCE PAYMENT -->
        <div class="card-section advance_section">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="section-title">Advance Payment</span>
                <button id="toggleAdvance" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>

            <div class="list-group advance-months" style="display: none;">
                <!-- Month -->
            @foreach($data['records']['loan'] as $index => $record)
                <label class="list-group-item d-flex align-items-center justify-content-between gap-3 mb-2 payment-option payment-option-advance {{ $index > 2 ? 'extra-payment d-none' : '' }}">
                    <div class="d-flex align-items-center gap-3">
                        <input class="form-check-input flex-shrink-0 advance_payment_month" type="checkbox" data-tenure_id="{{ $record['tenure_id'] }}" data-amount="{{ $record['interest'] + $record['principal'] }}" data-principal="{{ $record['principal'] }}" data-penalty="0" data-interest="{{ $record['interest'] }}" data-tenure_id="{{ $record['id'] }}">
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">Due Date: {{ \Carbon\Carbon::parse(is_array($record) ? $record['date'] : $record->date)->format('F j, Y') }}</span>
                            <small class="text-muted">{{ $record['count'] }}/ Payment</small>
                        </div>
                    </div>
                    <div class="fw-bold text-success">₱ {{ number_format($record['interest'] + $record['principal'],2) }}</div>
                </label>
                @endforeach
                <!-- All -->
                <label class="list-group-item d-flex align-items-center justify-content-between gap-3 mb-2">
                    <div class="d-flex align-items-center gap-3">
                        <input class="form-check-input" type="checkbox" id="payAllCheck">
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">All</span>
                        </div>
                    </div>
                </label>
                <!-- Show More Button -->
                @if(count($data['records']['loan']) > 3)
                <div class="text-center mt-2">
                    <button class="btn btn-sm btn-primary" id="showMoreAdvance">Show More <i class="bi bi-chevron-down"></i></button>
                </div>
                @endif
            </div>
        </div>


        <!-- PAYMENT BREAKDOWN -->
        <div class="card-section">
            <span class="section-title">Payment Breakdown</span>
            <ul class="breakdown-list" id="breakdownBody">
                <li class="breakdown-item d-flex align-items-center">
                    <span class="label">Principal</span>
                    <span class="flex-line mx-2"></span>
                    <span class="payment-principal">₱</span>
                </li>
                <li class="breakdown-item d-flex align-items-center">
                    <span class="label">Interest</span>
                    <span class="flex-line mx-2"></span>
                    <span class="payment-interest">₱</span>
                </li>
                <li class="breakdown-item d-flex align-items-center rebate d-none">
                    <span class="label">Rebate</span>
                    <span class="flex-line mx-2"></span>
                    <span class="payment-rebate">₱ 0.00</span>
                </li>
                <li class="breakdown-item d-flex align-items-center">
                    <span class="label">Penalty</span>
                    <span class="flex-line mx-2"></span>
                    <span class="payment-penalty">₱</span>
                </li>
                <li class="d-flex justify-content-between fw-bold border-top mt-2 pt-2 text-muted">
                    <span>Total</span>
                    <span class="payment-total">₱</span>
                </li>
            </ul>
        </div>

        <!-- PARTIAL PAYMENTS -->
        <div class="card-section d-none">
            <div id="partialSummary" class="border rounded p-3 bg-white">
                <strong>Category:</strong> <span id="summaryCategory">Interest</span><br>
                <strong>Total Amount:</strong> ₱<span id="summaryAmount">0.00</span><br>
                <strong>Covered Months:</strong> <span id="summaryMonths">0</span>
            </div>
        </div>

        <!-- PAY NOW BUTTON -->
            <button class="btn btn-primary w-100 loan_payment_confirm"  id="payNowBtn">Pay Now</button>
    </div>


    <div class="container py-5 px-4 payment_form_2 d-none">
        <div class="mx-auto" style="max-width: 800px;">
            <button type="button" data-url="/payment" class="btn small  start-0 top-50 translate-middle-y p-0">
               <i class="ri-arrow-go-back-line"></i>
                    Return
            </button>
            <h2 class="text-center fw-bold mb-4">Payment Confirmation</h2>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <!-- Summary Box -->
                    <div class="bg-divider rounded-3 p-4 mb-4 text-center">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h6 class="text-muted">Total Amount</h6>
                                <p class="fs-3 fw-bold text-success mb-0 total_amount_summary" id="list-group-item d-flex align-items-center justify-content-between gap-3 mb-2 position-relative ">₱5,000</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">Payment Category</h6>
                                <p class="fs-5 fw-semibold" id="confirmCategory">Principal</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Details -->
                    <h5 class="mb-3 fw-semibold">Bank Details</h5>
                    <div class="d-flex align-items-center bg-divider rounded-3 p-3 mb-3">
                        <img src="{{ asset('images/bpi_logo.png') }}" alt="BPI Logo" class="me-3" style="height: 45px;">
                        <div>
                            <h6 class="mb-0">Bank of the Philippine Islands (BPI)</h6>
                            <small class="text-muted">Preferred payment option</small>
                        </div>
                    </div>
                    <div class="bg-divider p-3">
                        <p class="mb-1"><strong>Account Number:</strong> 827 925 8976</p>
                        <p><strong>Account Name:</strong> Nida C. Lingat</p>
                    </div>
                    <!-- QR Code -->
                    <div class="text-center my-4">
                        <label class="form-label fw-semibold">Scan QR Code</label>
                        <div>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#qrCodeModal">
                                <img src="{{ asset('images/bpi.jpg') }}" alt="QR Code" class="img-fluid rounded shadow" style="max-width: 200px;">
                            </a>
                            <div class="form-note mt-2 small text-muted">Click QR code to enlarge</div>
                        </div>
                    </div>

                    <!-- Reference Code -->
                    <div class="mb-3">
                        <label for="referenceCode" class="form-label">Transaction Reference Code</label>
                        <div class="input-group">
                            <input type="text" id="referenceCode" name="referenceCode" class="form-control" maxlength="16" pattern="[A-Za-z0-9]{1,16}" required>
                            <span class="input-group-text" data-bs-toggle="tooltip" title="Must match screenshot's reference number">
                                <i class="ri-information-line"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks / Notes (optional)</label>
                        <textarea id="remarks" name="remarks" class="form-control" rows="3" placeholder="Any additional info..."></textarea>
                    </div>

                    <!-- Screenshot Upload -->
                    <div class="mb-4">
                        <label class="form-label">Transaction Screenshot</label>
                        <div id="dropzone" class="dropzone text-center p-4 rounded-3" onclick="triggerUpload()"
                            ondragover="event.preventDefault(); this.classList.add('border-primary')"
                            ondragleave="this.classList.remove('border-primary')"
                            ondrop="handleDrop(event)">
                            
                            <div id="placeholder">
                                <i class="bi bi-cloud-upload fs-1 text-secondary"></i>
                                <p class="mb-1 fw-semibold">Click or drag file here</p>
                                <p class="text-muted small">Accepted: JPG, PNG, GIF</p>
                            </div>

                            <div id="preview" class="d-none">
                                <img id="preview-image" class="img-fluid rounded shadow mb-3" style="max-height: 200px;">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-outline-primary btn-sm" onclick="triggerUpload()">Replace</button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="removeImage()">Remove</button>
                                </div>
                            </div>

                            <input type="file" id="screenshot" name="screenshot" class="d-none" accept="image/*" required onchange="handleFileUpload(event)">
                        </div>
                        <div class="form-text mt-2">Please provide your transaction screenshot for further verification.</div>
                    </div>

                    <!-- Submit -->
                    <center>
                        <button class="btn btn-primary col-md-5"  id="payment_return">Back</button>
                        <button type="button" data-id="{{ $data['loan_application']->id }}" class="btn btn-primary col-md-5 py-2" data-urls="/payment-success" id="submit_payment">Confirm Payment</button>
                    <center>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 zoom-in">
      <div class="modal-header border-0 pb-0">
        <h6 class="modal-title">QR Code</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-center d-flex flex-column align-items-center gap-3">
        <div style="overflow: hidden;">
            <img id="qrImage" src="{{ asset('images/bpi.jpg') }}" alt="QR Code Full" class="img-fluid rounded" style="transition: transform 0.2s; max-width: 100%;">
        </div>

        <div class="d-flex justify-content-center gap-2">
            <button class="btn btn-outline-secondary btn-sm" id="zoomInBtn">
                <i class="ri-zoom-in-line"></i>
            </button>
            <button class="btn btn-outline-secondary btn-sm" id="zoomOutBtn">
                <i class="ri-zoom-out-line"></i>
            </button>
            <a href="{{ asset('images/bpi.jpg') }}" download="BPI_QR_Code.png" class="btn btn-outline-primary btn-sm">
                <i class="ri-download-line me-1"></i> Download
            </a>
        </div>
      </div>
    </div>
  </div>




</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/payment.js') }}"></script>


@endsection
