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
    <h2 class="bounce animated page-heading">STUDENT'S PROFILE</h2>
    <div>
        <div class="container student-profile">
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="text-center student-profile-img-outline"><img class="student-profile-img" src="assets/img/FGr6hhhVQAEnlUQ.jpg"></div><button class="btn btn-primary attendance-input-button" type="button" style="margin: 10px ;">Attendance History</button></div>
                <div class="col-md-6 col-lg-8">
                    <div></div>
                    <p class="text-left student-profile-name">Xehanort's Nobody</p>
                    <p class="student-profile-desc">School</p>
                    <p class="student-profile-desc-subtitle">SD Insan Amanah</p>
                    <p class="student-profile-desc">Parent's Name</p>
                    <p class="student-profile-desc-subtitle">Xehanort</p>
                    <p class="student-profile-desc">Parent's Contact</p>
                    <p class="student-profile-desc-subtitle">+6281232158548</p>
                </div>
            </div>
        </div>
    </div>
    <h3 class="bounce animated page-heading">Programs</h3>
    <div class="container student-card-container">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-inline-block student-program-card col-xl-3 col-lg-3 col-md-4 col-sm-8 col-8"><img class="student-card-profile" src="assets/img/40.png">
                    <p class="student-program-name">Smartie</p>
                    <p class="student-program-payment-paid"><i class="fa fa-check"></i>&nbsp;Paid</p><button class="btn btn-primary student-card-button" type="button">Progress Reports</button></div>
                <div class="d-inline-block student-program-card col-xl-3 col-lg-3 col-md-4 col-sm-8 col-8"><img class="student-card-profile" src="assets/img/41.png">
                    <p class="student-program-name">Science</p>
                    <p class="student-program-payment-paid"><i class="fa fa-check"></i>&nbsp;Paid</p><button class="btn btn-primary student-card-button" type="button">Progress Reports</button></div>
                <div class="d-inline-block student-program-card col-xl-3 col-lg-3 col-md-4 col-sm-8 col-8"><img class="student-card-profile" src="assets/img/42.png">
                    <p class="student-program-name">Speak Up</p>
                    <p class="student-program-payment-late"><i class="fa fa-exclamation"></i>&nbsp;Late Payment</p><button class="btn btn-primary student-card-button" type="button">Progress Reports</button></div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
</body>

</html>