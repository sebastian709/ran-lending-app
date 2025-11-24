@extends('borrower.app')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
@endsection
@section('content')
<div class="d-flex">
    <!-- Sidebar -->
    @include('borrower.layouts.sidebar')
    
@if ($payment_status->payment_status_id === 1)
    
    <div class="main-content flex-grow-1">
        <div style="margin:0; height:100vh; display:flex; justify-content:center; align-items:center; background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); color:white; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-align:center;">
            <div style="padding: 40px; background: rgba(0,0,0,0.4); border-radius: 20px; box-shadow: 0 8px 20px rgba(0,0,0,0.5); max-width: 500px;">
                <h1 style="font-size: 2.5rem; margin-bottom: 20px;">Thank You for Paying!</h1>
                <p style="font-size: 1.2rem; margin-bottom: 30px;">Please wait while the admin is verifying your payment.</p>
                <p style="font-size: 1rem; font-style: italic;">- Ran Serenity</p>
                <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem; margin-top: 20px;">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
@elseif($payment_status->payment_status_id === 4)
    <div class="main-content flex-grow-1">
        <div style="margin:0; height:100vh; display:flex; justify-content:center; align-items:center; background: linear-gradient(135deg, #ff7e5f, #feb47b); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color:white; text-align:center;">

            <div style="padding: 40px; background: rgba(0,0,0,0.5); border-radius: 20px; box-shadow: 0 8px 20px rgba(0,0,0,0.5); max-width: 500px;">
                <h1 style="font-size: 2.5rem; margin-bottom: 20px;">Appeal Submitted!</h1>
                <p style="font-size: 1.2rem; margin-bottom: 30px;">Please wait while the admin is processing your appeal.</p>
                <p style="font-size: 1rem; font-style: italic;">- Ran Serenity</p>
                <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem; margin-top: 20px;">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <!-- Bootstrap 5 JS Bundle -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        </div>
    </div>

@endif

</div>




@endsection

