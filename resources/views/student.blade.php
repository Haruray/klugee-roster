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
    <div class="text-center">
        <div class="container student-profile-img-div">
            <div class="row" style="padding: 0 20px 0 20px;">
                <div class="col-sm-12 col-md-4 col-xl-3 offset-lg-1 text-center">
                    <div class="text-left d-inline-block teacher-profile-img-group">
                        <div class="text-center teacher-profile-img-outline">
                            <img class="student-profile-img" src="{{url('/uploads/students/'.$student->photo)}}">
                            <a href="/students/{{$student->id}}/attendance-history">
                                <button class="btn btn-primary attendance-input-button" type="button" style="margin: 20px 10px 10px 10px;">Attendance History</button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-8 col-lg-7 col-xl-8 offset-lg-0 text-center">
                    <div class="d-inline-block">
                        <p class="teacher-profile-name bold blue" style="margin: 20px 0 0 0;">{{$student->name}}</p>
                        <p class="green bold" style="font-size: 20px;">School</p>
                        <p class="blue bold" style="margin: -20px 0 0 0;font-size: 25px;">{{$student->school_name}}</p>
                        <button data-toggle="modal" data-target="#student-bio" class="btn btn-primary attendance-input-button" type="button" style="margin: 20px 10px 10px 10px;">Student Bio</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h3 class="bounce animated page-heading">Programs</h3>
    <div class="container student-card-container">
        <div class="row">
            <div class="col-lg-12">
                @foreach ($programs as $p)
                <div class="d-inline-block student-program-card col-xl-3 col-lg-3 col-md-4 col-sm-8 col-8"><img class="student-card-profile" src="{{url('/img/'.$p->program.'-logo.png')}}">
                    <p class="student-program-name">{{$p->program}}</p>
                    @if ($p->quota>0)
                        <p class="student-program-payment-paid"><i class="fa fa-check"></i>&nbsp;Paid</p>
                    @else
                        <p class="student-program-payment-late"><i class="fa fa-exclamation"></i>&nbsp;Late Payment</p>
                    @endif

                    <a href="/students/{{$student->id}}/progress-report/{{$p->program}}"><button class="btn btn-primary student-card-button" type="button">Progress Reports</button></a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Modal -->
<div class="modal fade" id="student-bio" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Student Bio</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p style="color: #38b6ff; font-weight:bold; font-size:23px; margin-bottom:-2px;">Name</p>
          <p>{{ $student->name }}</p>
          <p style="color: #38b6ff; font-weight:bold; font-size:23px; margin-bottom:-2px;">Nickname</p>
          <p>{{ $student->nickname }}</p>
          <p style="color: #38b6ff; font-weight:bold; font-size:23px; margin-bottom:-2px;">Address</p>
          <p>{{ $student->address }}</p>
          <p style="color: #38b6ff; font-weight:bold; font-size:23px; margin-bottom:-2px;">Birth Place</p>
          <p>{{ $student->birthplace }}</p>
          <p style="color: #38b6ff; font-weight:bold; font-size:23px; margin-bottom:-2px;">Birth Date</p>
          <p>{{ date('d F Y', strtotime($student->birthdate))}}</p>
          <p style="color: #38b6ff; font-weight:bold; font-size:23px; margin-bottom:-2px;">School Name</p>
          <p>{{ $student->school_name }}</p>
          <p style="color: #38b6ff; font-weight:bold; font-size:23px; margin-bottom:-2px;">Parent</p>
          <p>{{ $student->parent }} {{ $student->parent_name }}</p>
          <p style="color: #38b6ff; font-weight:bold; font-size:23px; margin-bottom:-2px;">Parent Contact</p>
          <p>{{ $student->parent_contact }}</p>
          <p style="color: #38b6ff; font-weight:bold; font-size:23px; margin-bottom:-2px;">Parent Email</p>
          <p>{{ $student->email }}</p>
        </div>
      </div>
    </div>
  </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
</body>

</html>
