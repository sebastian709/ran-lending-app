@extends('borrower.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
 <link rel="stylesheet" href="css/payment.css">
<style>
   
</style>
@endsection

@section('content')
<div class="d-flex">
    @include('borrower.layouts.sidebar')

    <div class="container py-5 px-4">
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
                                <p class="fs-3 fw-bold text-success mb-0" id="confirmTotal">&#8369;5,000</p>
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
                    <button type="submit" class="btn btn-primary w-100 py-2" data-url="/payment-success">Confirm Payment</button>
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

<script src="{{ asset('js/payment.js') }}"></script>


@endsection
