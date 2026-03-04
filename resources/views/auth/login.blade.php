<!-- login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <a href="{{ url('/lending') }}" class="d-flex align-items-center text-decoration-none text-muted">
                    <i class="ri-arrow-left-line me-2"></i>
                    <span>Back to Home</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow-1 d-flex align-items-center justify-content-center py-5">
        <div class="w-100" style="max-width: 400px;" id="login-container">
            <div class="text-center mb-4">
                <a href="{{ url('/') }}" class="font-pacifico text-primary-custom text-decoration-none" style="font-size: 2.5rem;">RAN Lending</a>
                <h1 class="h2 fw-bold mb-2">Welcome to RAN Lending</h1>
                <p class="text-muted">Experience hassle-free loans with peace of mind.</p>
            </div>

            <div class="form-container rounded-custom p-4">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label fw-medium">Email</label>
                        <input type="email" id="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="Enter your email address"
                               value="{{ old('email') }}" required autofocus>
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-medium">Password</label>
                        <div class="position-relative">
                            <input type="password" id="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Enter your password" required>
                            <button type="button" class="password-toggle border-0 bg-transparent p-0" aria-label="Show password" aria-pressed="false">
                                <i class="ri-eye-line text-muted"></i>
                            </button>
                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check d-flex align-items-center">
                            <input type="checkbox" id="remember" name="remember" value="1"
                                   class="form-check-input me-2" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember" class="form-check-label small text-muted">
                                Remember me for 15 days
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 fw-medium">Login</button>

                    <div class="text-center mt-3">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="btn btn-link text-primary-custom p-0 small text-decoration-none">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="mt-4 text-center">
                <p class="text-muted mb-2">Don't have an account?</p>
                <a href="{{ route('register') }}" class="btn btn-outline-secondary-custom w-100 fw-medium">Create Account</a>
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/auth.js') }}"></script>
@if (session('session_conflict_message'))
<script>
    window.addEventListener('DOMContentLoaded', function () {
        const conflictMessage = @json(session('session_conflict_message'));
        const isDark = document.body.classList.contains('dark-mode')
            || document.body.classList.contains('site-dark')
            || document.body.classList.contains('landing-dark');
        if (window.Swal && typeof window.Swal.fire === 'function') {
            Swal.fire({
                icon: 'info',
                title: 'Session Ended',
                text: conflictMessage,
                confirmButtonText: 'OK',
                background: isDark ? '#0f172a' : '#ffffff',
                color: isDark ? '#e2e8f0' : '#111827',
                confirmButtonColor: isDark ? '#3b82f6' : '#2563eb'
            });
            return;
        }
        alert(conflictMessage);
    });
</script>
@endif
</body>
</html>
