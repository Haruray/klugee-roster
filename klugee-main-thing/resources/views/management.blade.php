<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>klugee-roster</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="{{asset('css/Login-Form-Clean.css')}}">
    <link rel="stylesheet" href="{{asset('css/Navigation-Clean.css')}}">
    <link rel="stylesheet" href="{{asset('css/Navigation-with-Button.css')}}">
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand-md navigation-clean-button navbar-main">
        <div class="container"><a class="navbar-brand navbar-logo" href="/"><img class="d-inline-block" src="{{asset('img/2.png')}}"><p class="d-inline-block brand-name" style="color: #fff5cc;">Roster Management<br></p></a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div
                class="collapse navbar-collapse navbar-icons-center" id="navcol-1">
                <ul class="nav navbar-nav text-left mr-auto nav-item-dropdown">
                    <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#" style="color: white;"><i class="fa fa-search nav-item-icon"></i>&nbsp; &nbsp; Explore</a>
                        @if (auth()->user()->user_type=="teacher")
                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" role="presentation" href="/attendance">Attendance</a>
                            <a class="dropdown-item" role="presentation" href="/students">Students</a>
                            <a class="dropdown-item" role="presentation" href="/schedule">Schedule</a>
                            <a class="dropdown-item" role="presentation" href="/earnings">Earnings</a>
                        </div>
                        @else
                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" role="presentation" href="/attendance-admin">Attendance</a>
                            <a class="dropdown-item" role="presentation" href="/user-profiles">Profiles</a>
                            <a class="dropdown-item" role="presentation" href="/schedule-admin">Schedule</a>
                            <a class="dropdown-item" role="presentation" href="/accounting">Accounting</a>
                        </div>
                        @endif

                    </li>
                </ul>

                <div class="nav-item-div"><a class="login" href="/profile"><img class="profile-img" src="{{url('/uploads/profile-pictures/'.auth()->user()->id_teacher.'_'.auth()->user()->name.'.png')}}"><p class="d-inline-block nav-item-text">Teacher {{auth()->user()->name}}</p></a></div>
                @if (auth()->user()->user_type == "admin")
                    <div class="text-left nav-item-div">
                        <a class="login" href="/management">
                            <div class="d-inline-block"><i class="fa fa-cog nav-img yellow"></i></div>
                            <p class="d-inline-block nav-item-text">Management</p>
                        </a>
                    </div>
                @endif
                <div class="text-left nav-item-div">
                    <a class="login" href="/notification">
                        <div class="d-inline-block"><i class="fa fa-bell-o notif-img yellow"></i><img class="warning-sign" src="{{asset('img/15.png')}}"></div>
                        <p class="d-inline-block nav-item-text">Notification</p>
                    </a>
                </div>
                <div class="text-left nav-item-div">
                    <a class="login" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form></li>
                        <div class="d-inline-block"><i class="fa fa-sign-out notif-img yellow"></i></div>
                        <p class="d-inline-block nav-item-text">Sign Out</p>
                    </a>
                </div>
        </div>
        </div>
    </nav>
    <h1 class="bounce animated page-heading">DATA MANAGEMENT</h1>
    <div class="container">
        <div class="management-box">
            <div class="management-sub-box">
                <h3 class="management-heading">Password Change</h3>
            </div>
            <div class="management-sub-box">
                <h3 class="management-heading">Programs</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Available Programs</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($programs as $p)
                                <tr>
                                    <td>{{ $p->program }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><button class="btn btn-primary" type="button">Add Program</button></div>
            <div class="management-sub-box">
                <h3 class="management-heading">Fee List</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Program</th>
                                <th>Level</th>
                                <th>Exclusive (IDR)</th>
                                <th>Semi-Private (IDR)</th>
                                <th>School (IDR)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i=0 ; $i < count($feelist) ; $i+=$feelist->where('program',$feelist[$i]->program)->count())
                                <tr>
                                    <td rowspan="{{ $feelist->where('program',$feelist[$i]->program)->count() }}">{{ $feelist[$i]->program }}</td>
                                    <td>{{ $feelist[$i]->level }}</td>
                                    <td>{{ $feelist[$i]->nominal_exclusive }}</td>
                                    <td>{{ $feelist[$i]->nominal_semiprivate }}</td>
                                    <td>{{ $feelist[$i]->nominal_school }}</td>
                                    <td><button class="btn btn-warning">Edit</button></td>
                                </tr>
                                @for ($j=$i+1 ; $j < $i+$feelist->where('program',$feelist[$i]->program)->count() ; $j++)
                                <tr>
                                    <td>{{ $feelist[$j]->level }}</td>
                                    <td>{{ $feelist[$j]->nominal_exclusive }}</td>
                                    <td>{{ $feelist[$j]->nominal_semiprivate }}</td>
                                    <td>{{ $feelist[$j]->nominal_school }}</td>
                                    <td><button class="btn btn-warning">Edit</button></td>
                                </tr>
                                @endfor
                            @endfor
                        </tbody>
                    </table>
                </div><button class="btn btn-primary" type="button">Add Fee</button></div>
            <div class="management-sub-box">
                <h3 class="management-heading">Incentives</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Incentive Name</th>
                            <th>Receiver</th>
                            <th>Nominal (IDR)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incentive as $i)
                            <tr>
                                <td>{{ $i->name }}</td>
                                <td>{{ $i->receiver }}</td>
                                <td>{{ $i->nominal }}</td>
                                <td><button class="btn btn-warning">Edit</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="management-sub-box">
                <h3 class="management-heading">Salaries</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Nominal (IDR)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salary as $s)
                            <tr>
                                <td>{{ $s->position }}</td>
                                <td>{{ $s->status }}</td>
                                <td>{{ $s->nominal }}</td>
                                <td><button class="btn btn-warning">Edit</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button class="btn btn-primary" type="button">Add Salary</button>
            </div>
            <div class="management-sub-box">
                <h3 class="management-heading">Parents Partner</h3>
                <button class="btn btn-primary" type="button">Add Fee</button>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
</body>

</html>
