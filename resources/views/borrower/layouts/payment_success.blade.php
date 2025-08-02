@extends('borrower.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
<style>
    .success-wrapper {
        max-width: 600px;
        margin: 0 auto;
        padding-top: 4rem;
        text-align: center;
    }

    .success-icon {
        font-size: 4rem;
        color: #28a745;
    }

    .success-card {
        border-radius: 1rem;
        box-shadow: 0 0.25rem 1rem rgba(0, 0, 0, 0.05);
    }
</style>
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
                Our team will verify the transaction within <strong>1–2 business days</strong>.
            </p>

            <a href="/payment-history" class="btn btn-primary">
                <i class="ri-time-line me-1"></i> Go to Payment History
            </a>
            <p class="text-muted mt-4">
                Redirecting to <strong>Payment History</strong> in <span id="countdown">15</span> seconds...
            </p>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script src="{{ asset('js/components/confetti.js') }}"></script>
<script>
    let seconds = 15;
    const countdownEl = document.getElementById('countdown');
    const redirectUrl = "/payment-history";

    const interval = setInterval(() => {
        seconds--;
        if (countdownEl) countdownEl.textContent = seconds;
        if (seconds <= 0) {
            clearInterval(interval);
            window.location.href = redirectUrl;
        }
    }, 1000);
</script>
@endsection