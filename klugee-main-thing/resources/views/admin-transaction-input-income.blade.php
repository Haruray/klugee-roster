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

                <div class="nav-item-div"><a class="login" href="/profile"><img class="profile-img" src="{{url('/uploads/profile-pictures/'.auth()->user()->id_teacher.'_'.auth()->user()->name.'.png')}}"><p class="d-inline-block nav-item-text">Teacher {{auth()->user()->name}}</p></a></div>
                @if (auth()->user()->user_type == "admin")
                    <div class="text-left nav-item-div">
                        <a class="login" href="/management">
                            <div class="d-inline-block"><i class="fa fa-cog nav-img yellow"></i></div>
                            <p class="d-inline-block nav-item-text">Management</p>
                        </a>
                    </div>
                @endif
                <div class="text-left nav-item-div">
                    <a class="login" href="/notification">
                        <div class="d-inline-block"><i class="fa fa-bell-o notif-img yellow"></i><img class="warning-sign" src="{{asset('img/15.png')}}"></div>
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
    <h1 class="bounce animated page-heading" style="color: rgb(48,121,200);">Input Transaction (Income)</h1>
    <div class="attendance-box" style="background-color: rgb(48,121,200);">

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
        <form name="income-form" method="POST" action="/accounting/input-transaction/income/process">
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
                            <div class="attendance-icon align-middle"><i class="fa fa-check-circle"></i></div><select name="transaction_type" class="form-control attendance-input"><optgroup label="Transaction Type"><option value="Registration">Registration</option><option value="Other">Other (Merchandise, Buku, etc)</option></optgroup></select></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-check-circle"></i></div><select name="sub_transaction" class="form-control attendance-input"><optgroup label="Sub-Transaction"><option value="other">Other</option></optgroup></select></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-check-circle"></i></div><input name="detail" class="form-control attendance-input" type="text" placeholder="Detail" required=""></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-dollar"></i></div><input name="nominal" class="form-control attendance-input" type="text" placeholder="Amount" required="" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-exclamation"></i></div><input name="notes" class="form-control attendance-input" type="text" placeholder="Notes" required=""></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-user"></i></div>
                            <select name="pic" class="form-control attendance-input">
                                <optgroup label="PIC">
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
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-credit-card"></i></div>
                            <select name="payment_method" class="form-control attendance-input">
                                <optgroup label="Payment Method">
                                    <option value="Cash">Cash</option>
                                    <option value="Atm">ATM</option>
                                    <option value="Other">Other</option>
                                </optgroup>
                            </select></div>
                    </div>
                </div>
            </div>
            <div class="attendance-input-button-box"><button class="btn btn-primary attendance-input-button" type="submit" value="submit" name="submit">Submit</button></div>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>

</body>
</html>
