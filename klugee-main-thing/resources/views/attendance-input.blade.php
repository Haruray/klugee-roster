<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Untitled</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('css/Login-Form-Clean.css')}}">
    <link rel="stylesheet" href="{{asset('css/Navigation-Clean.css')}}">
    <link rel="stylesheet" href="{{asset('css/Navigation-with-Button.css')}}">
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css" />

    <script>
        $(document).ready(function() {
            $('select').select2({
            width: '90%'
        });

            $('#student1').select2({
            placeholder: "Select a student",
            allowClear: true
        });

        $('#attendance-form-program').select2({
            placeholder: "Select a program",
            allowClear: true
        });

        $('#attendance-form-location').select2({
            placeholder: "Select location",
            allowClear: true
        });

        $('#attendance-form-hour').select2({
            placeholder: "Select hour",
            allowClear: true
        });

        $('#attendance-form-classtype').select2({
            placeholder: "Select classtype",
            allowClear: true
        });

        });
        
    </script>
    <script src="{{asset('js/attendance-script.js')}}"></script>
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
    <h1 class="bounce animated page-heading">Attendance Input</h1>
    <form id="attendance-form">
    <div class="attendance-box" id="attendance-box">
        <div id="attendance-form-box">
            <h2 class="page-sub-heading"><i class="fa fa-pencil-square"></i></h2>
                <div class="container">
                    <div class="form-row">
                        <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="attendance-input-div">
                                <div class="attendance-icon align-middle"><i class="fa fa-book"></i></div>
                                <!--<input class="form-control attendance-input" type="text" placeholder="Program" required=""> -->
                                <select id="attendance-form-program" class="required js-placeholder-program js-example-basic-single form-control attendance-input" name="program" placeholder="Program" required>
                                        <option></option>
                                        <option value="smartie">Smartie</option>
                                        <option value="science">Science</option>
                                        <option value="speak up">Speak up</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="form-row">
                        <div class="col-md-12 col-lg-12 col-xl-12">
                            <div class="attendance-input-div">
                                <div class="attendance-icon align-middle"><i class="fa fa-calendar"></i></div>
                                <input class="form-control attendance-input" type="date" required="" id="date" name="date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="form-row">
                        <div class="col-md-12 col-lg-12 col-xl-12">
                            <div class="attendance-input-div">
                                <div class="attendance-icon align-middle"><i class="fa fa-map-marker"></i></div>
                                <!--<input class="form-control attendance-input" type="text" placeholder="Location" required=""> -->
                                <select id="attendance-form-hour" class="required js-example-basic-single form-control attendance-input" name="hour" placeholder="hour" required="">
                                        <option></option>
                                        <option value="7am-8am">7AM - 8AM</option>
                                        <option value="8am-9am">8AM - 9AM</option>
                                        <option value="9am-10am">9AM - 10AM</option>
                                        <option value="10am-11am">10AM - 11AM</option>
                                        <option value="11am-12am">11AM - 12AM</option>
                                        <option value="12am-1pm">12AM - 1PM</option>
                                        <option value="1pm-2pm">1PM - 2PM</option>
                                        <option value="2pm-3pm">2PM - 3PM</option>
                                        <option value="3pm-4pm">3PM - 4PM</option>
                                        <option value="4pm-5pm">4PM - 5PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="form-row">
                        <div class="col-md-12 col-lg-12 col-xl-12">
                            <div class="attendance-input-div">
                                <div class="attendance-icon align-middle"><i class="fa fa-map-marker"></i></div>
                                <!--<input class="form-control attendance-input" type="text" placeholder="Location" required=""> -->
                                <select id="attendance-form-location" class="required js-example-basic-single form-control attendance-input" name="location" placeholder="Location" required="">
                                        <option></option>
                                        <option value="studio">Studio</option>
                                        <option value="house">Student's house</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="form-row">
                        <div class="col-md-12 col-lg-12 col-xl-12">
                            <div class="attendance-input-div">
                                <div class="attendance-icon align-middle"><i class="fa fa-tag"></i></div>
                                <!--<input class="form-control attendance-input" type="text" placeholder="Class Type" required="">-->
                                <select id="attendance-form-classtype" class="required js-example-basic-single form-control attendance-input" name="class-type" placeholder="Program" required="">
                                        <option></option>
                                        <option value="exclusive">Exclusive</option>
                                        <option value="semi-private">Semi-Private</option>
                                        <option value="mini-group">Mini-Group</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="form-row">
                        <div class="col-11 col-sm-11 col-md-11 col-lg-11 col-xl-11">
                            <div class="attendance-input-div">
                                <div class="attendance-icon align-middle"><i class="fa fa-user"></i></div>
                                    <!--<input class="form-control attendance-input attendance-input-student" type="text" placeholder="Student's Name" required="">-->
                                    <select class="required js-example-basic-single form-control attendance-input attendance-input-student" name="student1" id="student1" required>
                                        <option></option>
                                        @foreach ($students as $s)
                                            <option value='{{$s->name}}'>{{$s->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <div class="col-1 col-sm-1 col-md-1 col-lg-1 text-center align-self-center" id="student-attend-input-check-1">
                            <a onclick="$dc.MarkStudent('#student-attend-input-check-1')">
                                <div class="attendance-confirm align-bottom"><i class="fa fa-check"></i></div>
                            </a>
                            <input type="hidden" name="student-attend-1" id="student-attend-1" value="no">
                        </div>
                    </div>
                </div>
    </div>
            <div class="attendance-input-button-box">
            <div class="attendance-plus">
                <a onclick = "$dc.addStudentInput()">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
                <button class="btn btn-primary attendance-input-button" type="button" onclick="$dc.AttendanceInput()" value="submit">Submit</button></div>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>

    <script src="{{asset('js/bs-init.js')}}"></script>
    
    
</body>

</html>