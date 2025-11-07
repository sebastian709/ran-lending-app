@extends('admin.container')

@section('content')
<!-- Borrower Info Section -->
<!-- Borrower Info Section -->
<div class="container py-5">
    <div class="card shadow border-0 rounded-4">
        <!-- Card Header -->
        <div class="card-header bg-primary text-white rounded-top-4 d-flex align-items-center">
            <i class="ri-user-3-fill me-2 fs-4"></i>
            <h5 class="mb-0 fw-bold">Borrower Info</h5>
            <span class="m-3 badge {{ $appeal->receive_status == 0 ? 'bg-danger' : 'bg-success' }}">
                {{ $appeal->receive_status == 0 ? 'Pending' : 'Received' }}
            </span>
    </div>

        <!-- Card Body -->
        <div class="card-body p-4">

            <!-- Borrower Details -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded-3 border d-flex flex-column">
                        <small class="text-muted mb-1"><i class="ri-user-line me-1"></i> Name</small>
                        <span class="fw-semibold fs-5">{{ $appeal->borrower_name }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded-3 border d-flex flex-column">
                        <small class="text-muted mb-1"><i class="ri-id-card-line me-1"></i> Loan ID</small>
                        <span class="fw-semibold fs-5">{{ 'LN-' . str_pad($appeal->loan_id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded-3 border d-flex flex-column">
                        <small class="text-muted mb-1"><i class="ri-barcode-box-line me-1"></i> Loan Reference ID</small>
                        <span class="fw-semibold fs-5">{{ $appeal->reference_number }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded-3 border d-flex flex-column">
                        <small class="text-muted mb-1"><i class="ri-time-line me-1"></i> Appeal Submission</small>
                        <span class="fw-semibold fs-5">{{ $appeal->formatted_date }}</span>
                    </div>
                </div>
            </div>

            <!-- Appeal Reason -->
            <div class="mb-4">
                <small class="text-muted mb-2 d-block"><i class="ri-message-2-line me-1"></i> Appeal Reason</small>
                <div class="p-3 bg-white border rounded-3 shadow-sm">
                    <p class="mb-0">{{ $appeal->reason ?? 'No reason provided' }}</p>
                </div>
            </div>

            <!-- Uploaded Proof -->
            <div class="mb-4">
                <small class="text-muted mb-2 d-block"><i class="ri-attachment-2 me-1"></i> Uploaded Proof</small>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ asset('storage/' . $appeal->uploaded_proof ) }}" target="_blank" class="shadow-sm rounded-4 overflow-hidden">
                        <img src="{{ asset('storage/' . $appeal->uploaded_proof ) }}" 
                             alt="Proof Screenshot" 
                             class="img-fluid rounded-4" 
                             style="width: 320px; height: 320px; object-fit: cover;">
                    </a>
                </div>
            </div>

            <!-- Action Button -->
            <div class="text-end">
                <button class="btn btn-success btn-lg rounded-pill shadow-sm received_appeal" {{ $appeal->receive_status == 0 ? '' : 'hidden' }} value="{{ $appeal->appeal_id  }}">
                    <i class="ri-check-double-line me-2"></i> Receive
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Remix Icon CDN (add inside <head>) -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">

@endsection

