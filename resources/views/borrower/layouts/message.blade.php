@extends('borrower.app')

@section('styles')
    
@endsection

@section('content')

<div class="d-flex flex-grow-1 justify-content-center align-items-center">
    <div class="thank-you-message text-center position-relative">
        <div class="icon">
            <i class="ri-checkbox-circle-line text-success"  style="font-size: 68px;"></i>
        </div>
        <h2 class="text-primary-custom">Thank You for Your Loan Application!</h2>
        <p>We are currently reviewing your request. Our team will be in touch with you shortly for a brief interview to finalize the process. Please expect a call soon.</p>
        <a href="home" class="btn btn-primary-custom btn-back-home mt-3">
            Back to Home
        </a>
        <div class="progress mt-5">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
        </div>
    </div>
</div>

 @endsection
