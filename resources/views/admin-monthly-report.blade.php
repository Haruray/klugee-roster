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
    <h1 class="bounce animated page-heading" style="color: rgb(48,121,200);">FINANCE MONTHLY REPORT</h1>
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
                            <th rowspan="2">Source</th>
                            <th style="text-align: center;" colspan="4">Income (IDR)</th>
                            <th rowspan="2">Expense (IDR)</th>
                            <th rowspan="2">Profit (IDR)</th>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <th>Registration</th>
                            <th>SPP</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Active Students</td>
                            <td>{{ $income_spp->groupBy('detail')->count() }}</td>
                            <td>{{ $income_regis->sum('nominal') }}</td>
                            <td>{{ $income_spp->sum('nominal') }}</td>
                            <td>{{$income_spp->sum('nominal') +  $income_regis->sum('nominal')}}</td>
                            <td>0</td>
                            <td rowspan="{{ 3 + count($expense) }}">{{$income_spp->sum('nominal') +  $income_regis->sum('nominal') + $income_other->sum('nominal') - $expense->sum('total')}}</td>
                        </tr>
                        <tr>
                            <td>Others</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ $income_other->sum('nominal') }}</td>
                            <td>0</td>
                        </tr>
                        @foreach ($expense as $e)
                        <tr>
                            <td>{{ $e->transaction_type }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ $e->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <thead>
                        <th colspan="4"></th>
                        <th>{{$income_spp->sum('nominal') +  $income_regis->sum('nominal') + $income_other->sum('nominal')}}</th>
                        <th colspan="2">{{ $expense->sum('total') }}</th>
                    </thead>
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
        location.replace("/accounting/financial-data/report/"+month+"/"+year);
    }
</script>

</html>
