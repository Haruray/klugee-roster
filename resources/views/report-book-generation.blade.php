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
    <div class="container">
        <div class="row">
            <div class="col-md-9 text-left">
                <div class="d-inline-block progress-report-text">
                    <h2 class="text-left progress-report-program-name">REPORT BOOK</h2>
                    <p class="text-left progress-report-student-name">{{$student->name}}</p>
                    <p class="text-left progress-report-program-name">{{$program}}</p>
                </div>
            </div>
        </div>
        <div style="background-color: #fff5cc; border-radius:10px; padding:20px;">
            <form action="/generate-report" method="POST">
                @csrf
                <button class="btn btn-primary float-right progress-report-button bold" type="submit" value="submit" style="font-size: 13px;"><i class="fa fa-book"></i>&nbsp;Generate</button>
                <textarea name="description-english" id="" style="box-sizing: border-box; width:100%; margin-bottom:20px; margin-top:20px;" placeholder="Evaluation Description (English)" required></textarea>
                <textarea name="description-indo" id="" style="box-sizing: border-box; width:100%; margin-bottom:20px;" placeholder="Evaluation Description (Indonesia)" required></textarea>
                <input class="form-control" type="text" name="overall-score" style="margin-bottom:20px;" placeholder="Overall Score ( Example : 'Excellent (A+)' )" required>
                <input class="form-control" type="text" name="farewell" style="margin-bottom:20px;" placeholder="Closing; Student's next stage (Example : 'Smartie Level 2')" required>
                <input type="hidden" name="program" id="program" value="{{ $program }}">
            <div class="table-responsive progress-report-table">
                    <table id="progress-report-table" class="table">
                        <thead>
                            <tr>
                                <th style="width:10%;">Check All <br> <input type="checkbox" onClick="toggle(this)" /><br/></th>
                                <th>Date</th>
                                <th>Level</th>
                                <th>Unit</th>
                                <th>Last Exercise</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0 ; $i < count($progress_report) ; $i++)
                            <tr>
                                <td><input type="checkbox" name="progress[]" value="{{ $progress_report[$i]->id }}"></td>
                                <td>{{date('l',strtotime($progress_report[$i]->date))}}, {{date('d',strtotime($progress_report[$i]->date))}} {{date('F',strtotime($progress_report[$i]->date))}} {{date('Y',strtotime($progress_report[$i]->date))}}</td>
                                <td>{{$progress_report[$i]->level ?: ''}}</td>
                                <td>{{$progress_report[$i]->unit ?: ''}}</td>
                                <td>{{$progress_report[$i]->last_exercise ?: ''}}</td>
                                <td>{{$progress_report[$i]->score ?: ''}}</td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>



    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
</body>

<script>
    function toggle(source) {
    checkboxes = document.getElementsByName('progress[]');
    for (var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
</script>

</html>
