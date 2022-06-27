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

                <div class="nav-item-div"><a class="login" href="/profile"><img class="profile-img" src="{{url('/uploads/profile-pictures/'.auth()->user()->photo)}}"><p class="d-inline-block nav-item-text">Teacher {{auth()->user()->name}}</p></a></div>
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
    <h1 class="bounce animated page-heading">DATA MANAGEMENT</h1>
    <div class="container">
        <div class="management-box">
            <div class="management-sub-box">
                <h3 class="management-heading">Password Change</h3>
            </div>
            <div class="management-sub-box">
                <h3 class="management-heading">Programs</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Available Programs</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($programs as $p)
                                <tr>
                                    <td>{{ $p->program }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><button class="btn btn-primary" type="button"  data-toggle="modal" data-target="#program-add">Add Program</button></div>
            <div class="management-sub-box">
                <h3 class="management-heading">Fee List</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Program</th>
                                <th>Level</th>
                                <th>Exclusive (IDR)</th>
                                <th>Semi-Private (IDR)</th>
                                <th>School (IDR)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i=0 ; $i < count($feelist) ; $i+=$feelist->where('program',$feelist[$i]->program)->count())
                                <tr id="fee-{{ $feelist[$i]->program }}-{{ $feelist[$i]->level }}">
                                    <td rowspan="{{ $feelist->where('program',$feelist[$i]->program)->count() }}">{{ $feelist[$i]->program }}</td>
                                    <td>{{ $feelist[$i]->level }}</td>
                                    <td id="fee-{{ $feelist[$i]->program }}-{{ $feelist[$i]->level }}-exclusive">{{ $feelist[$i]->nominal_exclusive/1000 }}K</td>
                                    <td id="fee-{{ $feelist[$i]->program }}-{{ $feelist[$i]->level }}-semiprivate">{{ $feelist[$i]->nominal_semiprivate/1000 }}K</td>
                                    <td id="fee-{{ $feelist[$i]->program }}-{{ $feelist[$i]->level }}-school">{{ $feelist[$i]->nominal_school/1000 }}K</td>
                                    <td><button class="btn btn-warning" onclick="editFee('fee-{{ $feelist[$i]->program }}-{{ $feelist[$i]->level }}', {{ $feelist[$i]->id }}, '{{ $feelist[$i]->program}}')">Edit</button></td>
                                </tr>
                                @for ($j=$i+1 ; $j < $i+$feelist->where('program',$feelist[$i]->program)->count() ; $j++)
                                <tr id="fee-{{ $feelist[$j]->program }}-{{ $feelist[$j]->level }}">
                                    <td>{{ $feelist[$j]->level }}</td>
                                    <td id="fee-{{ $feelist[$j]->program }}-{{ $feelist[$j]->level }}-exclusive">{{ $feelist[$j]->nominal_exclusive/1000 }}K</td>
                                    <td id="fee-{{ $feelist[$j]->program }}-{{ $feelist[$j]->level }}-semiprivate">{{ $feelist[$j]->nominal_semiprivate/1000 }}K</td>
                                    <td id="fee-{{ $feelist[$j]->program }}-{{ $feelist[$j]->level }}-school">{{ $feelist[$j]->nominal_school/1000 }}K</td>
                                    <td><button class="btn btn-warning" onclick="editFee('fee-{{ $feelist[$j]->program }}-{{ $feelist[$j]->level }}', {{ $feelist[$j]->id }}, '{{ $feelist[$j]->program}}')">Edit</button></td>
                                </tr>
                                @endfor
                            @endfor
                        </tbody>
                    </table>
                </div><button class="btn btn-primary" type="button" data-toggle="modal" data-target="#fee-add">Add Fee</button></div>
            <div class="management-sub-box">
                <h3 class="management-heading">Incentives</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Incentive Name</th>
                            <th>Receiver</th>
                            <th>Nominal (IDR)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incentive as $i)
                            <tr>
                                <td>{{ $i->name }}</td>
                                <td>{{ $i->receiver }}</td>
                                <td id="incentive-{{ $i->name }}">{{ $i->nominal/1000 }}K</td>
                                <td><button class="btn btn-warning" onclick="editIncentive('{{ $i->name }}',{{ $i->id }})">Edit</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="management-sub-box">
                <h3 class="management-heading">Salaries</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Nominal (IDR)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salary as $s)
                            <tr>
                                <td>{{ ucwords($s->position) }}</td>
                                <td>{{ $s->status }}</td>
                                <td id="salary-{{ $s->position }}-{{ $s->status }}">{{ $s->nominal/1000 }}K</td>
                                <td><button class="btn btn-warning" onclick="editSalary('salary-{{ $s->position }}-{{ $s->status }}',{{ $s->id }})">Edit</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="management-sub-box">
                <h3 class="management-heading">Parents Partner</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Parent Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($partner as $p)
                            <tr>
                                <td>{{ $p->referrer_name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#partner-add">Add Partner</button>
            </div>

        </div>
    </div>
    <!-- FEE EDIT MODAL -->
    <div class="modal fade" id="edit-fee" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 id="edit-title" class="modal-title">Fee Edit</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container">
                  <form id="edit-fee-form" method="POST" action="/management/fee-edit">
                      @csrf
                    <input type="hidden" name="fee-id" value="" id="fee-id">
                    <p style="margin-bottom: 0px; margin-top:10px;">Exclusive Fee</p>
                    <input class="form-control" type="text" name="exclusive" id="exclusive">
                    <p style="margin-bottom: 0px; margin-top:10px;">Semi-Private Fee</p>
                    <input class="form-control" type="text" name="semiprivate" id="semiprivate">
                    <p style="margin-bottom: 0px; margin-top:10px;">School Fee</p>
                    <input class="form-control" type="text" name="school" id="school">
              </div>
            </div>
            <div class="modal-footer">
              <button id="schedule-edit-submit" type="submit" value="submit" class="btn btn-primary">Confirm</button>
            </form>
            </div>
          </div>
        </div>
      </div>

      <!-- INCENTIVE EDIT MODAL -->
      <div class="modal fade" id="edit-incentive" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 id="edit-title-incentive" class="modal-title">Incentive Edit</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container">
                  <form method="POST" id="edit-incentive-form" action="/management/incentive-edit">
                      @csrf
                    <input type="hidden" value="" name="incentive-id" id="incentive-id">
                    <p style="margin-bottom: 0px; margin-top:10px;">Incentive Nominal</p>
                    <input class="form-control" type="text" name="incentive-input" id="incentive-input">
              </div>
            </div>
            <div class="modal-footer">
              <button id="schedule-edit-submit" type="submit" value="submit" class="btn btn-primary">Confirm</button>
            </form>
            </div>
          </div>
        </div>
      </div>

      <!-- FEE ADD MODAL -->
      <div class="modal fade" id="fee-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 id="add-title-fee" class="modal-title">Fee Add</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container">
                  <form method="POST" id="add-fee-form" action="/management/fee-add">
                      @csrf
                    <p style="margin-bottom: 0px; margin-top:10px;">Program Name</p>
                    <select class="form-control" name="fee-program" id="fee-program">
                        @foreach ($programs as $p)
                            <option value="{{ $p->program }}">{{ $p->program }}</option>
                        @endforeach
                    </select>
                    <p style="margin-bottom: 0px; margin-top:10px;">Level</p>
                    <input class="form-control" type="text" name="level-add" id="level-add" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                    <p style="margin-bottom: 0px; margin-top:10px;">Exclusive Fee</p>
                    <input class="form-control" type="text" name="exclusive-add" id="exclusive-add">
                    <p style="margin-bottom: 0px; margin-top:10px;">Semi-Private Fee</p>
                    <input class="form-control" type="text" name="semiprivate-add" id="semiprivate-add">
                    <p style="margin-bottom: 0px; margin-top:10px;">School Fee</p>
                    <input class="form-control" type="text" name="school-add" id="school-add">

              </div>
            </div>
            <div class="modal-footer">
              <button id="schedule-edit-submit" type="submit" value="submit" class="btn btn-primary">Confirm</button>
            </form>
            </div>
          </div>
        </div>
      </div>

      <!-- PROGRAM ADD MODAL -->
      <div class="modal fade" id="program-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 id="add-title-program" class="modal-title">Program Add</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container">
                  <form method="POST" id="add-program-form" action="/management/program-add">
                      @csrf
                    <p style="margin-bottom: 0px; margin-top:10px;">New Program Name</p>
                    <input class="form-control" type="text" name="program-add" id="program-add">

              </div>
            </div>
            <div class="modal-footer">
              <button id="schedule-edit-submit" type="submit" value="submit" class="btn btn-primary">Confirm</button>
            </form>
            </div>
          </div>
        </div>
      </div>

    <!-- SALARY EDIT MODAL -->
    <div class="modal fade" id="edit-salary" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 id="edit-title-salary" style="text-transform: capitalize;" class="modal-title">Salary Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form method="POST" id="edit-salary-form" action="/management/salary-edit">
                        @csrf
                    <input type="hidden" value="" name="salary-id" id="salary-id">
                    <p style="margin-bottom: 0px; margin-top:10px;">Salary Nominal</p>
                    <input class="form-control" type="text" name="salary-input" id="salary-input">
                </div>
            </div>
            <div class="modal-footer">
                <button id="schedule-edit-submit" type="submit" value="submit" class="btn btn-primary">Confirm</button>
            </form>
            </div>
            </div>
        </div>
        </div>

    <!-- PARENT PARTNER ADD MODAL -->
      <div class="modal fade" id="partner-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 id="add-title-partner" class="modal-title">Parent Partner Add</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container">
                  <form method="POST" id="add-partner-form" action="/management/partner-add">
                      @csrf
                    <p style="margin-bottom: 0px; margin-top:10px;">Parent Name</p>
                    <select class="form-control" name="parent" id="parent">
                        @foreach ($parent as $p)
                            <option value="{{ $p->id }}">{{ $p->parent }} {{ $p->parent_name }}</option>
                        @endforeach
                    </select>

              </div>
            </div>
            <div class="modal-footer">
              <button id="schedule-edit-submit" type="submit" value="submit" class="btn btn-primary">Confirm</button>
            </form>
            </div>
          </div>
        </div>
      </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>


</body>

<script>
    function editFee(name, id, program){
        let mainmodal = document.getElementById("edit-fee");
        $('#edit-fee').modal('toggle');
        mainmodal.querySelector("#fee-id").value = id;
        mainmodal.querySelector("#edit-title").innerHTML = program + " Fee Edit";
        mainmodal.querySelector("#exclusive").value = document.getElementById(name+"-exclusive").innerHTML.replace("K","000");
        mainmodal.querySelector("#semiprivate").value = document.getElementById(name+"-semiprivate").innerHTML.replace("K","000");
        mainmodal.querySelector("#school").value = document.getElementById(name+"-school").innerHTML.replace("K","000");

    }

    function editIncentive(name,id){
        let mainmodal = document.getElementById("edit-incentive");
        $('#edit-incentive').modal('toggle');
        mainmodal.querySelector("#incentive-id").value = id;
        mainmodal.querySelector("#edit-title-incentive").innerHTML = name + " Incentive Edit";
        mainmodal.querySelector("#incentive-input").value = document.getElementById('incentive-'+name).innerHTML.replace("K","000");

    }

    function editSalary(name,id){
        let mainmodal = document.getElementById("edit-salary");
        $('#edit-salary').modal('toggle');
        mainmodal.querySelector("#salary-id").value = id;
        mainmodal.querySelector("#edit-title-salary").innerHTML = name.replaceAll("-"," ");
        mainmodal.querySelector("#salary-input").value = document.getElementById(name).innerHTML.replace("K","000");
    }

</script>

</html>
