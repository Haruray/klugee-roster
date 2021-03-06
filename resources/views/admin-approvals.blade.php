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
    <h1 class="bounce animated page-heading" style="color: rgb(48,121,200);">APPROVALS</h1>
    <div class="container">
        <div style="margin: 0 10px 0 10px;">
            <h2 class="page-heading" style="text-align:left;">Fees Approvals</h2>
            <div class="table-responsive">
                <table style="margin-top:20px; margin-bottom:30px;" id="progress-report-table" class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Teacher Name</th>
                            <th>Attendance Info</th>
                            <th>Teaching Info</th>
                            <th>Fee</th>
                            <th>Lunch Incentive</th>
                            <th>Transport Incentive</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @for ($i = 0 ; $i < count($fee) ; $i+=$fee->where('date',$fee[$i]->date)->count())
                                <td rowspan="{{$fee->where('date',$fee[$i]->date)->count()}}">{{date('l',strtotime($fee[$i]->date))}}, {{date('d/m/Y',strtotime($fee[$i]->date))}}</td>
                                <td>{{ $fee[$i]->name }}</td>
                                <td><button type="button" class="btn btn-primary" onclick="$dc2.AttendanceInfo({{ $fee[$i]->id_attendance }})">Attendance Info</button></td>
                                <td><button type="button" class="btn btn-primary" onclick="$dc2.TeachingInfo({{ $fee[$i]->id_attendance }})">Progress Report</button></td>
                                <td id="{{ $fee[$i]->id_fee }}-main-fee">{{$fee[$i]->fee_nominal}}</td>
                                <td id="{{ $fee[$i]->id_fee }}-lunch-fee">{{$fee[$i]->lunch_nominal}}</td>
                                <td id="{{ $fee[$i]->id_fee }}-transport-fee">{{$fee[$i]->transport_nominal}}</td>
                                <td>{{$fee[$i]->fee_nominal + $fee[$i]->lunch_nominal + $fee[$i]->transport_nominal}}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/accounting/approvals/approve-fee/{{  $fee[$i]->id_fee }}"><button class="btn btn-success" type="button">Approve</button></a>
                                        <button onclick="editFee({{ $fee[$i]->id_fee }})" class="btn btn-warning">Edit</button>
                                        <a href="/accounting/approvals/delete-fee/{{  $fee[$i]->id_fee }}"><button class="btn btn-danger" type="button">Delete</button></a>
                                    </div>
                                </td>
                        </tr>
                                @for ($j = $i+1 ; $j < $fee->where('date',$fee[$i]->date)->count()+$i ; $j++)
                                <tr>
                                    <td>{{ $fee[$j]->name }}</td>
                                    <td><button type="button" class="btn btn-primary" onclick="$dc2.AttendanceInfo({{ $fee[$j]->id_attendance }})">Attendance Info</button></td>
                                    <td><button type="button" class="btn btn-primary" onclick="$dc2.TeachingInfo({{ $fee[$j]->id_attendance }})">Progress Report</button></td>
                                    <td id="{{ $fee[$j]->id_fee }}-main-fee">{{$fee[$j]->fee_nominal}}</td>
                                    <td id="{{ $fee[$j]->id_fee }}-lunch-fee">{{$fee[$j]->lunch_nominal}}</td>
                                    <td id="{{ $fee[$j]->id_fee }}-transport-fee">{{$fee[$j]->transport_nominal}}</td>
                                    <td>{{$fee[$j]->fee_nominal + $fee[$j]->lunch_nominal + $fee[$j]->transport_nominal}}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/accounting/approvals/approve-fee/{{  $fee[$j]->id_fee }}"><button class="btn btn-success" type="button">Approve</button></a>
                                            <button onclick="editFee({{ $fee[$j]->id_fee }})" class="btn btn-warning">Edit</button>
                                            <a href="/accounting/approvals/delete-fee/{{  $fee[$j]->id_fee }}"><button class="btn btn-danger" type="button">Delete</button></a>
                                        </div>
                                    </td>
                                </tr>
                                @endfor
                            @endfor

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="container">
        <div style="margin: 0 10px 0 10px;">
            <h2 class="page-heading" style="text-align:left;">Salary Approvals</h2>
            <div class="table-responsive">
                <table style="margin-top:20px; margin-bottom:30px;" id="progress-report-table" class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Teacher Name</th>
                            <th>Description</th>
                            <th>Nominal</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                            @foreach ($salary as $s)
                            <tr>
                                <td>{{date('l',strtotime($s->date))}}, {{date('d/m/Y',strtotime($s->date))}}</td>
                                <td>{{ $s->name }}</td>
                                <td>{{$s->note}}</td>
                                <td>{{$s->nominal}}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/accounting/approvals/approve-salary/{{  $s->id }}"><button class="btn btn-success" type="button">Approve</button></a>
                                        <a href="/accounting/approvals/delete-salary/{{  $s->id }}"><button class="btn btn-danger" type="button">Delete</button></a>
                                    </div>
                                </td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="container">
        <div style="margin: 0 10px 0 10px;">
            <h2 class="page-heading" style="text-align:left;">Incentives Approvals</h2>
            <div class="table-responsive">
                <table style="margin-top:20px; margin-bottom:30px;" id="progress-report-table" class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Incentive Name</th>
                            <th>Teacher Name</th>
                            <th>Nominal</th>
                            <th>Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($incentive as $i)
                        <tr>
                                <td>{{date('l',strtotime($i->date))}}, {{date('d/m/Y',strtotime($i->date))}}</td>
                                <td>{{ $i->name }}</td>
                                <td>{{$i->teacher_name}}</td>
                                <td>{{$i->nominal}}</td>
                                <td>{{ $i->note }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/accounting/approvals/approve-incentive/{{  $i->id }}"><button class="btn btn-success" type="button">Approve</button></a>
                                        <a href="/accounting/approvals/delete-incentive/{{  $i->id }}"><button class="btn btn-danger" type="button">Delete</button></a>
                                    </div>
                                </td>
                                </tr>
                            @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="container">
        <div style="margin: 0 10px 0 10px;">
            <h2 class="page-heading" style="text-align:left;">Referrals Approvals</h2>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th rowspan="2">Date of join</th>
                            <th rowspan="2">Student</th>
                            <th style="text-align: center;" colspan="3">Bonus Referral Parents</th>
                            <th rowspan="2">PIC Front Admin</th>
                            <th rowspan="2">Actions</th>
                        </tr>
                        <tr>
                            <th>Parents</th>
                            <th>Referral</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($referrals as $r)
                        <tr>
                            <td>{{ date('l',strtotime($r->date)) }}, {{ date('d-m-Y',strtotime($r->date))}}</td>
                            <td>{{ $r->registering_student_name }}</td>
                            <td>{{ $r->referrer_name }}</td>
                            <td>{{ $r->referral_nominal }}</td>
                            @if ($r->status_referral)
                            <td><i class="fa fa-check-circle" style="font-size: 40px;color: #6ce679;"></i></td>
                            @elseif (!$r->status_referral && $r->referral_nominal==0)
                            <td><i class="fa fa-exclamation-circle" style="color: red;font-size: 40px;"></i></td>
                            @else
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/accounting/approvals/approve-referral/{{  $r->id }}"><button class="btn btn-success" type="button">Approve</button></a>
                                    <a href="/accounting/approvals/delete-referral/{{  $r->id }}"><button class="btn btn-danger" type="button">Delete</button></a>
                                </div>
                            </td>
                            @endif

                            <td>{{ $r->pic_front_admin }}</td>
                            @if ($r->status_front_admin)
                            <td><i class="fa fa-check-circle" style="font-size: 40px;color: #6ce679;"></i></td>
                            @elseif (!$r->status_front_admin && $r->pic_front_admin == 0)
                            <td><i class="fa fa-exclamation-circle" style="color: red;font-size: 40px;"></i></td>
                            @else
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/accounting/approvals/approve-referral-front/{{  $r->id }}"><button class="btn btn-success" type="button">Approve</button></a>
                                    <a href="/accounting/approvals/delete-referral-front/{{  $r->id }}"><button class="btn btn-danger" type="button">Delete</button></a>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="container">
        <div style="margin: 0 10px 0 10px;">
            <h2 class="page-heading" style="text-align:left;">Accountings</h2>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Transaction Type</th>
                            <th>Sub Transaction</th>
                            <th>Detail</th>
                            <th>Nominal (IDR)</th>
                            <th>PIC</th>
                            <th>Payment Method</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($income as $i)
                            <tr>
                                <td style="font-weight:bold;">Income</td>
                                <td>{{ date('l',strtotime($i->date)) }}, {{ date('d-m-Y',strtotime($i->date)) }}</td>
                                <td>{{ $i->transaction_type }}</td>
                                <td>{{ $i->sub_transaction }}</td>
                                <td>{{ $i->detail }}</td>
                                <td>{{ $i->nominal }}</td>
                                <td>{{ $i->name }}</td>
                                <td>{{ $i->payment_method }}</td>
                                <td>{{ $i->notes }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/accounting/approvals/approve-accounting/{{  $i->id }}"><button class="btn btn-success" type="button">Approve</button></a>
                                        <a href="/accounting/approvals/delete-accounting/{{  $i->id }}"><button class="btn btn-danger" type="button">Delete</button></a>
                                   </div>
                                </td>
                            </tr>
                        @endforeach
                        @foreach ($expense as $e)
                        <tr>
                            <td style="color: red; font-weight:bold;">Expense</td>
                            <td>{{ date('l',strtotime($e->date)) }}, {{ date('d-m-Y',strtotime($e->date)) }}</td>
                            <td>{{ $e->transaction_type }}</td>
                            <td>{{ $e->sub_transaction }}</td>
                            <td>{{ $e->detail }}</td>
                            <td>{{ $e->nominal }}</td>
                            <td>{{ $e->name }}</td>
                            <td>{{ $e->payment_method }}</td>
                            <td>{{ $e->notes }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/accounting/approvals/approve-accounting/{{  $e->id }}"><button class="btn btn-success" type="button">Approve</button></a>
                                    <a href="/accounting/approvals/delete-accounting/{{  $e->id }}"><button class="btn btn-danger" type="button">Delete</button></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
                <form id="edit-fee-form" method="POST" action="/accounting/approvals/fee-edit">
                    @csrf
                <input type="hidden" name="fee-id" value="" id="fee-id">
                <p style="margin-bottom: 0px; margin-top:10px;">Main Fee</p>
                <input class="form-control" type="text" name="main-fee" id="main-fee" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                <p style="margin-bottom: 0px; margin-top:10px;">Lunch Incentive</p>
                <input class="form-control" type="text" name="lunch-incentive" id="lunch-incentive" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                <p style="margin-bottom: 0px; margin-top:10px;">Transport Incentive</p>
                <input class="form-control" type="text" name="transport-incentive" id="transport-incentive" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
            </div>
        </div>
        <div class="modal-footer">
            <button id="schedule-edit-submit" type="submit" value="submit" class="btn btn-primary">Confirm</button>
        </form>
        </div>
        </div>
    </div>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
    <script src="{{ asset('js/teaching-info.js') }}"></script>
    <script>
        function editFee(id_fee){
        let mainmodal = document.getElementById("edit-fee");
        $('#edit-fee').modal('toggle');
        mainmodal.querySelector("#fee-id").value = id_fee;
        mainmodal.querySelector("#main-fee").value = document.getElementById(id_fee+"-main-fee").innerHTML.replace("K","000");
        mainmodal.querySelector("#lunch-incentive").value = document.getElementById(id_fee+"-lunch-fee").innerHTML.replace("K","000");
        mainmodal.querySelector("#transport-incentive").value = document.getElementById(id_fee+"-transport-fee").innerHTML.replace("K","000");

    }
    </script>
</body>
</html>
