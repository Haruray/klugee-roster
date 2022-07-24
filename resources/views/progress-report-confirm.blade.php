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
    <div class="tada animated attendance-box" style="background-color: #00c2cb;">
        <h3 class="page-sub-heading">Progress report is&nbsp;<span class="yellow">filled</span></h3>
        <h1 class="page-sub-heading"><i class="fa fa-check swing animated infinite input-confirm-check"></i></h1>
        <p class="input-confirm-description">
            @for ($i=0 ; $i < count($progress) ; $i++)
                @if ($i != count($progress)-1)
                    {{ $progress[$i]->name }}
                    @if ($progress[$i]->homework)
                    (Izin, Homework)
                    @elseif ($progress[$i]->alpha)
                    (Alpha)
                    @endif
                    ,
                @else
                    {{ $progress[$i]->name }}
                    @if ($progress[$i]->homework)
                    (Izin, Homework)
                    @elseif ($progress[$i]->alpha)
                    (Alpha)
                    @endif
                @endif
            @endfor
            <br>
            {{ date('l',strtotime($progress_no_null[0]->date)) }}, <span id="date-progress">{{ date('d-m-Y', strtotime($progress_no_null[0]->date)) }}</span> <br>
            {{ $progress_no_null[0]->program }}, Level <span id="level-progress">{{ $progress_no_null[0]->level }} </span><br>
            <span id="unit-progress">{{ $progress_no_null[0]->unit }} </span><br>
            <span id="last-exercise-progress">{{ $progress_no_null[0]->last_exercise }} </span><br>
            @for ($i=0 ; $i < count($progress) ; $i++)
                @if (!$progress[$i]->alpha)
                {{ $progress[$i]->name }}'s Score : {{ $progress[$i]->score }} <br>

                @endif
            @endfor
        <div class="input-confirm-buttons"><button class="btn btn-primary d-block input-confirm-button-alternative"
             type="button" onclick="editProgress()">Edit Progress Report</button></div>
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
        <!-- PROGRESS REPORT EDIT MODAL -->
        <div class="modal fade" id="edit-progress" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 id="edit-title" class="modal-title">Progress Report Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-progress-form" method="POST" action="/progress-edit">
                        @csrf
                        <div class="container">
                            <div class="form-row">
                                <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="attendance-input-div">
                                        <select style="margin-left:18px;" name="level" id="level" class="form-control attendance-input" style="margin-left: 15px;" required>
                                            <option value="" disabled>Level</option>
                                            @foreach ($levels as $l)
                                                @if ($l->level == $level_ouch)
                                                    <option value="{{ $l->level }}" selected>{{ $l->level }}</option>
                                                @else
                                                <option value="{{ $l->level }}">{{ $l->level }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="form-row">
                                <div class="col-md-12 col-lg-12 col-xl-12">
                                    <div class="attendance-input-div">
                                        <input id="unit" name="unit" class="form-control attendance-input" type="text" placeholder="Unit" required></div>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="form-row">
                                <div class="col-md-12 col-lg-12 col-xl-12">
                                    <div class="attendance-input-div">
                                        <input id="last_exercise" name="last_exercise" class="form-control attendance-input" type="text" placeholder="Last Exercise" required></div>
                                </div>
                            </div>
                        </div>
                        @foreach ($progress as $p)
                        @if (!$p->alpha)
                        <div class="container">
                            <div class="form-row">
                                <div class="col-md-12 col-lg-12 col-xl-12">
                                    <div class="attendance-input-div">
                                        <input id="score-{{$p->student_id}}" name="score-{{$p->student_id}}" class="form-control attendance-input" type="text" placeholder="{{$p->nickname}}'s Score"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ $p->score }}" required></div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach

                        <div class="container">
                            <div class="form-row">
                                <div class="col-md-12 col-lg-12 col-xl-12">
                                    <div class="attendance-input-div">
                                        <input id="documentation" name="documentation" class="form-control attendance-input" type="file" placeholder="Documentation"></div>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="form-row">
                                <div class="col-md-12 col-lg-12 col-xl-12">
                                    <div class="attendance-input-div">
                                        <textarea id="note" name="note" class="form-control attendance-input" placeholder="Note" value="{{ $progress[0]->note }}"></textarea></div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="attendance_id" name="attendance_id" value="{{$attendance_id}}">
                </div>
                <div class="modal-footer">
                    <button id="schedule-edit-submit" type="submit" value="submit" class="btn btn-primary">Confirm</button>
                </form>
                </div>
                </div>
            </div>
            </div>
    <script>
        function editProgress(){
        let mainmodal = document.getElementById("edit-progress");
        $('#edit-progress').modal('toggle');
        mainmodal.querySelector("#unit").value = document.getElementById("unit-progress").innerHTML;
        mainmodal.querySelector("#last_exercise").value = document.getElementById("last-exercise-progress").innerHTML;
    }
    </script>

</body>

</html>
