<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Untitled</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="{{asset('css/Login-Form-Clean.css')}}">
    <link rel="stylesheet" href="{{asset('css/Navigation-Clean.css')}}">
    <link rel="stylesheet" href="{{asset('css/Navigation-with-Button.css')}}">
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">



    <style>
        #myImg {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
        }

        #myImg:hover {opacity: 0.7;}

        /* The Modal (background) */
        .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
        }

        /* Modal Content (Image) */
        .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        }

        /* Caption of Modal Image (Image Text) - Same Width as the Image */
        #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 150px;
        }

        /* Add Animation - Zoom in the Modal */
        .modal-content, #caption {
        animation-name: zoom;
        animation-duration: 0.6s;
        }

        @keyframes zoom {
        from {transform:scale(0)}
        to {transform:scale(1)}
        }

        /* The Close Button */
        .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        }

        .close:hover,
        .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
        }

        /* 100% Image Width on Smaller Screens */
        @media only screen and (max-width: 700px){
        .modal-content {
            width: 100%;
        }
        }
    </style>
    <script src="{{asset('js/progressreportlist-script.js')}}"></script>
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
                                <th style="width:15px;">Checklist</th>
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
                                <td>{{date('D',strtotime($progress_report[$i]->date))}}, {{date('d',strtotime($progress_report[$i]->date))}} {{date('F',strtotime($progress_report[$i]->date))}} {{date('Y',strtotime($progress_report[$i]->date))}}</td>
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



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
</body>

</html>
