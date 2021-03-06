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
    <h1 class="bounce animated page-heading" style="color: rgb(48,121,200);">REFERRALS HISTORY</h1>
    <div class="container">
        <div style="margin: 0 0 15px 10px;">
            <select id="report-month" class="form-control-lg select-box-single" onchange="changeReport()">
                @if ($selected_month == 1)
                <option value="01" selected>January</option>
                @else
                <option value="01">January</option>
                @endif
                @if ($selected_month == 2)
                <option value="02" selected>February</option>
                @else
                <option value="02">February</option>
                @endif
                @if ($selected_month == 3)
                <option value="03" selected>March</option>
                @else
                <option value="03">March</option>
                @endif
                @if ($selected_month == 4)
                <option value="04" selected>April</option>
                @else
                <option value="04">April</option>
                @endif
                @if ($selected_month == 5)
                <option value="05" selected>May</option>
                @else
                <option value="05">May</option>
                @endif
                @if ($selected_month == 6)
                <option value="06" selected>June</option>
                @else
                <option value="06">June</option>
                @endif
                @if ($selected_month == 7)
                <option value="07" selected>July</option>
                @else
                <option value="07">July</option>
                @endif
                @if ($selected_month == 8)
                <option value="08" selected>August</option>
                @else
                <option value="08">August</option>
                @endif
                @if ($selected_month == 9)
                <option value="09" selected>September</option>
                @else
                <option value="09">September</option>
                @endif
                @if ($selected_month == 10)
                <option value="10" selected>October</option>
                @else
                <option value="10">October</option>
                @endif
                @if ($selected_month == 11)
                <option value="11" selected>November</option>
                @else
                <option value="11">November</option>
                @endif
                @if ($selected_month == 12)
                <option value="12" selected>December</option>
                @else
                <option value="12">December</option>
                @endif

            </select>
            <select
                id="report-year" class="form-control-lg select-box-single" required="" onchange="changeReport()">
                @foreach ($years as $y)
                    @if ($y->year == $selected_year)
                        <option value="{{ $y->year }}" selected>{{ $y->year }}</option>
                    @else
                        <option value="{{ $y->year }}">{{ $y->year }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div style="margin: 0 10px 0 10px;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th rowspan="2">Date of join</th>
                            <th rowspan="2">Student</th>
                            <th style="text-align: center;" colspan="3">Bonus Referral Parents</th>
                            <th rowspan="2">PIC Front Admin</th>
                            <th rowspan="2">Status</th>
                        </tr>
                        <tr>
                            <th>Parents</th>
                            <th>Referral</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($referrals as $r)
                        <tr>
                            <td>{{ $r->date }}</td>
                            <td>{{ $r->name }}</td>
                            <td>{{ $r->referrer_name }}</td>
                            <td>{{ $r->referral_nominal }}</td>
                            @if ($r->status_referral)
                            <td><i class="fa fa-check-circle" style="font-size: 40px;color: #6ce679;"></i></td>
                            @else
                            <td><i class="fa fa-exclamation-circle" style="color: red;font-size: 40px;"></i></td>
                            @endif
                            <td>{{ $r->pic_front_admin }}</td>
                            @if ($r->status_front_admin)
                            <td><i class="fa fa-check-circle" style="font-size: 40px;color: #6ce679;"></i></td>
                            @else
                            <td><i class="fa fa-exclamation-circle" style="color: red;font-size: 40px;"></i></td>
                            @endif

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
</body>
<script>
    function changeReport(){
        var month = document.getElementById("report-month").value;
        var year = document.getElementById("report-year").value;
        if (!year){year = 0;}
        location.replace("/accounting/referral/"+month+"/"+year);
    }
</script>

</html>
