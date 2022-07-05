<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" href="{{ asset('img/2.png') }}">
    <title>({{ count(auth()->user()->unreadNotifications) }}) Klugee Roster Management</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/font-awesome.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('css/Login-Form-Clean.css')}}">
    <link rel="stylesheet" href="{{asset('css/Navigation-Clean.css')}}">
    <link rel="stylesheet" href="{{asset('css/Navigation-with-Button.css')}}">
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <script src="{{asset('js/schedulemanage-script.js')}}"></script>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css" />

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
                        @elseif (auth()->user()->user_type=="head teacher")
                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" role="presentation" href="/attendance-admin">Attendance</a>
                            <a class="dropdown-item" role="presentation" href="/user-profiles">Profiles</a>
                            <a class="dropdown-item" role="presentation" href="/schedule-admin">Schedule</a>
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

                <div class="nav-item-div"><a class="login" href="/profile"><img class="profile-img" src="{{url('/uploads/profile-pictures/'.auth()->user()->photo)}}"><p class="d-inline-block nav-item-text">{{ ucwords(auth()->user()->user_type) }} {{auth()->user()->name}}</p></a></div>
                    <div class="text-left nav-item-div">
                        <a class="login" href="/management">
                            <div class="d-inline-block"><i class="fa fa-cog nav-img yellow"></i></div>
                            <p class="d-inline-block nav-item-text">Management</p>
                        </a>
                    </div>
                <div class="text-left nav-item-div">
                    <a class="login" href="/notification">
                        <div class="d-inline-block"><i class="fa fa-bell-o notif-img yellow"></i>
                            @if (count(auth()->user()->unreadNotifications) > 0)
                            <img class="warning-sign" src="{{asset('img/15.png')}}">
                            @else
                            <img class="warning-sign-hidden" src="{{asset('img/15.png')}}">
                            @endif
                        </div>
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
    <h2 class="text-center bounce animated page-heading">All Schedule</h2>
    <div class="container">
        <div style="margin: 0 0 15px 10px;">
            <select id="report-month" class="form-control-lg select-box-single" onchange="changeSchedule()">
                @if (strcmp($selector,'all')==0)
                <option value="all" selected>All</option>
                @else
                <option value="all">All</option>
                @endif
                @foreach ($teachers as $t)
                    @if ($selector == $t->id)
                    <option value="{{ $t->id }}" selected>{{ $t->name }}</option>
                    @else
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                    @endif
                @endforeach
            </select>

            @if (strcmp($selector,'all')==0)
            <div style="margin: 0 10px 0 10px;">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th rowspan="2">Hari</th>
                                <th rowspan="2">Jam Mulai</th>
                                <th rowspan="2">Jam Selesai</th>
                                <th colspan="{{ count($teacher_json) }}" style="text-align: center;">Teachers</th>
                            </tr>
                            <tr>
                                @foreach (array_keys($teacher_json) as $t)
                                    <th style="text-align: center;">{{ $t }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (array_keys($schedule_json) as $day)
                                <tr>
                                    <td rowspan="{{ count($schedule_json[$day])+1 }}">{{ $day }}</td>
                                </tr>
                                @foreach (array_keys($schedule_json[$day]) as $time)
                                <tr>
                                    <td>
                                        {{ $time }}
                                    </td>
                                    <td>{{ date('H:i', strtotime("+55 minutes", strtotime($time))) }}</td>
                                    @foreach ($schedule_json[$day][$time] as $desc)
                                            @if ($desc=='')
                                            <td>{{ $desc }}</td>
                                            @else
                                            <td style="background-color:#fff5cc; font-weight:bold;">{{ $desc }}</td>
                                            @endif
                                    @endforeach
                                </tr>

                                @endforeach
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            @else

            @endif
        </div>
    </div>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
</body>

<script>
    function changeSchedule(){
        var month = document.getElementById("report-month").value;
        var year = document.getElementById("report-year").value;
        if (!year){year = 0;}
        location.replace("/accounting/financial-data/recap/income/"+month+"/"+year);
    }
</script>

</html>
