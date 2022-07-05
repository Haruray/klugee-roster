<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>klugee-roster</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('css/Login-Form-Clean.css')}}">
    <link rel="stylesheet" href="{{asset('css/Navigation-Clean.css')}}">
    <link rel="stylesheet" href="{{asset('css/Navigation-with-Button.css')}}">
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <script src="{{asset('js/schedulemanage-script.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css" />

    <script>
        $(document).ready(function() {
            $('.select').select2({
            width: 'style',
            theme: "bootstrap"
        });

            $('#teacher-name').select2({
            placeholder: "Select a teacher",
            allowClear: true,
            theme: "bootstrap"
        });

        $('#students').select2({
            placeholder: "Select students",
            allowClear: true,
            width: '100%',
            theme: "bootstrap"
        });
        $('#students2').select2({
            placeholder: "Select students",
            allowClear: true,
            width: '100%',
            theme: "bootstrap"
        });

        });
    </script>
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
    <h2 class="text-center bounce animated page-heading">Manage Schedules</h2>
    <div id="main-content" class="container">
        <div class="schedule-teacher-search-div">
                <select style="display:inline-block;"class="select required form-control schedule-teacher-search-bar" name="teacher-name" id="teacher-name" required>
                    <option></option>
                    @foreach ($teachers as $t)
                        <option value='{{$t->id}}'>{{$t->name}}</option>
                    @endforeach

                </select>
                <button onclick="$dc.ScheduleSearch()" class="btn btn-success text-center" type="button" style=""><i class="fa fa-search"></i>&nbsp;Search</button>
        </div>
        <div id="schedules">
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Schedule Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
            <form action="">
                @csrf
                <select style="margin-top:10px;" name="day" id="day" class="form-control">
                    <option value="" disabled selected>Day</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
                <input style="margin-top:10px;" id="time" name="time" type="time" class="form-control" placeholder="Time">
                <select style="margin-top:10px;" name="location" id="location" class="form-control">
                    <option value="" disabled selected>Location</option>
                    <option value="Studio">Studio</option>
                    <option value="Online">Online</option>
                    <option value="Visit-Near">Visit-Near</option>
                    <option value="Visit-Far">Visit-Far</option>
                </select>
                <select style="margin-top:10px;" name="class-type" id="class-type" class="form-control">
                    <option value="" disabled selected>Class Type</option>
                    <option value="Exclusive">Exclusive</option>
                    <option value="Semiprivate">Semi Private</option>
                    <option value="School">School</option>
                </select>
                <select style="margin-top:10px;" name="program" id="program" class="form-control">
                    <option value="" disabled selected>Program</option>
                    @foreach ($program as $p)
                        <option value="{{$p->program }}">{{ $p->program }}</option>
                    @endforeach
                </select>

                <input style="margin-top:10px; margin-bottom:10px;" name="subject" id="subject" type="text" class="form-control" placeholder="Subject">
                <select style="margin-top:15px;" id="students" class="students select" name="students[]" multiple="multiple">
                    @foreach ($student as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                  </select>
                <input type="hidden" id="teacher-id" name="teacher-id" value="">
            </form>
        </div>
      </div>
      <div class="modal-footer">
        <button onclick="$dc.ScheduleAdd()" type="button" class="btn btn-primary">Add</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="scheduleEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Schedule Edit</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container">
              <form id="scheduleEditForm" action="">
                  @csrf
                  <select style="margin-top:10px;" name="day" id="day" class="form-control">
                      <option value="" disabled selected>Day</option>
                      <option value="Monday">Monday</option>
                      <option value="Tuesday">Tuesday</option>
                      <option value="Wednesday">Wednesday</option>
                      <option value="Thursday">Thursday</option>
                      <option value="Friday">Friday</option>
                      <option value="Saturday">Saturday</option>
                      <option value="Sunday">Sunday</option>
                  </select>
                  <input style="margin-top:10px;" id="time" name="time" type="time" class="form-control" placeholder="Time">
                  <select style="margin-top:10px;" name="location" id="location" class="form-control">
                      <option value="" disabled selected>Location</option>
                      <option value="Studio">Studio</option>
                      <option value="Online">Online</option>
                      <option value="Visit-Near">Visit-Near</option>
                      <option value="Visit-Far">Visit-Far</option>
                  </select>
                  <select style="margin-top:10px;" name="class-type" id="class-type" class="form-control">
                      <option value="" disabled selected>Class Type</option>
                      <option value="Exclusive">Exclusive</option>
                      <option value="Semiprivate">Semi Private</option>
                      <option value="School">School</option>
                  </select>
                  <select style="margin-top:10px;" name="program" id="program" class="form-control">
                      <option value="" disabled selected>Program</option>
                      @foreach ($program as $p)
                          <option value="{{$p->program }}">{{ $p->program }}</option>
                      @endforeach
                  </select>

                  <input style="margin-top:10px; margin-bottom:10px;" name="subject" id="subject" type="text" class="form-control" placeholder="Subject">
                  <select style="margin-top:15px;" id="students2" class="students2 select" name="students[]" multiple="multiple">
                      @foreach ($student as $s)
                          <option value="{{ $s->id }}">{{ $s->name }}</option>
                      @endforeach
                    </select>
                  <input type="hidden" id="schedule-id" name="schedule-id" value="">
              </form>
          </div>
        </div>
        <div class="modal-footer">
          <button id="schedule-edit-submit" type="button" class="btn btn-primary">Confirm</button>
        </div>
      </div>
    </div>
  </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
</body>

</html>
