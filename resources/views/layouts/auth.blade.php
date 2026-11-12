<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <style>
            body {
                margin: 0;
                min-height: 100vh;
                font-family: 'Nunito', sans-serif;
                background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            }

            .auth-shell {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px;
            }

            .auth-card {
                width: 100%;
                max-width: 480px;
                background: #ffffff;
                border-radius: 16px;
                box-shadow: 0 18px 45px rgba(15, 23, 42, 0.12);
                padding: 32px 28px;
            }

            .auth-card h4 {
                font-weight: 800;
                color: #0f172a;
            }

            .auth-card .form-control {
                border-radius: 10px;
                padding: 11px 12px;
                border: 1px solid #dfe7f1;
                transition: all 0.2s ease;
            }

            .auth-card .form-control:focus {
                border-color: #2563eb;
                box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
            }

            .auth-card .btn {
                border-radius: 10px;
                font-weight: 600;
                padding: 10px 18px;
            }

            .text-danger {
                color: #dc3545 !important;
            }

            .text-muted {
                color: #6b7280 !important;
            }
        </style>
    </head>
    <body>
        <div class="auth-shell">
            <div class="auth-card">
                @yield('content')
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        @stack('scripts')
    </body>
</html>
