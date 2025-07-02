@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Verify OTP</h2>
    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf

        <input type="hidden" name="email" value="{{ old('email', $email) }}">

        <div class="mb-3">
            <label for="otp" class="form-label">Enter OTP</label>
            <input type="text" name="otp" id="otp" class="form-control" required autofocus>
            @error('otp')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Verify OTP</button>
    </form>
</div>
@endsection
