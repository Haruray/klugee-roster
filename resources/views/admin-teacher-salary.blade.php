<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" href="{{ asset('img/2.png') }}">
    <title>({{ count(auth()->user()->unreadNotifications) }}) Klugee Roster Management</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/font-awesome.min.css') }}">
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
    <h1 class="bounce animated page-heading" style="color: rgb(48,121,200);">Teacher Salary</h1>
    <div class="attendance-box" style="background-color: rgb(48,121,200);">

        @if ($message = Session::get('sukses'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">??</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif

        @if ($message = Session::get('gagal'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">??</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif

        <h2 class="page-sub-heading"><i class="fa fa-pencil-square"></i></h2>
        <form name="salary-form" method="POST" action="/accounting/teacher-payment/salary/process" onsubmit="return validateForm()">
            @csrf
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-calendar"></i></div><input name="date" class="form-control attendance-input" type="date" required=""></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-user"></i></div>
                            <select style="margin-left: 17px;" name="teacher-name" class="form-control attendance-input">
                                <optgroup label="Teacher Name">
                                    <option value="" disabled selected>Teacher Name</option>
                                    <option value="all">All Teacher</option>
                                    @foreach ($teachers as $t)
                                    <option value="{{$t->id}}">{{ $t->name }}</option>
                                @endforeach
                                </optgroup>
                            </select>
                            </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div id="salary-amount" class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-sitemap"></i></div>
                            <input style="margin-left:17px;" name="divisi" class="form-control attendance-input" type="text" placeholder="Divisi" required="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div id="salary-amount" class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-archive"></i></div>
                            <input style="margin-left:17px;" name="no-kontrak" class="form-control attendance-input" type="text" placeholder="No. Kontrak" required="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div id="salary-amount" class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-archive"></i></div>
                            <input style="margin-left:17px;" name="npsn" class="form-control attendance-input" type="text" placeholder="NPSN" required="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div id="salary-amount" class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-archive"></i></div>
                            <input style="margin-left:17px;" name="NILPNF" class="form-control attendance-input" type="text" placeholder="Nomor Izin Lembaga Pendidikan Non-Formal" required="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-exclamation"></i></div><input name="notes" class="form-control attendance-input" type="text" placeholder="Notes"></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-credit-card"></i></div>
                            <select style="margin-left: 17px;" name="payment_method" class="form-control attendance-input">
                                <optgroup label="Payment Method">
                                    <option value="" disabled selected>Payment Method</option>
                                    <option value="Cash">Cash</option>
                                    <option value="ATM">ATM</option>
                                    <option value="Other">Other</option>
                                </optgroup>
                            </select></div>
                    </div>
                </div>
            </div>
            <p style="color: white; font-size:15px; padding:10px; text-align:center;">
                <strong>Notice : </strong> The salary will by <strong>automatically generated</strong> by the system.
                Make sure the salary amount is correct by checking the <strong><a style="color: #fff5cc;" href="/management">management page</a></strong>.
                The database will only record the <strong>main salary</strong>,
                but the generated check will contain all the fees and incentives for the month.
                Please make sure it's correct by checking the corresponding teacher profile.
            </p>
            <div class="attendance-input-button-box"><button class="btn btn-primary attendance-input-button" type="submit" value="submit" name="submit">Submit</button></div>
        </form>
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>

</body>
<script>
    function validateForm() {
        let x = document.forms["salary-form"];
        if (x["teacher-name"].value == "") {
            Swal.fire({
                icon : 'error',
                title: 'Oops...',
                text: 'Teacher Name must be filled out.'
            });
            return false;
        }
        if (x["payment_method"].value == "") {
            Swal.fire({
                icon : 'error',
                title: 'Oops...',
                text: 'Payment Method must be filled out.'
            });
            return false;
        }
        Swal.fire({
                icon : 'success',
                title: 'Form Submitted!',
                text: 'Please wait until the payment receipt is downloaded.'
            });
    }
</script>
</html>
