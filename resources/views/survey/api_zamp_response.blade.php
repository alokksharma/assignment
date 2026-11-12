<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZAMP API Sync Status</title>

    <style>
        * { box-sizing: border-box; }
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
            max-width: 560px;
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
        .dashboard-btn {
            display: inline-block;
            width: 100%;
            padding: 13px 20px;
            border: none;
            border-radius: 10px;
            background: #2563eb;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        .dashboard-btn:hover {
            background: #1d4ed8;
        }
        .nav-row {
            margin-top: 18px;
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }
        .secondary {
            background: #e5e7eb;
            color: #111827;
        }
        .secondary:hover {
            background: #d1d5db;
        }
        .next-btn {
            background: #16a34a;
        }
        .next-btn:hover {
            background: #15803d;
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
            .card { padding: 24px 18px; }
            .stats { grid-template-columns: 1fr; }
            .nav-row { flex-direction: column; }
            .dashboard-btn { width: 100%; }
        }
    </style>
</head>
<body>
  <div id="loader" class="loader-overlay">
    <div class="spinner"></div>
    <div class="loader-text">Syncing ZAMP survey data, please wait...</div>
  </div>

  <div class="card">
    <div class="icon">✓</div>

    <h1>ZAMP Survey Sync Status</h1>

    <p class="message">
      {{ $content ?? 'Survey sync completed.' }}
    </p>

    <div class="stats">
      <div class="stat">
        <span class="stat-value">{{ $updatedCount ?? 0 }}</span>
        <span class="stat-label">Updated Count</span>
      </div>

      <div class="stat">
        <span class="stat-value">{{ $requestCount ?? 0 }}</span>
        <span class="stat-label">Request Count</span>
      </div>
    </div>

    @if (!empty($paginator))
      <p>{{ $content ?? 'Survey sync completed.' }} (page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }})</p>

      @if($paginator->hasMorePages())
        <div class="nav-row">
          <form method="GET" action="{{ $full_url ?? url()->full() }}">
            <input type="hidden" name="page" value="{{ $paginator->currentPage() + 1 }}">
            @if(!empty($supplierId))
              <input type="hidden" name="supplier_id" value="{{ $supplierId }}">
            @endif
            <button type="submit" class="dashboard-btn next-btn">Load next page</button>
          </form>
        </div>
      @else
        <p>All pages processed.</p>
      @endif
    @endif

    <div class="nav-row">
      <a href="{{ route('dashboard') }}" class="dashboard-btn secondary">Return to Dashboard</a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const loader = document.getElementById('loader');
      const nextButton = document.querySelector('.next-btn');

      if (loader && nextButton) {
        nextButton.addEventListener('click', function () {
          loader.classList.add('active');
        });
      }
    });
  </script>
</body>
</html>
