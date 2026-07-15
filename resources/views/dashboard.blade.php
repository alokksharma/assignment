@extends('layouts.main')
@section('content')
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
                                    <i class="la la-bar-chart"></i>
                                </div>
                            </div>
                            <div class="col-7 d-flex align-items-center">
                                <div class="numbers">
                                    <p class="card-category">All Surveys</p>
                                    <h4 class="card-title">{{$surveysCount}}</h4>
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
        </div>
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
                    {{-- <div class="card-footer">
                        <ul class="nav">
                            <li class="nav-item"><a class="btn btn-default btn-link" href="#"><i class="la la-history"></i> History</a></li>
                            <li class="nav-item ml-auto"><a class="btn btn-default btn-link" href="#"><i class="la la-refresh"></i> Refresh</a></li>
                        </ul>
                    </div> --}}
                </div>
        </div>
    </div>
</div>
@endsection


