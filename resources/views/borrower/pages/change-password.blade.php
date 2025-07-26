@extends('borrower.app')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .form-control[readonly] {
            background-color: #f9fafb;
            cursor: not-allowed;
        }
    </style>

@endsection

@section('content')
    <div class="d-flex min-vh-100 bg-light">
        @include('borrower.layouts.sidebar')

        <div class="main-content flex-grow-1 p-4">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-5">
                        <div class="card shadow-sm rounded-3">
                            <div class="card-body">
                                <div class="position-relative mb-4">
                                    <button type="button" data-url="/profile"
                                        class="btn small position-absolute start-0 top-50 translate-middle-y p-0">
                                        <i class="ri-arrow-go-back-line"></i> Back
                                    </button>
                                    <h4 class="text-center text-primary m-0">Change Password</h4>
                                </div>
                                <form id="change-password-form" data-action="{{ route('borrower.change-password.update') }}"
                                    data-logout="{{ route('logout') }}">
                                    @csrf



                                    <div class="mb-3 position-relative">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="current_password"
                                                name="current_password" required>
                                            <span class="input-group-text toggle-password" data-target="#current_password">
                                                <i class="ri-eye-line"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mb-3 position-relative">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="new_password"
                                                name="new_password" required>
                                            <span class="input-group-text toggle-password" data-target="#new_password">
                                                <i class="ri-eye-line"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mb-3 position-relative">
                                        <label for="new_password_confirmation" class="form-label">Confirm New
                                            Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="new_password_confirmation"
                                                name="new_password_confirmation" required>
                                            <span class="input-group-text toggle-password"
                                                data-target="#new_password_confirmation">
                                                <i class="ri-eye-line"></i>
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Password strength criteria --}}
                                    <div class="mb-4">
                                        <label class="form-label">Password must include:</label>
                                        <ul class="list-unstyled ps-3" id="password-criteria">
                                            <li data-criteria="length"><i class="ri-checkbox-blank-circle-line me-2"></i>
                                                Minimum 8 characters</li>
                                            <li data-criteria="upperlower"><i
                                                    class="ri-checkbox-blank-circle-line me-2"></i> Uppercase and lowercase
                                                letters</li>
                                            <li data-criteria="number"><i class="ri-checkbox-blank-circle-line me-2"></i> At
                                                least one number</li>
                                            <li data-criteria="special"><i class="ri-checkbox-blank-circle-line me-2"></i>
                                                At least one special character</li>
                                        </ul>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Change Password</button>
                                    </div>
                                </form>


                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection