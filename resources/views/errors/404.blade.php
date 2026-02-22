<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - RAN Lending</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32-ran.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16-ran.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
</head>
<body>
    <main class="error-page">
        <div class="brand-logo">
            <img src="{{ asset('favicon-32x32-ran.png') }}" alt="RAN Lending Logo">
            <span>RAN Lending</span>
        </div>

        <div class="error-card shadow-sm">
            <h1>404</h1>
            <h2>Page not found</h2>
            <p>The page you requested does not exist or may have been moved.</p>

            <a href="{{ url('/') }}" class="btn btn-primary-custom">
                <i class="bi bi-arrow-left"></i> Back to Home
            </a>
        </div>
    </main>

    <footer class="error-footer">
        &copy; {{ date('Y') }} RAN Lending
    </footer>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
