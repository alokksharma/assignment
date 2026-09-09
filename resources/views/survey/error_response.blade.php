<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Survey Sync Failed</title>

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
      padding: 36px 32px;
      background: #fff;
      border-radius: 16px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .icon {
      width: 64px;
      height: 64px;
      margin: 0 auto 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      background: #fee2e2;
      color: #dc2626;
      font-size: 32px;
      font-weight: bold;
    }

    h1 {
      margin: 0 0 10px;
      font-size: 26px;
      color: #111827;
    }

    .message {
      margin: 0 0 24px;
      color: #6b7280;
      line-height: 1.6;
    }

    .error-box {
      margin-bottom: 28px;
      padding: 16px;
      border-radius: 10px;
      background: #fef2f2;
      border: 1px solid #fecaca;
      color: #b91c1c;
      font-size: 14px;
      text-align: left;
    }

    .error-label {
      display: block;
      margin-bottom: 6px;
      font-weight: 700;
    }

    .dashboard-btn {
      display: inline-block;
      width: 100%;
      padding: 13px 20px;
      border-radius: 10px;
      background: #2563eb;
      color: #fff;
      text-decoration: none;
      font-size: 16px;
      font-weight: 600;
      transition: background 0.2s ease, transform 0.2s ease;
    }

    .dashboard-btn:hover {
      background: #1d4ed8;
      transform: translateY(-1px);
    }

    @media (max-width: 480px) {
      .card {
        padding: 28px 20px;
      }
    }
  </style>
</head>

<body>

  <div class="card">

    <div class="icon">!</div>

    <h1></h1>

    <p class="message">
      We couldn't sync at this time.
      Please try again later.
    </p>

    <div class="error-box">
      <span class="error-label">Error</span>
      {{$error}}
    </div>

    <a href="{{route('dashboard')}}" class="dashboard-btn">
      Return to Dashboard
    </a>

  </div>

</body>
</html>
