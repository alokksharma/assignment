<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ZAMP API Sync Status</title>

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
      max-width: 620px;
      margin: 20px;
      padding: 32px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      text-align: center;
    }

    .icon {
      width: 64px;
      height: 64px;
      margin: 0 auto 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      background: #dcfce7;
      color: #16a34a;
      font-size: 32px;
      font-weight: bold;
    }

    h1 {
      margin: 0 0 10px;
      font-size: 26px;
    }

    .message {
      margin-bottom: 28px;
      color: #6b7280;
      line-height: 1.6;
    }

    .stats {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 16px;
      margin-bottom: 24px;
    }

    .stat {
      padding: 20px;
      border-radius: 12px;
      background: #f8fafc;
      border: 1px solid #e5e7eb;
    }

    .stat-value {
      display: block;
      margin-bottom: 6px;
      font-size: 30px;
      font-weight: 700;
      color: #2563eb;
    }

    .stat-label {
      color: #6b7280;
      font-size: 14px;
    }

    .actions {
      display: flex;
      gap: 12px;
      justify-content: center;
      flex-wrap: wrap;
      margin-top: 12px;
    }

    .btn {
      display: inline-block;
      padding: 12px 18px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.2s ease;
    }

    .btn-primary {
      background: #2563eb;
      color: #fff;
    }

    .btn-primary:hover {
      background: #1d4ed8;
    }

    .btn-secondary {
      background: #e5e7eb;
      color: #111827;
    }

    .btn-secondary:hover {
      background: #d1d5db;
    }

    .pagination-meta {
      margin-top: 10px;
      font-size: 14px;
      color: #6b7280;
    }

    .loader-overlay {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 9999;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      background: rgba(255, 255, 255, 0.92);
    }

    .loader-overlay.active {
      display: flex;
    }

    .spinner {
      width: 48px;
      height: 48px;
      border: 5px solid #e5e7eb;
      border-top-color: #2563eb;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }

    .loader-text {
      margin-top: 16px;
      color: #374151;
      font-size: 16px;
      font-weight: 600;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    @media (max-width: 480px) {
      .card {
        padding: 24px 18px;
      }

      .stats {
        grid-template-columns: 1fr;
      }

      .actions {
        flex-direction: column;
      }

      .btn {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <div id="loader" class="loader-overlay">
    <div class="spinner"></div>
    <div class="loader-text">Loading next page, please wait...</div>
  </div>

  <div class="card">
    <div class="icon">✓</div>

    <h1>ZAMP API Sync Status</h1>

    <p class="message">
      {{ $content }}
    </p>

    <div class="stats">
      <div class="stat">
        <span class="stat-value">{{ $updatedCount }}</span>
        <span class="stat-label">Updated Count</span>
      </div>

      <div class="stat">
        <span class="stat-value">{{ $requestCount }}</span>
        <span class="stat-label">Request Count</span>
      </div>
    </div>

    @if (!empty($languages) && $languages->lastPage() > 1)
      <div class="pagination-meta">
        Page {{ $languages->currentPage() }} of {{ $languages->lastPage() }}
      </div>
    @endif

    <div class="actions">
      @if (!empty($nextPageUrl))
        <a href="{{ $nextPageUrl }}" class="btn btn-primary next-page-btn">Next Page</a>
      @endif

      <a href="{{ route('dashboard') }}" class="btn btn-secondary">Return to Dashboard</a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const loader = document.getElementById('loader');
      const nextPageBtn = document.querySelector('.next-page-btn');

      if (loader && nextPageBtn) {
        nextPageBtn.addEventListener('click', function () {
          loader.classList.add('active');
        });
      }
    });
  </script>
</body>
</html>
