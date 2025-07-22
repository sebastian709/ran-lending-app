<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
</head>
<body>
    <div class="error-container">
        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="Empty State Icon">
        <h1 class="error-title">Oops!</h1>
        <p class="error-message">Looks like the shelf is empty right now.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Go Back Home
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</body>
</html>
