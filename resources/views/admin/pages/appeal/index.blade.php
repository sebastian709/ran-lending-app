@extends('admin.container')

@section('content')
<div class="container-fluid p-4 appeal-page">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div class="section-title d-flex align-items-center mb-0">
            <i class="ri-file-list-3-line me-2"></i> Appeal Details
        </div>
        <button class="btn btn-outline-secondary view-appeal" data-url="/admin/appeal-request/">
            <i class="ri-arrow-left-line me-1"></i> Back to Appeal List
        </button>
    </div>

    <div class="table-card">
        <div class="table-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center">
                <i class="ri-user-3-fill me-2 fs-5 text-primary-custom"></i>
                <h3 class="mb-0">Borrower Information</h3>
            </div>
            <span class="badge {{ $appeal->receive_status == 0 ? 'bg-warning-subtle text-warning-emphasis border' : 'bg-success-subtle text-success-emphasis border' }}">
                {{ $appeal->receive_status == 0 ? 'Pending' : 'Received' }}
            </span>
        </div>

        <div class="card-body p-4">
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="appeal-meta-block d-flex flex-column">
                        <small class="text-muted mb-1"><i class="ri-user-line me-1"></i> Name</small>
                        <span class="fw-semibold">{{ $appeal->borrower_name }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="appeal-meta-block d-flex flex-column">
                        <small class="text-muted mb-1"><i class="ri-id-card-line me-1"></i> Loan ID</small>
                        <span class="fw-semibold">{{ 'LN-' . str_pad($appeal->loan_id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="appeal-meta-block d-flex flex-column">
                        <small class="text-muted mb-1"><i class="ri-barcode-box-line me-1"></i> Loan Reference ID</small>
                        <span class="fw-semibold">{{ $appeal->reference_number }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="appeal-meta-block d-flex flex-column">
                        <small class="text-muted mb-1"><i class="ri-time-line me-1"></i> Appeal Submission</small>
                        <span class="fw-semibold">{{ $appeal->formatted_date }}</span>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <small class="text-muted mb-2 d-block"><i class="ri-message-2-line me-1"></i> Appeal Reason</small>
                <div class="p-3 border rounded-3 appeal-reason-box">
                    <p class="mb-0">{{ $appeal->reason ?? 'No reason provided' }}</p>
                </div>
            </div>

            <div class="mb-4">
                <small class="text-muted mb-2 d-block"><i class="ri-attachment-2 me-1"></i> Uploaded Proof</small>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ asset('storage/' . $appeal->uploaded_proof ) }}" data-lightbox="appeal-proof-{{ $appeal->appeal_id }}" data-title="Appeal Uploaded Proof" class="appeal-proof-link rounded-4 overflow-hidden">
                        <img src="{{ asset('storage/' . $appeal->uploaded_proof ) }}" 
                             alt="Proof Screenshot" 
                             class="img-fluid rounded-4" 
                             style="width: 320px; height: 320px; object-fit: cover; cursor: zoom-in;">
                    </a>
                </div>
            </div>

            <div class="text-end">
                <button class="btn btn-primary-custom received_appeal" {{ $appeal->receive_status == 0 ? '' : 'hidden' }} value="{{ $appeal->appeal_id  }}">
                    <i class="ri-check-double-line me-1"></i> Mark as Received
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
