@if ($loanStatus == 999 || $loanStatus == 0)
    {{-- Not applied for loan --}}
    <div class="empty-state-icon">
        <i class="ri-money-dollar-circle-line"></i>
    </div>
    <h2 class="h3 fw-bold mb-3">Hi, {{ Auth::user()->firstname }}! {{ $loanStatus == 0 ? 'You Have a Pending Loan Application' : "You Haven't Applied for a Loan Yet." }}</h2>
    <p class="text-muted mb-4 fs-5">{{$loanStatus == 0 ? "You're just a few steps away from completing your application, Click below to continue." : "No worries! It's quick and easy to get started. Apply for a loan today!"}}</p>
    <button onclick="window.location.href='{{ url('/apply-loan') }}'" class="btn btn-primary-custom btn-lg">
        <i class="ri-{{ $loanStatus == 0 ? 'arrow-right' :'add-circle' }}-line me-2"></i> {{ $loanStatus == 0 ? 'Continue Your Application' : 'Apply for a Loan'}}
    </button>

@elseif ($loanStatus == 1)
    {{-- Submitted / For Approval --}}
    <div class="empty-state-icon">
        <i class="ri-time-line"></i>
    </div>
    <h2 class="h3 fw-bold mb-3 text-primary-custom">Your Loan is Being Processed!</h2>
    <p class="text-muted fs-5">Thanks for your patience. Your application is under review.</p>

@elseif ($loanStatus == 2)
    {{-- Draft / In Progress --}}
    <div class="empty-state-icon">
        <i class="ri-error-warning-line"></i>
    </div>
    <h2 class="h3 fw-bold mb-3 text-warning">Your Loan Application is Pending!</h2>
    <p class="text-muted fs-5">We are waiting for some documents or information to complete your application.</p>

@elseif ($loanStatus == 3)
    {{-- Rejected --}}
    <div class="empty-state-icon">
        <i class="ri-close-circle-line text-danger"></i>
    </div>
    <h2 class="h3 fw-bold mb-3 text-danger">We're Sorry, Your Loan Was Not Approved</h2>
    <p class="text-muted fs-5">Unfortunately, your loan was declined. Call us at <strong>(+63) 912 345 6789</strong>.</p>

@elseif ($loanStatus == 4)
    {{-- Approved --}}
    <div class="empty-state-icon">
        <i class="ri-check-double-line text-success"></i>
    </div>
    <h2 class="h3 fw-bold mb-3 text-success">Congratulations, Your Loan is Approved!</h2>
    <p class="text-muted fs-5">Great news! Your loan has been approved. Check your bank frequently.</p>

     @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
        <script src="{{ asset('js/components/confetti.js') }}"></script>
    @endpush
@endif
