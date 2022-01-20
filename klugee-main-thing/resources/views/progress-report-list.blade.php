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
    <h2 class="bounce animated page-heading">PROGRESS REPORTS</h2>
    <div class="container">
        <div class="row">
            <div class="col-md-3 text-center"><img src="{{asset('img/40.png')}}" style="width: 120px;"></div>
            <div class="col-md-9 text-center">
                <div class="d-inline-block progress-report-text">
                    <p class="text-center progress-report-student-name">Michelle Anatolia</p>
                    <p class="text-center progress-report-program-name">SMARTIE</p>
                </div>
            </div>
        </div>
        <div class="progress-report-list-button-group"><button class="btn btn-primary float-right progress-report-button bold" type="button" style="font-size: 13px;"><i class="fa fa-book"></i>&nbsp;Report Book</button></div><button class="btn btn-primary float-left attendance-input-button" type="button"
            style="font-size: 13px;"><i class="fa fa-sort-up"></i>&nbsp;Sort by Newest</button>
        <div class="table-responsive progress-report-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Session</th>
                        <th>Date</th>
                        <th>Level</th>
                        <th>Unit</th>
                        <th>Last Exercise</th>
                        <th>Score</th>
                        <th>Action</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>11-11-2012</td>
                        <td>3rd grade math</td>
                        <td>Multiplication</td>
                        <td>Multiple by 8</td>
                        <td>100</td>
                        <td><button class="btn btn-primary" type="button">Edit</button></td>
                        <td><i class="fa fa-check-circle" style="font-size: 40px;color: #6ce679;"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>12-11-2021</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn btn-primary" type="button">Edit</button></td>
                        <td><i class="fa fa-exclamation-circle" style="color: red;font-size: 40px;"></i></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
</body>

</html>