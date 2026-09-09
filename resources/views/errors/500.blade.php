<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Something Went Wrong</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #1f2937;
        }

        .card {
            width: 100%;
            max-width: 520px;
            margin: 20px;
            padding: 40px 32px;
            background: #fff;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        }

        .icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #fee2e2;
            color: #dc2626;
            font-size: 34px;
            font-weight: bold;
        }

        h1 {
            margin: 0 0 12px;
            font-size: 28px;
        }

        p {
            margin: 0 0 28px;
            color: #6b7280;
            line-height: 1.6;
        }

        .buttons {
            display: flex;
            gap: 12px;
        }

        .btn {
            flex: 1;
            display: inline-block;
            padding: 14px 20px;
            border-radius: 10px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            font-size: 15px;
        }

        .retry-btn {
            background: #16a34a;
        }

        .retry-btn:hover {
            background: #15803d;
        }

        .dashboard-btn {
            background: #2563eb;
        }

        .dashboard-btn:hover {
            background: #1d4ed8;
        }

        @media (max-width: 480px) {
            .card {
                padding: 30px 20px;
            }

            .buttons {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<div class="card">

    <div class="icon">!</div>

    <h1>Something Went Wrong</h1>

    <p>
        We're sorry, but something went wrong while processing your request.
        Please try again.
    </p>

    <div class="buttons">

        <!-- Retry same URL -->
        <a href="{{ url()->current() }}" class="btn retry-btn">
            Retry
        </a>

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="btn dashboard-btn">
            Dashboard
        </a>

    </div>

</div>

</body>
</html>
