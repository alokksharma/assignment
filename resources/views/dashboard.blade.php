@extends('layouts.main')
@section('content')

<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">Dashboard </h4>
        <div class="row">
            @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('SuperAdmin'))
            <div class="col-md-3">
                <div class="card card-stats card-warning">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="la la-bar-chart"></i>
                                </div>
                            </div>
                            <div class="col-7 d-flex align-items-center">
                                <div class="numbers">
                                    <p class="card-category">All Surveys</p>
                                    <h4 class="card-title">{{$surveysCount['total']}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats card-success">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="la la-map"></i>
                                </div>
                            </div>
                            <div class="col-7 d-flex align-items-center">
                                <div class="numbers">
                                    <p class="card-category">Total Countries</p>
                                    <h4 class="card-title">{{$countriesCount}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats card-primary">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="la la-language"></i>
                                </div>
                            </div>
                            <div class="col-7 d-flex align-items-center">
                                <div class="numbers">
                                    <p class="card-category">All Languages</p>
                                    <h4 class="card-title">{{$languagesCount}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats card-danger">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="la la-question"></i>
                                </div>
                            </div>
                            <div class="col-7 d-flex align-items-center">
                                <div class="numbers">
                                    <p class="card-category">All Questions</p>
                                    <h4 class="card-title">{{$questionsCount}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @elseif (auth()->user()->hasRole('Vendor'))
                <div class="col-md-3">
                    <div class="card card-stats card-warning">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="la la-adjust"></i>
                                    </div>
                                </div>
                                <div class="col-7 d-flex align-items-center">
                                    <div class="numbers">
                                        <p class="card-category">All Project Assigned</p>
                                        <h4 class="card-title">{{$projectCount->total_count}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endif
        </div>
        @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('SuperAdmin'))
        <div class="row row-card-no-pd">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <p class="fw-bold mt-1">Sync Countries</p>
                        <a href="{{route('countries.sync')}}" class="btn btn-primary btn-full text-left mt-3 mb-3"><i class="la la-plus"></i> Add Countries</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <p class="fw-bold mt-1">Sync Languages</p>
                        <a href="{{route('languages.sync')}}" class="btn btn-primary btn-full text-left mt-3 mb-3"><i class="la la-plus"></i> Add Languages</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <p class="fw-bold mt-1">Sync Questions</p>
                        <a href="{{route('questions.sync')}}" class="btn btn-primary btn-full text-left mt-3 mb-3"><i class="la la-plus"></i> Add Questions</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <p class="fw-bold mt-1">Sync Surveys</p>
                        <a href="{{route('surveys.sync')}}" class="btn btn-primary btn-full text-left mt-3 mb-3"><i class="la la-plus"></i> Add Questions</a>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="row row-card-no-pd">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p class="fw-bold mt-1">Total Project</p>
                        <h4><b>{{$projectCount->total_count}}</b></h4>
                    </div>
                    <div class="card-footer">
                        <ul class="nav">
                            <li class="nav-item">{{$totalVendorAssign}} Total Assign Vendor</li>
                            <li class="nav-item">{{$totalAssignProject}} Total Assign Project</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <div class="progress-card">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Complete</span>
                                <span class="text-muted fw-bold"> {{$projectCount->complete_count}}</span>
                            </div>
                            <div class="progress mb-2" style="height: 7px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{\App\Models\Proof::projectPercentage($projectCount->total_count, $projectCount->complete_count)}}%" aria-valuenow="{{\App\Models\Proof::projectPercentage($projectCount->total_count, $projectCount->complete_count)}}" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-placement="top" title="{{\App\Models\Proof::projectPercentage($projectCount->total_count, $projectCount->complete_count)}}%"></div>
                            </div>
                        </div>
                        <div class="progress-card">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Terminate</span>
                                <span class="text-muted fw-bold">{{$projectCount->terminate_count}}</span>
                            </div>
                            <div class="progress mb-2" style="height: 7px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{\App\Models\Proof::projectPercentage($projectCount->total_count, $projectCount->terminate_count)}}%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-placement="top" title="{{\App\Models\Proof::projectPercentage($projectCount->total_count, $projectCount->terminate_count)}}%"></div>
                            </div>
                        </div>
                        <div class="progress-card">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Quotafull</span>
                                <span class="text-muted fw-bold">{{$projectCount->quotafull_count}}</span>
                            </div>
                            <div class="progress mb-2" style="height: 7px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{\App\Models\Proof::projectPercentage($projectCount->total_count, $projectCount->quotafull_count)}}%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-placement="top" title="{{\App\Models\Proof::projectPercentage($projectCount->total_count, $projectCount->quotafull_count)}}%"></div>
                            </div>
                        </div>
                        <div class="progress-card">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Quality</span>
                                <span class="text-muted fw-bold">{{$projectCount->quality_count}}</span>
                            </div>
                            <div class="progress mb-2" style="height: 7px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{\App\Models\Proof::projectPercentage($projectCount->total_count, $projectCount->quality_count)}}%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-placement="top" title="{{\App\Models\Proof::projectPercentage($projectCount->total_count, $projectCount->quality_count)}}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('SuperAdmin'))
            <div class="col-md-3">
                <div class="card card-stats">
                    <div class="card-body">
                        <p class="fw-bold mt-1">Project Status</p>
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center icon-warning">
                                    <i class="la la-play text-warning"></i>
                                </div>
                            </div>
                            <div class="col-7 d-flex align-items-center">
                                <div class="numbers">
                                    <p class="card-category">Live Project</p>
                                    <h4 class="card-title">{{$surveysCount ? $surveysCount['live'] : 0}}</h4>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="la la-pause text-primary"></i>
                                </div>
                            </div>
                            <div class="col-7 d-flex align-items-center">
                                <div class="numbers">
                                    <p class="card-category">Pause Project</p>
                                    <h4 class="card-title">{{$surveysCount ? $surveysCount['pause'] : 0}}</h4>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @endif
        </div>
                    {{-- <div class="card-footer">
                        <ul class="nav">
                            <li class="nav-item"><a class="btn btn-default btn-link" href="#"><i class="la la-history"></i> History</a></li>
                            <li class="nav-item ml-auto"><a class="btn btn-default btn-link" href="#"><i class="la la-refresh"></i> Refresh</a></li>
                        </ul>
                    </div> --}}
    </div>
</div>

@endsection


