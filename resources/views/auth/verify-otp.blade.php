<!-- login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RAN Lending</title>
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
    <main class="flex-grow-1 d-flex align-items-center justify-content-center py-5">
        <div class="w-100" style="max-width: 400px;" id="forgot-step2-container">
            <div class="text-center mb-4">
                <a href="#" class="font-pacifico text-primary-custom text-decoration-none" style="font-size: 2.5rem;">RAN Lending</a>
                <h1 class="h2 fw-bold mb-2">Enter Verification Code</h1>
                <p class="text-muted">We've sent a code to your phone ending in ***1234</p>
            </div>

            <div class="form-container rounded-custom p-4">
                <form id="forgot-step2-form">
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

                    <button type="submit" class="btn btn-primary-custom w-100 fw-medium">Verify Code</button>
                    
                    <div class="text-center mt-3">
                        <button type="button" id="resend-code-btn" class="btn btn-link text-primary-custom p-0 small text-decoration-none">
                            <i class="ri-refresh-line me-1"></i>
                            Resend Code
                            <span id="timer" class="text-muted">(59s)</span>
                        </button>
                    </div>
                </form>

                <hr class="my-4">
                <button id="back-to-login-btn2" class="btn btn-link w-100 text-muted text-decoration-none">
                    <i class="ri-arrow-left-line me-1"></i>
                    Back to Login
                </button>
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
</body>
</html>
