<div class="sidebar">
				<div class="scrollbar-inner sidebar-wrapper">
					<div class="user">
						<div class="photo">
							<img src="{{ asset('assets/img/profile.jpg') }}">
						</div>
						<div class="info">
							<a class="#" data-toggle="collapse" href="#collapseExample" aria-expanded="true">
								<span>
									Short URL
								</span>
							</a>
							<div class="clearfix"></div>

							{{-- <div class="collapse in" id="collapseExample" aria-expanded="true" style="">
								<ul class="nav">
									<li>
										<a href="#profile">
											<span class="link-collapse">My Profile</span>
										</a>
									</li>

								</ul>
							</div> --}}
						</div>
					</div>
					<ul class="nav">
						<li class="nav-item @if (request()->routeIs('dashboard')) active @endif">
							<a href="{{ route('dashboard') }}">
								<i class="la la-dashboard"></i>
								<p>Dashboard</p>
								{{-- <span class="badge badge-count">5</span> --}}
							</a>
						</li>
                        @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('SuperAdmin'))
                            <li class="nav-item @if (request()->routeIs('companies.invite-list')) active @endif">
							<a href="{{ route('companies.invite-list') }}">
								<i class="la la-table"></i>
								<p>Users List</p>
								{{-- <span class="badge badge-count">14</span> --}}
							</a>
						</li>
                        @endif

                        <li class="nav-item @if (request()->routeIs('short_urls.index')) active @endif">
                            <a href="{{ route('short_urls.index') }}">
                                <i class="la la-link"></i>
                                <p>Short URLs</p>
                                {{-- <span class="badge badge-count">8</span> --}}
                            </a>
                        </li>

                        {{-- logout button --}}

                        <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <li class="nav-item update-pro">
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
