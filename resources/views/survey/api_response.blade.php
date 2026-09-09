<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Survey Sync Status</title>

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
    }

    .stats {
      display: grid;
      grid-template-columns: 1fr 1fr;
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
      margin-top: 12px;
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

    .sync-btn {
      background: #16a34a;
    }

    .sync-btn:hover {
      background: #15803d;
    }

    /* Loader */
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
      to {
        transform: rotate(360deg);
      }
    }

    @media (max-width: 480px) {
      .card {
        padding: 24px;
      }

      .stats {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>

  <!-- Loading Overlay -->
  <div id="loader" class="loader-overlay">
    <div class="spinner"></div>
    <div class="loader-text">Syncing survey, please wait...</div>
  </div>

  <div class="card">

    <div class="icon">✓</div>

    <h1>Survey Sync Status</h1>

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

    <p>{{ $content }} (page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }})</p>

    @if($paginator->hasMorePages())
        <form id="nextPageForm" method="GET" action="{{ route('surveys.sync') }}">
            <input type="hidden" name="page" value="{{ $paginator->currentPage() + 1 }}">
            @if($supplierId)
                <input type="hidden" name="supplier_id" value="{{ $supplierId }}">
            @endif
            <button id="nextPageBtn" class="dashboard-btn sync-btn" type="submit">Load next page</button>
        </form>
    @else
        <p>All pages processed.</p>
    @endif

    <a href="{{ route('dashboard') }}" class="dashboard-btn">
      Return to Dashboard
    </a>

  </div>

  <script>
  function showLoader(event) {
    event.preventDefault();

    const button = document.getElementById('syncButton');
    const loader = document.getElementById('loader');
    const url = button.href;

    button.style.pointerEvents = 'none';
    button.style.opacity = '0.6';
    button.innerHTML = 'Syncing...';

    loader.classList.add('active');
    window.location.href = url;
  }

  // Hook up the "Load next page" form the same way
  const nextPageForm = document.getElementById('nextPageForm');
  if (nextPageForm) {
    nextPageForm.addEventListener('submit', function (event) {
      // don't preventDefault — let the GET form actually navigate,
      // just show the loader while it does
      const btn = document.getElementById('nextPageBtn');
      btn.disabled = true;
      btn.style.opacity = '0.6';
      btn.innerHTML = 'Loading...';

      document.getElementById('loader').classList.add('active');
    });
  }
</script>

</body>
</html>
