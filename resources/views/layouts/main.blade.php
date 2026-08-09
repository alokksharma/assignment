<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>3AS API Portal Gateway</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
	<link rel="stylesheet" href="{{ asset('assets/css/ready.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        #targetSpec table {
            width: 100% !important;
            border-collapse: collapse;
        }

        #targetSpec table td,
        #targetSpec table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #targetSpec table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

		/* ===== Footer ===== */
html, body{
    height:100%;
}

.wrapper{
    min-height:100vh;
}

.main-panel{
    min-height:100vh;
    display:flex;
    flex-direction:column;
}

.main-panel > *:first-child{
    flex:1;
}

.footer{
    background:#ffffff;
    border-top:1px solid #e5e5e5;
    padding:15px 25px;
    margin-top:auto;
}

.footer .copyright{
    text-align:center;
    color:#6c757d;
    font-size:14px;
}

.footer a{
    color:#0d6efd;
    font-weight:600;
    text-decoration:none;
}
.footer .text-right {
    text-align: right !important;
}

.footer a:hover{
    text-decoration:underline;
}

		/* ===========================
   Particle Logo
=========================== */

.logo-header{
    position:relative;
}

.particle-logo{
    position:relative;
    width:130px;
    height:55px;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
    text-decoration:none;
}

#logoCanvas{
    position:absolute;
    inset:0;
    width:100%;
    height:100%;
}

.particle-logo span{
    position:relative;
    z-index:2;
    font-size:26px;
    font-weight:800;
    letter-spacing:2px;
    color:#ffffff;
    text-shadow:
        0 0 8px #00d4ff,
        0 0 15px #00d4ff,
        0 0 30px #00d4ff;
}

.project-row a{
    cursor:pointer;
    transition:.3s;
}

.project-row a:hover{
    background:#0056b3;
    text-decoration:none;
}

.toggle-icon{
    transition:.3s;
}

.table-sm td,
.table-sm th{
    vertical-align:middle;
}

.table-warning{
    background:#fff8dc;
}
    </style>
</head>
<body>
	<div class="wrapper">
		<div class="main-header">
			<div class="logo-header">
				<a href="#" class="logo particle-logo">
					<canvas id="logoCanvas"></canvas>
					<span>3AS</span>
				</a>
				<button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<button class="topbar-toggler more"><i class="la la-ellipsis-v"></i></button>
			</div>
			<nav class="navbar navbar-header navbar-expand-lg">
				<div class="container-fluid">

					<form class="navbar-left navbar-form nav-search mr-md-3" action="">
						<div class="input-group">
							<input type="text" placeholder="Search ..." class="form-control">
							<div class="input-group-append">
								<span class="input-group-text">
									<i class="la la-search search-icon"></i>
								</span>
							</div>
						</div>
					</form>
					<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
						<li class="nav-item dropdown hidden-caret">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="la la-envelope"></i>
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="#">Action</a>
								<a class="dropdown-item" href="#">Another action</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">Something else here</a>
							</div>
						</li>

						<li class="nav-item dropdown">
							<a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false"> <img src="{{ asset('assets/img/profile.jpg') }}" alt="user-img" width="36" class="img-circle"><span >{{ auth()->user()->name}}</span></span> </a>
							 <ul ngbdropdownmenu="" class="dropdown-menu dropdown-menu-end profile-dropdown" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(0px, 72px, 0px);" data-popper-placement="bottom-start">
                                    <div class="dropdown-header noti-title">
                                        <h6 class="text-overflow m-0">Welcome !</h6>
                                    </div>
                                    <a routerlink="{{route('profile.edit')}}" class="dropdown-item notify-item" href="{{route('profile.edit')}}">
                                        <i class="mdi mdi-account-circle-outline fs-16 align-middle"></i>
                                        <span>My Account</span>
                                    </a>
                                    {{-- <a routerlink="/auth/lock-screen" class="dropdown-item notify-item" href="/auth/lock-screen">
                                        <i class="mdi mdi-lock-outline fs-16 align-middle"></i>
                                        <span>Lock Screen</span>
                                    </a> --}}
                                    <div class="dropdown-divider"></div>
                                   <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>

                                    <a href="#" class="dropdown-item notify-item"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="mdi mdi-location-exit fs-16 align-middle"></i>
                                        <span>Logout</span>
                                    </a>
                                </ul>
								<!-- /.dropdown-user -->
							</li>
								<!-- /.dropdown-user -->
							</li>
					</ul>
                </div>
            </nav>
		</div>
            @include('layouts.sidebar')
            <div class="main-panel">
                @yield('content')

				<footer class="footer">
    <div class="container-fluid">
        <div class="row align-items-center">

            <!-- Left -->
            <div class="col-md-12 text-left">
                © {{ date('Y') }}
               <a href="https://3asresearchsolutions.in" target="_blank"> <strong>3AS Research Pvt. Ltd.</strong></a> | Growing Through Insights
                &nbsp;|&nbsp;
                API Portal v1.0
            </div>

        </div>
    </div>
</footer>

            </div>
            <!-- Modal -->
        <div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="modalUpdatePro" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h6 class="modal-title"><i class="la la-frown-o"></i> Survey Details</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <h5 id="projectName"></h5>
                        <div id="targetSpec"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
<script src="{{ asset('assets/js/core/jquery.3.2.1.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/chartist/chartist.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/chartist/plugin/chartist-plugin-tooltip.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/jquery-mapael/jquery.mapael.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/jquery-mapael/maps/world_countries.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/chart-circle/circles.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/ready.min.js') }}"></script>
@stack('scripts')

<script>

const canvas=document.getElementById("logoCanvas");
const ctx=canvas.getContext("2d");

function resize(){

    canvas.width=canvas.offsetWidth;
    canvas.height=canvas.offsetHeight;

}

resize();

window.addEventListener("resize",resize);

const particles=[];

for(let i=0;i<60;i++){

    particles.push({

        x:Math.random()*canvas.width,
        y:Math.random()*canvas.height,

        vx:(Math.random()-0.5)*0.6,
        vy:(Math.random()-0.5)*0.6,

        r:Math.random()*2+1

    });

}

function animate(){

    ctx.clearRect(0,0,canvas.width,canvas.height);

    particles.forEach(p=>{

        p.x+=p.vx;
        p.y+=p.vy;

        if(p.x<0||p.x>canvas.width) p.vx*=-1;
        if(p.y<0||p.y>canvas.height) p.vy*=-1;

        ctx.beginPath();
        ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
        ctx.fillStyle="#00d4ff";
        ctx.fill();

    });

    for(let i=0;i<particles.length;i++){

        for(let j=i+1;j<particles.length;j++){

            let dx=particles[i].x-particles[j].x;
            let dy=particles[i].y-particles[j].y;

            let dist=Math.sqrt(dx*dx+dy*dy);

            if(dist<40){

                ctx.strokeStyle="rgba(0,212,255,"+(1-dist/40)+")";
                ctx.lineWidth=0.6;

                ctx.beginPath();
                ctx.moveTo(particles[i].x,particles[i].y);
                ctx.lineTo(particles[j].x,particles[j].y);
                ctx.stroke();

            }

        }

    }

    requestAnimationFrame(animate);

}

animate();

</script>
</html>
