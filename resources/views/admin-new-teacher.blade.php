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
    <h1 class="bounce animated page-heading">New User Registration</h1>
    <div class="attendance-box">
        @if ($message = Session::get('sukses'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif

        @if ($message = Session::get('gagal'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif
        <h2 class="page-sub-heading"><i class="fa fa-pencil-square"></i></h2>
        <form name="teacheradd" id="teacheradd" method="POST" action="/users/add/process" onsubmit="return validateForm();">
            @csrf
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-id-badge"></i></div><input name="official-id" class="form-control attendance-input" type="text" placeholder="Official ID" required=""></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-id-card"></i></div><input name="nik" class="form-control attendance-input" type="text" placeholder="NIK" required="" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-user"></i></div><input name="name" class="form-control attendance-input" type="text" placeholder="Name" required=""></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-user-o"></i></div><input name="nickname" class="form-control attendance-input" type="text" placeholder="Nickname" required=""></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-envelope"></i></div><input id="email" name="email" class="form-control attendance-input" type="text" placeholder="Email" required=""></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-map"></i></div><input name="address" class="form-control attendance-input" type="text" placeholder="Address" required=""></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-map-marker"></i></div><input name="birthplace" class="form-control attendance-input" type="text" placeholder="Birth Place" required=""></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <p style="color: white; font-weight:bold; margin:1px 0px 5px 40px;">Birthdate</p>
                            <div class="attendance-icon align-middle"><i class="fa fa-calendar"></i></div>
                            <input style="margin-left: 16px;" name="date" class="form-control attendance-input" type="date" required="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-mobile"></i></div><input name="telp" class="form-control attendance-input" type="text" placeholder="Telephone Number" required="" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-phone"></i></div><input name="telp-emergency" class="form-control attendance-input" type="text" placeholder="Emergency Contact" required="" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-university"></i></div><input name="institution" class="form-control attendance-input" type="text" placeholder="Institution Name" required=""></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <p style="color: white; font-weight:bold; margin:1px 0px 5px 40px;">Join Date</p>
                            <div class="attendance-icon align-middle"><i class="fa fa-calendar"></i></div>
                            <input style="margin-left:16px;" name="join-date" class="form-control attendance-input" type="date" placeholder="Join Date" required="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-sitemap"></i></div>
                            <select style="margin-left:16px;" name="user-type" class="form-control attendance-input" required>
                                <optgroup label="User Type">
                                    <option value="" disabled selected>User Type</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="head teacher">Head Teacher</option>
                                    <option value="admin">Admin Officer</option>
                                    <option value="super admin">Super Admin</option>
                                </optgroup>
                            </select></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div" style="text-align: center;">
                            <p style="color:white; font-weight:bold; font-size:20px; text-align:center;">Teacher Status</p>
                            <div id="teacher-status-checkbox" class="form-check" style="display: inline-block; margin-right:5px; color:white;">
                                <input class="form-check-input" name="teacher-status" type="radio" value="Part Time" id="part-time">
                                <label class="form-check-label" for="part-time">
                                  Part Time
                                </label>
                              </div>
                              <div class="form-check" style="display: inline-block; margin-right:5px; color:white;">
                                <input class="form-check-input" name="teacher-status" type="radio" value="Full Time" id="full-time">
                                <label class="form-check-label" for="full-time">
                                  Full Time
                                </label>
                              </div>
                              <div class="form-check" style="display: inline-block; margin-right:5px; color:white;">
                                <input class="form-check-input" name="teacher-status" type="radio" value="Part Time Contract" id="part-time-contract">
                                <label class="form-check-label" for="part-time-contract">
                                  Part Time Contract
                                </label>
                              </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div" style="text-align: center;">
                            <p style="color:white; font-weight:bold; font-size:20px; text-align:center;">Teaching Method</p>
                            <div id="teaching-method-checkbox" class="form-check" style="display: inline-block; margin-right:5px; color:white;">
                                <input class="form-check-input" name="teaching-method[]" type="checkbox" value="Online" id="tm-online">
                                <label class="form-check-label" for="tm-online">
                                  Online
                                </label>
                              </div>
                              <div class="form-check" style="display: inline-block; margin-right:5px; color:white;">
                                <input class="form-check-input" name="teaching-method[]" type="checkbox"" value="Studio" id="tm-studio">
                                <label class="form-check-label" for="tm-studio">
                                  Studio
                                </label>
                              </div>
                              <div class="form-check" style="display: inline-block; margin-right:5px; color:white;">
                                <input class="form-check-input" name="teaching-method[]" type="checkbox" value="Student's House" id="tm-house">
                                <label class="form-check-label" for="tm-house">
                                  Student's House
                                </label>
                              </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div" style="text-align: center;">
                            <p style="color:white; font-weight:bold; font-size:20px; text-align:center;">Teaching Program</p>
                            @foreach ($program as $p)
                            <div id="teaching-program-checkbox" class="form-check" style="display: inline-block; margin-right:5px; color:white;">
                                <input class="form-check-input" name="teaching-program[]" type="checkbox" value="{{ $p->program }}" id="{{ explode(" ",$p->program)[0] }}">
                                <label class="form-check-label" for="{{ explode(" ",$p->program)[0] }}">
                                  {{ $p->program }}
                                </label>
                              </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="attendance-input-button-box"><button class="btn btn-primary attendance-input-button" type="submit" value="submit" name="submit">Submit</button></div>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>

</body>
<script>

    function validateForm() {
        let x = document.forms["teacheradd"];
        let mail = document.getElementById('email').value;
        if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))){
                Swal.fire({
                    icon : 'error',
                    title: 'Oops...',
                    text: 'Invalid Email Address.'
                });
                return false;
        }

        if (x["user-type"].value == "") {
            Swal.fire({
                icon : 'error',
                title: 'Oops...',
                text: 'User Type must be filled out.'
            });
            return false;
        }
        if ($('div#teaching-program-checkbox :checkbox:checked').length <= 0){
            Swal.fire({
                icon : 'error',
                title: 'Oops...',
                text: 'Teaching Program checkbox must be filled out.'
            });
            return false;
        }
        if ($('div#teaching-method-checkbox :checkbox:checked').length <= 0){
            Swal.fire({
                icon : 'error',
                title: 'Oops...',
                text: 'Teaching Method checkbox must be filled out.'
            });
            return false;
        }
    }
</script>
</html>
