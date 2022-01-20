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
    <script src="{{asset('js/studentsearch-script.js')}}"></script>
</head>

<body>
<nav class="navbar navbar-light navbar-expand-md navigation-clean-button navbar-main">
        <div class="container"><a class="navbar-brand navbar-logo" href="#"><img class="d-inline-block" src="{{asset('img/2.png')}}"><p class="d-inline-block brand-name" style="color: #fff5cc;">Roster Management<br></p></a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div
                class="collapse navbar-collapse navbar-icons-center" id="navcol-1">
                <ul class="nav navbar-nav text-left mr-auto nav-item-dropdown">
                    <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#" style="color: white;"><i class="fa fa-search nav-item-icon"></i>&nbsp; &nbsp; Explore</a>
                        <div class="dropdown-menu" role="menu"><a class="dropdown-item" role="presentation" href="#">Attendance</a><a class="dropdown-item" role="presentation" href="#">Students</a><a class="dropdown-item" role="presentation" href="#">Schedule</a><a class="dropdown-item" role="presentation"
                                href="#">Earnings</a></div>
                    </li>
                </ul>
                <div class="nav-item-div"><a class="login" href="#"><img class="profile-img" src="{{asset('img/edgysul.png')}}"><p class="d-inline-block nav-item-text">Teacher Rico</p></a></div>
                <div class="text-left nav-item-div">
                    <a class="login" href="#">
                        <div class="d-inline-block"><i class="fa fa-bell notif-img yellow"></i><img class="warning-sign" src="{{asset('img/15.png')}}"></div>
                        <p class="d-inline-block nav-item-text">Notification</p>
                    </a>
                </div>
                <div class="nav-item-div"><a class="login" href="#"><img class="profile-img" src="{{asset('img/sign out.png')}}"><p class="d-inline-block nav-item-text">Sign out</p></a></div>
        </div>
        </div>
    </nav>
    <h1 class="bounce animated page-heading">STUDENTS SEARCH</h1>
    <div class="student-search">
        <h1></h1>
        <form>
            <p class="d-inline-block student-search-icon"><i class="fa fa-search"></i></p><input id="student-search" onkeyup="$dc.StudentSearch()" class="form-control d-inline-block student-search-form" type="text"></form>
    </div>
    <div class="container student-card-container">
        <div class="row">
            <div id="student-list" class="col-lg-12">
                @foreach ($students as $student)
                <div class="d-inline-block student-card col-xl-2 col-lg-3 col-md-4 col-sm-5 col-5"><img class="student-card-profile" src="{{asset('img/default-profile-img.png')}}">
                    <p class="student-card-name">{{$student->name}}</p><a href="/students/{{$student->id}}"><button class="btn btn-primary student-card-button" type="button">Select</button></a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
</body>

</html>