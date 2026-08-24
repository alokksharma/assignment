@extends('layouts.main')
@section('content')
<style>
    /* Responsive clock tweaks */
    .clock-card .digital-watch{font-size:clamp(1.1rem,4.5vw,2.4rem);}
    .clock-card .icon-big svg{width:48px;height:48px;}
    @media (max-width:576px){
        .clock-card .icon-big{display:flex; justify-content:center; margin-bottom:8px;}
        .clock-card .numbers{text-align:center;}
    }
</style>
<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">Dashboard </h4>
        <div class="row">
            @can('invite admin')
            <div class="col-md-3">
                <div class="card card-stats card-warning">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="la la-users"></i>
                                </div>
                            </div>
                            <div class="col-7 d-flex align-items-center">
                                <div class="numbers">
                                    <p class="card-category">All Users</p>
                                    <h4 class="card-title">{{$userCount}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
            <div class="col-md-3">
                <div class="card card-stats card-success">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="la la-bar-chart"></i>
                                </div>
                            </div>
                            <div class="col-7 d-flex align-items-center">
                                <div class="numbers">
                                    <p class="card-category">Short Url</p>
                                    <h4 class="card-title">{{$shortUrlsCount}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 clock-card">
                <div class="card card-stats">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="icon-big text-center" style="font-family: 'Nunito', sans-serif;">
                                    <!-- Inline SVG clock icon styled to match Nunito font aesthetic -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#4a5568" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path d="M12 7v5l3 2"></path>
                                    </svg>
                                    <div>
                                        {{-- <div style="font-weight:600; font-size:0.95rem; color:#334155;">Current Time</div> --}}
                                        <p id="clockDate">—</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8 d-flex align-items-center">
                                <div class="numbers">
                                    <h4 id="clock" class="digital-watch mb-0">00:00:00</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
      <script>
        function updateClock() {
            const now = new Date();

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            const time = `${hours}:${minutes}:${seconds}`;
            const date = new Intl.DateTimeFormat('en-US', {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            }).format(now);

            // update DOM using jQuery
            $('#clock').text(time);
            $('#clockDate').text(date);
        }

        // update every second
        $(document).ready(function() {
            updateClock(); // initial call
            setInterval(updateClock, 1000);
        });
    </script>
@endpush


