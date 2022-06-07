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
                            <th>Teaching Info</th>
                            <th>Fee</th>
                            <th>Lunch Incentive</th>
                            <th>Transport Incentive</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @for ($i = 0 ; $i < count($fee) ; $i+=$fee->where('date',$fee[$i]->date)->count())
                                <td rowspan="{{$fee->where('date',$fee[$i]->date)->count()}}">{{date('l',strtotime($fee[$i]->date))}}, {{date('d/m/Y',strtotime($fee[$i]->date))}}</td>
                                <td>{{ $fee[$i]->name }}</td>
                                <td>button</td>
                                <td>{{$fee[$i]->fee_nominal}}</td>
                                <td>{{$fee[$i]->lunch_nominal}}</td>
                                <td>{{$fee[$i]->transport_nominal}}</td>
                                <td>{{$fee[$i]->fee_nominal + $fee[$i]->lunch_nominal + $fee[$i]->transport_nominal}}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-success" type="button">Approve</button>
                                        <button class="btn btn-danger" type="button">Delete</button>
                                    </div>
                                </td>
                        </tr>
                                @for ($j = $i+1 ; $j < $fee->where('date',$fee[$i]->date)->count() ; $j++)
                                <tr>
                                    <td>button</td>
                                    <td>{{$fee[$j]->fee_nominal}}</td>
                                    <td>{{$fee[$j]->lunch_nominal}}</td>
                                    <td>{{$fee[$j]->transport_nominal}}</td>
                                    <td>{{$fee[$j]->fee_nominal + $fee[$j]->lunch_nominal + $fee[$j]->transport_nominal}}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-success" type="button">Approve</button>
                                            <button class="btn btn-danger" type="button">Delete</button>
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($income as $i)
                            <tr>
                                <td style="font-weight:bold;">Income</td>
                                <td>{{ $i->date }}</td>
                                <td>{{ $i->transaction_type }}</td>
                                <td>{{ $i->sub_transaction }}</td>
                                <td>{{ $i->detail }}</td>
                                <td>{{ $i->nominal }}</td>
                                <td>{{ $i->name }}</td>
                                <td>{{ $i->payment_method }}</td>
                                <td>{{ $i->notes }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-success" type="button">Approve</button>
                                        <button class="btn btn-danger" type="button">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @foreach ($expense as $e)
                        <tr>
                            <td style="color: red; font-weight:bold;">Expense</td>
                            <td>{{ $e->date }}</td>
                            <td>{{ $e->transaction_type }}</td>
                            <td>{{ $e->sub_transaction }}</td>
                            <td>{{ $e->detail }}</td>
                            <td>{{ $e->nominal }}</td>
                            <td>{{ $e->name }}</td>
                            <td>{{ $e->payment_method }}</td>
                            <td>{{ $e->notes }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-success" type="button">Approve</button>
                                    <button class="btn btn-danger" type="button">Delete</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
</body>
<script>
    function changeReport(){
        var month = document.getElementById("report-month").value;
        var year = document.getElementById("report-year").value;
        location.replace("/accounting/financial-data/recap/income/"+month+"/"+year);
    }
</script>

</html>