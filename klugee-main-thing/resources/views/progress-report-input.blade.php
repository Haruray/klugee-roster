<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <!--<meta name="csrf-token" content="{{ csrf_token() }}">-->
    <title>Untitled</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="{{asset('css/Login-Form-Clean.css')}}">
    <link rel="stylesheet" href="{{asset('css/Navigation-Clean.css')}}">
    <link rel="stylesheet" href="{{asset('css/Navigation-with-Button.css')}}">
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">

    <script src="{{asset('js/progressreport-script.js')}}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <nav class="navbar navbar-light navbar-expand-md navigation-clean-button navbar-main">
        <div class="container"><a class="navbar-brand navbar-logo" href="#"><img class="d-inline-block" src="assets/img/2.png"><p class="d-inline-block brand-name" style="color: #fff5cc;">Roster Management<br></p></a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div
                class="collapse navbar-collapse navbar-icons-center" id="navcol-1">
                <ul class="nav navbar-nav text-left mr-auto nav-item-dropdown">
                    <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#" style="color: white;"><i class="fa fa-search nav-item-icon"></i>&nbsp; &nbsp; Explore</a>
                        <div class="dropdown-menu" role="menu"><a class="dropdown-item" role="presentation" href="#">Attendance</a><a class="dropdown-item" role="presentation" href="#">Students</a><a class="dropdown-item" role="presentation" href="#">Schedule</a><a class="dropdown-item" role="presentation"
                                href="#">Earnings</a></div>
                    </li>
                </ul>
                <div data-bs-hover-animate="jello" class="nav-item-div"><a class="login" href="#"><img class="profile-img" src="assets/img/edgysul.png"><p class="d-inline-block nav-item-text">Teacher Rico</p></a></div>
                <div data-bs-hover-animate="jello" class="nav-item-div">
                    <a class="login" href="#">
                        <div class="d-inline-block"><img class="notif-img" src="assets/img/13.png"><img class="warning-sign" src="assets/img/15.png"></div>
                        <p class="d-inline-block nav-item-text">Notification</p>
                    </a>
                </div>
                <div data-bs-hover-animate="jello" class="nav-item-div"><a class="login" href="#"><img class="profile-img" src="assets/img/sign%20out.png"><p class="d-inline-block nav-item-text">Sign out</p></a></div>
        </div>
        </div>
    </nav>
    <h1 class="bounce animated page-heading">Progress Report Input</h1>
    <div id="attendance-box" class="attendance-box">
        <h3 class="page-sub-heading yellow">
            @for ($i = 0 ; $i < count($students) - 1 ; $i++)
                {{$students[$i]->name}},
            @endfor
            {{$students[count($students)-1]->name}}
        </h3>
        <h2 class="page-sub-heading">SMARTIE</h2>
        <div class="input-confirm-buttons" style="margin: 0 0 20px;"><a href="/attendance/{{$attendance_id}}"><button class="btn btn-primary d-block progress-report-check-attendance-button" type="button" style="font-size: 14px;">Check Attendance</button></div></a>
        <form>
            @csrf
            <div class="container">
                <div class="form-row">
                    <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-book"></i></div><input id="level" name="level" class="form-control attendance-input" type="text" placeholder="Level" required></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-map-marker"></i></div><input id="unit" name="unit" class="form-control attendance-input" type="text" placeholder="Unit" required></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-tag"></i></div><input id="last_exercise" name="last_exercise" class="form-control attendance-input" type="text" placeholder="Last Exercise" required></div>
                    </div>
                </div>
            </div>
            @foreach ($students as $student)
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-tag"></i></div><input id="score-{{$student->id}}" name="score-{{$student->id}}" class="form-control attendance-input" type="text" placeholder="{{$student->nickname}}'s Score" required></div>
                    </div>
                </div>
            </div>
            @endforeach
            
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-tag"></i></div><input id="documentation" name="documentation" class="form-control attendance-input" type="file" placeholder="Documentation" required></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-tag"></i></div><textarea id="note" name="note" class="form-control attendance-input" placeholder="Note" required></textarea></div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="attendance_id" name="attendance_id" value="{{$attendance_id}}">
            <div class="attendance-input-button-box"><button onclick="$dc.ProgressReportInput()" class="btn btn-primary attendance-input-button" type="button">Submit</button></div>
            <!--<div class="attendance-input-button-box"><button class="btn btn-primary attendance-input-button" type="submit" name="submit" value="submit">Submit</button></div>-->
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
</body>

</html>