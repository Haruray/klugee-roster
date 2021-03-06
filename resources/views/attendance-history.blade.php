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
    <h1 class="page-heading">Attendance History</h1>
    <div class="attendance-history-box">
        <form>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-6">
                        <p class="attendance-history-search-text">From :</p>
                        <input id="from" class="form-control" type="date" required="" value={{ $from }}></div>
                    <div class="col-md-6">
                        <p class="attendance-history-search-text">To :</p>
                        <input id="to" class="form-control" type="date" required="" value={{ $to }}></div>
                </div>
            </div>
            <div class="attendance-input-button-box">
                <button onclick="ChangeHistory()" class="btn btn-primary attendance-input-button" type="button"><i class="fa fa-search"></i>&nbsp;Search</button></div>
        </form>
    </div>
    <h4 class="page-heading" style="margin: 20px;">Search Results</h4>
    <div class="container attendance-history-box-container">
        <div class="row">
            <div class="col">
                @for ($i = 0 ; $i < count($progress) ; $i+=$progress->where('id', $progress[$i]->id)->count())
                    <div class="d-inline-block bounce animated attendance-history-card col-xl-3 col-lg-4 col-md-4 col-sm-5 col-8">
                        <p class="attendance-history-card-icon">
                            @if ($progress[$i]->filled == false || is_null($progress[$i]->filled))
                            <i class="fa fa-exclamation attendance-history-icon-notdone"></i>
                            @else
                            <i class="fa fa-check attendance-history-icon-done"></i>
                            @endif
                        </p>
                        <p class="attendance-history-text">
                            @for ($j=$i ; $j < $i+$progress->where('id', $progress[$i]->id)->count() ; $j++)
                                @if ($j == $i+$progress->where('id', $progress[$i]->id)->count() - 1 )
                                    {{ $progress[$j]->name }} <br>
                                @else
                                    {{ $progress[$j]->name }},
                                @endif
                            @endfor
                            {{ date('l',strtotime($progress[$i]->date)) }}, {{ date('d F Y',strtotime($progress[$i]->date)) }}<br>
                            {{ date('h:i', strtotime($progress[$i]->time))}} - {{ date('h:i', strtotime($progress[$i]->time)+3600) }}<br>
                            {{ $progress[$i]->program }}<br>
                            {{ $progress[$i]->location }}
                        </p>
                        <div class="input-confirm-buttons">
                            @if ($progress[$i]->filled)
                            <button onclick="$dc2.TeachingInfo({{ $progress[$i]->id }})" class="btn btn-primary d-block input-confirm-button" type="button">Progress Report</button>
                            @else
                            <a href="/attendance/progress-report/{{ $progress[$i]->id }}"><button class="btn btn-primary d-block input-confirm-button" type="button">Fill Progress Report</button></a>
                            @endif
                            <button class="btn btn-primary d-block input-confirm-button" type="button">Edit Attendance</button>
                        </div>
                    </div>
                @endfor


            </div>
        </div>
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/teaching-info.js') }}"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
    <script>
        let ChangeHistory = function(){
            let from = document.getElementById("from").value;
            let to = document.getElementById("to").value;
            // Simulate an HTTP redirect:
            window.location.replace('/attendance/history/'+from+'/'+to);
        }
    </script>
</body>

</html>
