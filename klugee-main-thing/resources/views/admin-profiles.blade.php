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
        <div class="container"><a class="navbar-brand navbar-logo" href="#"><img class="d-inline-block" src="assets/img/2.png"><p class="d-inline-block brand-name" style="color: #fff5cc;">Roster Management<br></p></a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div
                class="collapse navbar-collapse navbar-icons-center" id="navcol-1">
                <ul class="nav navbar-nav text-left mr-auto nav-item-dropdown">
                    <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#" style="color: white;"><i class="fa fa-search nav-item-icon"></i>&nbsp; &nbsp; Explore</a>
                        <div class="dropdown-menu" role="menu"><a class="dropdown-item" role="presentation" href="#">Attendance</a><a class="dropdown-item" role="presentation" href="#">Students</a><a class="dropdown-item" role="presentation" href="#">Schedule</a><a class="dropdown-item" role="presentation"
                                href="#">Earnings</a></div>
                    </li>
                </ul>
                <div class="nav-item-div"><a class="login" href="#"><img class="profile-img" src="assets/img/edgysul.png"><p class="d-inline-block nav-item-text">Teacher Rico</p></a></div>
                <div class="text-left nav-item-div">
                    <a class="login" href="#">
                        <div class="d-inline-block"><i class="fa fa-bell-o notif-img yellow"></i><img class="warning-sign" src="assets/img/15.png"></div>
                        <p class="d-inline-block nav-item-text">Notification</p>
                    </a>
                </div>
                <div class="text-left nav-item-div">
                    <a class="login" href="#">
                        <div class="d-inline-block"><i class="fa fa-sign-out notif-img yellow"></i></div>
                        <p class="d-inline-block nav-item-text">Sign Out</p>
                    </a>
                </div>
        </div>
        </div>
    </nav>
    <h1 class="bounce animated page-heading">PROFILES</h1>
    <div>
        <div class="container">
            <div class="row text-center justify-content-center align-items-center">
                <div class="col-md-3 offset-md-0 col-sm-6">
                    <a href="#">
                        <div data-bs-hover-animate="bounce" class="button">
                            <p class="button-content-icon"><i class="fa fa-user"></i></p>
                            <p class="button-content">Student</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 offset-md-0 col-sm-6">
                    <a href="#">
                        <div data-bs-hover-animate="bounce" class="button">
                            <p class="button-content-icon"><i class="fa fa-user"></i></p>
                            <p class="button-content">Users</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 offset-md-0 col-sm-6">
                    <a href="#">
                        <div data-bs-hover-animate="bounce" class="button">
                            <p class="button-content-icon"><i class="fa fa-th-list"></i></p>
                            <p class="button-content">Attendance List</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
</body>

</html>