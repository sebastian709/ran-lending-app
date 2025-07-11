@extends('borrower.app')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
@endsection

@section('content')

<div class="d-flex flex-grow-1 justify-content-center align-items-center">
    <div class="thank-you-message text-center position-relative">
        <div class="icon">
            <i class="ri-checkbox-circle-line"></i>
        </div>
        <h2 class="text-primary-custom">Thank You for Your Loan Application!</h2>
        <p>We are currently reviewing your request. Our team will be in touch with you shortly for a brief interview to finalize the process. Please expect a call soon.</p>
        <a href="apply-loan" class="btn btn-primary-custom btn-back-home mt-3">
            Back to Home
        </a>
        <div class="progress mt-5">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
        </div>
    </div>
</div>

 @endsection
