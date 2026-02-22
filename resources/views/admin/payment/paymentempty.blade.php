@extends('admin.container')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin/payment-page.css') }}">

<div class="container">
        
        <div class="container vh-100 d-flex justify-content-center align-items-center">
            <div class="text-center">
            <!-- Example placeholder image -->
            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076505.png" 
                alt="Empty State" 
                class="mb-4" 
                width="150">
            <h5 class="text-muted">You are all clean up! No pending payment as of the moment.</h5>
            </div>
        </div>
    </div>
@endsection

