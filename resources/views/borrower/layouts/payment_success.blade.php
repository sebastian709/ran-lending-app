@extends('borrower.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/borrower/payment-success.css') }}">
@endsection

@section('content')
<div class="container py-5">
    <div class="success-wrapper">
        <div class="card success-card p-5">
            <div class="mb-4">
                <i class="ri-check-line success-icon"></i>
            </div>

            <h3 class="fw-bold mb-3">Thank you!</h3>
            <p class="text-muted mb-4">
                We've received your payment submission.<br>
                Our team will verify the transaction within <strong>1-2 business days</strong>.
            </p>

            <a href="/repayment-schedule" class="btn btn-primary">
                <i class="ri-time-line me-1"></i> Go to My Loan
            </a>
            <p class="text-muted mt-4">
                Redirecting to <strong>My Loan</strong> in <span id="countdown">15</span> seconds...
            </p>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script src="{{ asset('js/components/confetti.js') }}"></script>
<script src="{{ asset('js/borrower/payment-success.js') }}"></script>
@endsection
