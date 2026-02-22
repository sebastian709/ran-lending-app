<!-- login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - RAN Lending</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32-ran.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16-ran.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
<div class="d-flex flex-column min-vh-100">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="container py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none text-muted">
                        <i class="ri-arrow-left-line me-2"></i>
                        <span>Back to Home</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main id="forgot-password-page"
            class="flex-grow-1 d-flex align-items-center justify-content-center py-5"
            data-send-url="{{ route('forgot.auth.send') }}"
            data-check-url="{{ route('forgot.auth.check') }}"
            data-change-url="{{ route('forgot.change.pass') }}">

            <!-- Forgot Password Step 1 -->
            <div class="w-100" style="max-width: 400px;" id="forgot-step1-container">
                <div class="text-center mb-4">
                    <a href="#" class="font-pacifico text-primary-custom text-decoration-none" style="font-size: 2.5rem;">RAN Lending</a>
                    <h1 class="h2 fw-bold mb-2">Forgot Password - Email Verification</h1>
                </div>

                <div class="form-container rounded-custom p-4">
                        <p class="text-muted mb-3">Enter the email address linked to your account:</p>
                        <div class="mb-3">
                            <div class="position-relative">
                                <input type="email" id="forgot-email" name="email" class="form-control email-input" placeholder="Enter your email address">
                                <div id="no-email-error" class="text-danger text-center  small mt-1 d-none">Email address not registered.</div>
                            </div>
                            <div id="forgot-email-error" class="text-danger small mt-1 d-none">Invalid email format .</div>
                        </div>

                        <button type="button" class="authgen btn btn-primary-custom w-100 fw-medium">Send Verification Code</button>
                        <p class="mt-3 small text-muted">We'll send a 6-digit code via email to verify your identity.</p>

                    <hr class="my-4">
                    <a href="{{ url('/login') }}" id="back-to-login-btn1" class="btn btn-link w-100 text-muted text-decoration-none">
                        <i class="ri-arrow-left-line me-1"></i>
                        Back to Login
                    </a>
                </div>
            </div>

            <!-- Forgot Password Step 2 -->
            <div class="w-100 d-none" style="max-width: 400px;" id="forgot-step2-container">
                <div class="text-center mb-4">
                    <a href="#" class="font-pacifico text-primary-custom text-decoration-none" style="font-size: 2.5rem;">RAN Lending</a>
                    <h1 class="h2 fw-bold mb-2">Enter Verification Code</h1>
                    <p class="text-muted small">We've sent a 6-digit verification code to your email.<br>This code is valid for 5 minutes.</p>
                </div>

                <div class="form-container rounded-custom p-4">
                        <div class="mb-4">
                            <div class="d-flex justify-content-center">
                                <input type="text" maxlength="1" class="otp-input" data-index="1">
                                <input type="text" maxlength="1" class="otp-input" data-index="2">
                                <input type="text" maxlength="1" class="otp-input" data-index="3">
                                <input type="text" maxlength="1" class="otp-input" data-index="4">
                                <input type="text" maxlength="1" class="otp-input" data-index="5">
                                <input type="text" maxlength="1" class="otp-input" data-index="6">
                            </div>
                            <div id="otp-error" class="text-danger small mt-3 text-center d-none">Invalid verification code. Please try again.</div>
                        </div>

                        <button type="button" id="authcheck" class="btn btn-primary-custom w-100 fw-medium">Verify Code</button>
                        
                        <div class="text-center mt-3">
                            <button type="button" class="authgen resend-code-btn btn btn-link text-primary-custom p-0 small text-decoration-none">
                                <i class="ri-refresh-line me-1"></i>
                                Resend Code
                                <span id="timer" class="text-muted">(59s)</span>
                            </button>
                        </div>

                    <hr class="my-4">
                    <button id="back-to-login-btn2" class="btn btn-link w-100 text-muted text-decoration-none">
                        <i class="ri-arrow-left-line me-1"></i>
                        Back to Login
                    </button>
                </div>
            </div>

            <!-- Forgot Password Step 3 -->
            <div class="w-100 d-none" style="max-width: 400px;" id="forgot-step3-container">
                <div class="text-center mb-4">
                    <a href="#" class="font-pacifico text-primary-custom text-decoration-none" style="font-size: 2.5rem;">RAN Lending</a>
                    <h1 class="h2 fw-bold mb-2">Set a New Password</h1>
                </div>

                <div class="form-container rounded-custom p-4">
                        <div class="mb-3">
                            <label for="new-password" class="form-label fw-medium">New Password</label>
                            <div class="position-relative">
                                <input type="password" id="new-password" class="form-control" placeholder="Enter new password">
                                <div class="password-toggle" data-for="new-password">
                                    <i class="ri-eye-line text-muted"></i>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="confirm-password" class="form-label fw-medium">Confirm Password</label>
                            <div class="position-relative">
                                <input type="password" id="confirm-password" class="form-control" placeholder="Confirm new password">
                                <div class="password-toggle" data-for="confirm-password">
                                    <i class="ri-eye-line text-muted"></i>
                                </div>
                            </div>
                            <div id="password-match-error" class="text-danger small mt-1 d-none">Passwords do not match.</div>
                        </div>

                        <div class="mt-3 bg-light p-3 rounded">
                            <p class="small fw-medium text-dark mb-2">Password must include:</p>
                            <ul class="list-unstyled small password-criteria mt-2">
                            <li data-rule="length" class="text-muted"></i>Minimum 8 characters</li>
                            <li data-rule="case" class="text-muted"></i>Uppercase and lowercase letters</li>
                            <li data-rule="number" class="text-muted"></i>At least one number</li>
                            <li data-rule="special" class="text-muted"></i>At least one special character</li>
                            </ul>
                        </div>

                        <button type="submit" id="changepass" class="btn btn-primary-custom w-100 fw-medium">Reset Password</button>

                    <hr class="my-4">
                    <button id="back-to-login-btn3" class="btn btn-link w-100 text-muted text-decoration-none">
                        <i class="ri-arrow-left-line me-1"></i>
                        Back to Login
                    </button>
                </div>
            </div>

            <!-- Success Message -->
            <div class="w-100 d-none" style="max-width: 400px;" id="success-container">
                <div class="text-center mb-4">
                    <a href="#" class="font-pacifico text-primary-custom text-decoration-none" style="font-size: 2.5rem;">RAN Lending</a>
                </div>

                <div class="form-container rounded-custom p-4 text-center">
                    <div class="success-icon">
                        <i class="ri-check-line"></i>
                    </div>
                    <h2 class="h3 fw-bold mb-3">Success!</h2>
                    <p class="text-muted mb-4">Your password has been changed. You can now log in with your new password.</p>
                    <a href="{{ url('/login') }}" id="back-to-login-success" class="btn btn-primary-custom w-100 fw-medium">Back to Login</a>
                </div>
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="bg-white py-3 border-top">
            <div class="container">
                <div class="text-center text-muted small">
                    &copy; 2025 RAN Lending. All rights reserved.
                </div>
            </div>
        </footer>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/auth.js') }}"></script>
<script src="{{ asset('js/auth/forgot-password.js') }}"></script>
</body>
</html>
