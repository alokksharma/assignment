<div class="sidebar">
    <div class="scrollbar-content sidebar-wrapper">
        <div class="user">
            <div class="photo">
                <img src="{{ asset('assets/img/profile.jpg') }}">
            </div>
            <div class="info">
                <a class="#" data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                    <span>
                        Project Survey
                    </span>
                </a>
                <div class="clearfix"></div>
            </div>
        </div>

        <ul class="nav">
            @can('dashboard')
            <li class="nav-item @if (request()->routeIs('dashboard')) active @endif">
                <a href="{{ route('dashboard') }}">
                    <i class="la la-dashboard"></i>
                    <p>Dashboard</p>
                    {{-- <span class="badge badge-count">5</span> --}}
                </a>
            </li>
            @endcan
            @can('show-role')
                <li class="nav-item @if (request()->routeIs('roles.index')) active @endif">
                    <a href="{{ route('roles.index') }}">
                        <i class="la la-user"></i>
                        <p>Role List</p>
                        {{-- <span class="badge badge-count">14</span> --}}
                    </a>
                </li>
            @endcan
             @can('view-user')
                <li class="nav-item @if (request()->routeIs('companies.invite-list')) active @endif">
                    <a href="{{ route('companies.invite-list') }}">
                        <i class="la la-users"></i>
                        <p>User List</p>
                        {{-- <span class="badge badge-count">14</span> --}}
                    </a>
                </li>
            @endcan
            @can('view-vendor')
            <li class="nav-item @if (request()->routeIs('vendors.index')) active @endif">
                <a href="{{ route('vendors.index') }}">
                    <i class="la la-table"></i>
                    <p>Vendor List</p>
                    {{-- <span class="badge badge-count">14</span> --}}
                </a>
            </li>
            @endcan
            @can('view-vendor')
            <li class="nav-item @if (request()->routeIs('languages')) active @endif">
                <a href="{{ route('languages') }}">
                    <i class="la la-language"></i>
                    <p>Languages</p>
                    {{-- <span class="badge badge-count">8</span> --}}
                </a>
            </li>
            @endcan
            @can('view-countries')
            <li class="nav-item @if (request()->routeIs('countries')) active @endif">
                <a href="{{ route('countries') }}">
                    <i class="la la-map"></i>
                    <p>Countries</p>
                    {{-- <span class="badge badge-count">8</span> --}}
                </a>
            </li>
            @endcan
             @can('view-qualififcations')
            <li class="nav-item @if (request()->routeIs('questions')) active @endif">
                <a href="{{ route('questions') }}">
                    <i class="la la-question"></i>
                    <p>Qualification</p>
                    {{-- <span class="badge badge-count">8</span> --}}
                </a>
            </li>
            @endcan
             @can('view-surveys')
            <li class="nav-item @if (request()->routeIs('surveys')) active @endif">
                <a href="{{ route('surveys') }}">
                    <i class="la la-search"></i>
                    <p>Surveys</p>
                    {{-- <span class="badge badge-count">8</span> --}}
                </a>
            </li>
            @endcan
            @can('view-surveys-projects')
            <li class="nav-item @if (request()->routeIs('survey.project')) active @endif">
                <a href="{{ route('survey.project') }}">
                    <i class="la la-list"></i>
                    <p>Surveys Projects</p>
                    {{-- <span class="badge badge-count">8</span> --}}
                </a>
            </li>
            @endcan
            @can('view-vendor-assign-projects')
            <li class="nav-item @if (request()->routeIs('vendor.project.assign.list')) active @endif">
                <a href="{{ route('vendor.project.assign.list') }}">
                    <i class="la la-list"></i>
                    <p>Vendor Projects Assignment</p>
                    {{-- <span class="badge badge-count">8</span> --}}
                </a>
            </li>
            @endcan
            @can( 'data-reconcile-create')
              <li class="nav-item @if (request()->routeIs('data.reconcile')) active @endif">
                <a href="{{ route('data.reconcile') }}">
                    <i class="la la-pencil-square"></i>
                    <p>Data Reconcile</p>
                    {{-- <span class="badge badge-count">8</span> --}}
                </a>
            </li>
            @endcan
            @can('report-export')
             <li class="nav-item @if (request()->routeIs('vendor.project.report')) active @endif">
                <a href="{{ route('vendor.project.report') }}">
                    <i class="la la-external-link"></i>
                    <p>Report</p>
                    {{-- <span class="badge badge-count">8</span> --}}
                </a>
            </li>
            @endcan
            {{-- logout button --}}

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <li class="nav-item update-pro mb-4">
                    <button type="submit">
                        <i class="la la-hand-pointer-o"></i>
                        <p>Logout</p>
                    </button>
                </li>
            </form>
            {{-- end logout button --}}

        </ul>
    </div>
</div>
