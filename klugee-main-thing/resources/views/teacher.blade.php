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
    <div class="text-center">
        <div class="container teacher-profile-img-div">
            <div class="row">
                <div class="col-sm-12 col-md-4 col-xl-3 offset-lg-0 text-center">
                    <div class="text-left d-inline-block teacher-profile-img-group">
                        <div class="text-center teacher-profile-img-outline"><img class="student-profile-img" src="{{asset('img/edgysul.png')}}"><a href="#"><i class="fa fa-camera teacher-profile-camera" data-bs-hover-animate="pulse"></i></a></div>
                        <div class="teacher-fee">
                            <p>Fee up to day&nbsp;</p>
                            <p class="bold teacher-fee-nominal">Rp1.500.000</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-8 col-xl-8 text-center">
                    <div class="d-inline-block">
                        <p class="teacher-profile-name bold yellow">Muhammad EmetiK</p>
                        <p class="bold white" style="font-size: 20px;">Joined since</p>
                        <p class="bold teacher-join-time yellow">October 2019</p>
                        <div class="teacher-status">
                            <p class="d-inline-block white bold teacher-status-individual"><i class="fa fa-check-circle" style="color: #38b6ff;font-size: 35px;"></i>&nbsp;Active</p>
                            <p class="d-inline-block white bold teacher-status-individual"><i class="fa fa-check-circle" style="color: #38b6ff;font-size: 35px;"></i>&nbsp;Part Time</p>
                            <p class="d-inline-block white bold teacher-status-individual"><i class="fa fa-check-circle" style="color: #38b6ff;font-size: 35px;"></i>&nbsp;Contract</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-8 text-center">
                    <div class="d-inline-block teacher-desc" data-bs-hover-animate="bounce"><i class="fa fa-user teacher-desc-icon"></i>
                        <p class="teacher-desc-text">28 Students</p>
                    </div>
                    <div class="d-inline-block teacher-desc" data-bs-hover-animate="bounce"><i class="fa fa-clock-o teacher-desc-icon"></i>
                        <p class="teacher-desc-text">Attendance</p>
                    </div>
                    <div class="d-inline-block teacher-desc" data-bs-hover-animate="bounce"><i class="fa fa-calendar-check-o teacher-desc-icon"></i>
                        <p class="teacher-desc-text">Schedule</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <p class="text-uppercase" style="font-size: 20px;color: #a6a6a6;margin: 20px;"><i class="fa fa-home" style="color: #54dee4;font-size: 30px;"></i><strong>&nbsp; &nbsp;Alamat</strong><br><i class="fa fa-phone" style="color: #54dee4;font-size: 30px;"></i><strong>&nbsp; &nbsp; No telp</strong><br><i class="fa fa-id-card"
                            style="color: #54dee4;font-size: 30px;"></i><strong>&nbsp; NIK</strong><br><i class="fa fa-calendar" style="color: #54dee4;font-size: 30px;"></i>&nbsp; &nbsp;<strong>tanggal lahir</strong><button class="btn btn-warning text-white edit-button-bio-teacher"
                            type="button"><i class="fa fa-pencil"></i>&nbsp;Edit Biodata</button><br></p>
                    <p class="text-uppercase" style="font-size: 20px;color: #a6a6a6;margin: 20px;"><i class="fa fa-check" style="color: #38b6ff;font-size: 30px;"></i><strong>&nbsp;Teaching MEthod 1</strong><br><i class="fa fa-check" style="color: #38b6ff;font-size: 30px;"></i><strong>&nbsp;teaching method2</strong><br><i class="fa fa-check"
                            style="color: #38b6ff;font-size: 30px;"></i><strong>&nbsp;location 1</strong><br><i class="fa fa-check" style="color: #38b6ff;font-size: 30px;"></i><strong>&nbsp;location 2</strong></p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
</body>

</html>