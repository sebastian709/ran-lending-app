@extends('borrower.app')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
 <link rel="stylesheet" href="css/payment.css">

@endsection

@section('content')
<div class="d-flex">
    @include('borrower.layouts.sidebar')
    <div class="container py-4">
        <h2 class="text-center fw-bold mb-4">Payment History</h2>

        {{-- Payment: For Revision --}}
        <div class="payment-item" data-bs-toggle="collapse" data-bs-target="#payment-1">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="status-dot status-revision"></span>
                    <strong class="me-2">₱15,000</strong>
                    <span class="text-muted">20 July 2025 - Monthly Due</span>
                </div>
                <i class="ri-arrow-right-s-line arrow-icon"></i>
            </div>
        </div>
        <div id="payment-1" class="collapse">
            <div class="payment-details">
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Status:</strong> <span class="text-warning">For Revision</span></div>
                    <div class="col-md-6"><strong>Reference:</strong> REF-20250728-1234</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-3"><strong>Monthly Due:</strong> ₱10,000</div>
                    <div class="col-md-3"><strong>Interest:</strong> ₱2,000</div>
                    <div class="col-md-3"><strong>Penalty:</strong> ₱500</div>
                    <div class="col-md-3"><strong>Principal:</strong> ₱7,500</div>
                </div>
                <div class="mb-2"><strong>Amount Paid:</strong> ₱12,500</div>
                <div class="mb-2"><strong>Attachment:</strong><br>
                    <a href="#"><img src="https://via.placeholder.com/300x180?text=Receipt" class="attachment-img mt-2"></a>
                </div>
                <div class="mb-3"><strong>Remarks:</strong> Payment was submitted late due to banking maintenance. Please verify.</div>

                <div class="mb-3">
                    <strong>Comments:</strong>
                    <div class="comment-box">
                        <div class="comment-author">Borrower:</div>
                        <div>I already uploaded the updated screenshot as requested.</div>
                    </div>
                    <div class="comment-box">
                        <div class="comment-author">Admin:</div>
                        <div>Please re-upload a clearer image. The current one is blurred.</div>
                    </div>
                </div>

                <form class="form-comment">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Your Comment</label>
                        <textarea class="form-control" rows="3" placeholder="Enter your comment..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Comment</button>
                </form>
            </div>
        </div>

        {{-- Payment: Successful --}}
        <div class="payment-item" data-bs-toggle="collapse" data-bs-target="#payment-2">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="status-dot status-success"></span>
                    <strong class="me-2">₱18,000</strong>
                    <span class="text-muted">15 June 2025 - Monthly Due</span>
                </div>
                <i class="ri-arrow-right-s-line arrow-icon"></i>
            </div>
        </div>
        <div id="payment-2" class="collapse">
            <div class="payment-details">
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Status:</strong> <span class="text-success">Successful</span></div>
                    <div class="col-md-6"><strong>Reference:</strong> REF-20250615-ABCD</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-3"><strong>Monthly Due:</strong> ₱15,000</div>
                    <div class="col-md-3"><strong>Interest:</strong> ₱1,500</div>
                    <div class="col-md-3"><strong>Penalty:</strong> ₱0</div>
                    <div class="col-md-3"><strong>Principal:</strong> ₱13,500</div>
                </div>
                <div class="mb-2"><strong>Amount Paid:</strong> ₱18,000</div>
                <div class="mb-2"><strong>Attachment:</strong><br>
                    <a href="#"><img src="https://via.placeholder.com/300x180?text=Receipt" class="attachment-img mt-2"></a>
                </div>
                <div class="mb-3"><strong>Remarks:</strong> On-time payment. No issues.</div>
            </div>
        </div>

        {{-- Payment: Failed --}}
        <div class="payment-item" data-bs-toggle="collapse" data-bs-target="#payment-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="status-dot status-failed"></span>
                    <strong class="me-2">₱15,000</strong>
                    <span class="text-muted">01 May 2025 - Monthly Due</span>
                </div>
                <i class="ri-arrow-right-s-line arrow-icon"></i>
            </div>
        </div>
        <div id="payment-3" class="collapse">
            <div class="payment-details">
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Status:</strong> <span class="text-danger">Failed</span></div>
                    <div class="col-md-6"><strong>Reference:</strong> REF-20250501-FAILED</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-3"><strong>Monthly Due:</strong> ₱10,000</div>
                    <div class="col-md-3"><strong>Interest:</strong> ₱3,000</div>
                    <div class="col-md-3"><strong>Penalty:</strong> ₱2,000</div>
                    <div class="col-md-3"><strong>Principal:</strong> ₱5,000</div>
                </div>
                <div class="mb-2"><strong>Amount Paid:</strong> ₱0</div>
                <div class="mb-2"><strong>Attachment:</strong><br>
                    <span class="text-muted fst-italic">No attachment provided</span>
                </div>
                <div class="mb-3"><strong>Remarks:</strong> Payment failed due to insufficient balance.</div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/payment.js') }}"></script>


@endsection
