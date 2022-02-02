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
</head>

<body>
<nav class="navbar navbar-light navbar-expand-md navigation-clean-button navbar-main">
        <div class="container"><a class="navbar-brand navbar-logo" href="/"><img class="d-inline-block" src="{{asset('img/2.png')}}"><p class="d-inline-block brand-name" style="color: #fff5cc;">Roster Management<br></p></a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div
                class="collapse navbar-collapse navbar-icons-center" id="navcol-1">
                <ul class="nav navbar-nav text-left mr-auto nav-item-dropdown">
                    <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#" style="color: white;"><i class="fa fa-search nav-item-icon"></i>&nbsp; &nbsp; Explore</a>
                        <div class="dropdown-menu" role="menu"><a class="dropdown-item" role="presentation" href="/attendance">Attendance</a><a class="dropdown-item" role="presentation" href="/students">Students</a><a class="dropdown-item" role="presentation" href="/schedule">Schedule</a><a class="dropdown-item" role="presentation"
                                href="/earnings">Earnings</a></div>
                    </li>
                </ul>
                <div class="nav-item-div"><a class="login" href="#"><img class="profile-img" src="{{url('/uploads/profile-pictures/'.auth()->user()->id_teacher.'_'.auth()->user()->name.'.png')}}"><p class="d-inline-block nav-item-text">Teacher {{auth()->user()->name}}</p></a></div>
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
    <h1 class="page-heading">Attendance History</h1>
    <div class="attendance-history-box">
        <form>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-6">
                        <p class="attendance-history-search-text">From :</p><input class="form-control" type="date" required=""></div>
                    <div class="col-md-6">
                        <p class="attendance-history-search-text">To :</p><input class="form-control" type="date" required=""></div>
                </div>
            </div>
            <div class="attendance-input-button-box"><button class="btn btn-primary attendance-input-button" type="button"><i class="fa fa-search"></i>&nbsp;Search</button></div>
        </form>
    </div>
    <h4 class="page-heading" style="margin: 20px;">Search Results</h4>
    <div class="container attendance-history-box-container">
        <div class="row">
            <div class="col">
                <div class="d-inline-block bounce animated attendance-history-card col-xl-3 col-lg-4 col-md-4 col-sm-5 col-8">
                    <p class="attendance-history-card-icon"><i class="fa fa-check attendance-history-icon-done"></i></p>
                    <p class="attendance-history-text">Michelle Anatolia, Hirohiko Araki<br>Monday, 31 February 2019<br>3pm - 4pm<br>Smartie<br>Student's House 5km</p>
                    <div class="input-confirm-buttons"><button class="btn btn-primary d-block input-confirm-button" type="button">Progress Report</button><button class="btn btn-primary d-block input-confirm-button" type="button">Edit Attendance</button></div>
                </div>
                <div class="d-inline-block bounce animated attendance-history-card col-xl-3 col-lg-4 col-md-4 col-sm-5 col-8">
                    <p class="attendance-history-card-icon"><i class="fa fa-check attendance-history-icon-done"></i></p>
                    <p class="attendance-history-text">Michelle Anatolia, Hirohiko Araki<br>Monday, 31 February 2019<br>3pm - 4pm<br>Smartie<br>Student's House 5km</p>
                    <div class="input-confirm-buttons"><button class="btn btn-primary d-block input-confirm-button" type="button">Progress Report</button><button class="btn btn-primary d-block input-confirm-button" type="button">Edit Attendance</button></div>
                </div>
                <div class="d-inline-block bounce animated attendance-history-card col-xl-3 col-lg-4 col-md-4 col-sm-5 col-8">
                    <p class="attendance-history-card-icon"><i class="fa fa-check attendance-history-icon-done"></i></p>
                    <p class="attendance-history-text">Michelle Anatolia, Hirohiko Araki<br>Monday, 31 February 2019<br>3pm - 4pm<br>Smartie<br>Student's House 5km</p>
                    <div class="input-confirm-buttons"><button class="btn btn-primary d-block input-confirm-button" type="button">Progress Report</button><button class="btn btn-primary d-block input-confirm-button" type="button">Edit Attendance</button></div>
                </div>
                <div class="d-inline-block bounce animated attendance-history-card col-xl-3 col-lg-4 col-md-4 col-sm-5 col-8">
                    <p class="attendance-history-card-icon"><i class="fa fa-exclamation attendance-history-icon-notdone"></i></p>
                    <p class="attendance-history-text">Michelle Anatolia, Hirohiko Araki<br>Monday, 31 February 2019<br>3pm - 4pm<br>Smartie<br>Student's House 5km</p>
                    <div class="input-confirm-buttons"><button class="btn btn-primary d-block input-confirm-button" type="button">Progress Report</button><button class="btn btn-primary d-block input-confirm-button" type="button">Edit Attendance</button></div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
</body>

</html>