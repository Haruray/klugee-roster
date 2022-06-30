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
    <link rel="stylesheet" href="{{asset('css/Login-Form-Clean.css')}}">
    <link rel="stylesheet" href="{{asset('css/Navigation-Clean.css')}}">
    <link rel="stylesheet" href="{{asset('css/Navigation-with-Button.css')}}">
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">

    <link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
	<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
	<script src="https://unpkg.com/dropzone"></script>
	<script src="https://unpkg.com/cropperjs"></script>

    <style>
        .image_area:hover {
		  height: 50%;
		  cursor: pointer;
		}

		.text {
		  color: #333;
		  font-size: 20px;
		  position: absolute;
		  top: 50%;
		  left: 50%;
		  -webkit-transform: translate(-50%, -50%);
		  -ms-transform: translate(-50%, -50%);
		  transform: translate(-50%, -50%);
		  text-align: center;
		}
        .preview {
  			overflow: hidden;
  			width: 160px;
  			height: 160px;
  			margin: 10px;
  			border: 1px solid red;
		}

		.modal-lg{
  			max-width: 1000px !important;
		}

        #modal{
            overflow : auto;
        }


    </style>
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
    <div class="text-center">
        <div class="container teacher-profile-img-div">
            <div class="row">
                <div class="col-sm-12 col-md-4 col-xl-3 offset-lg-0 text-center">
                    <div class="text-left d-inline-block teacher-profile-img-group">
                        <div class="text-center teacher-profile-img-outline"><img id="profile-pic" class="student-profile-img" src="{{url('/uploads/profile-pictures/'.$profile->photo)}}">
                            <a data-toggle="modal" data-target="#upload-modal"><i class="fa fa-camera teacher-profile-camera" data-bs-hover-animate="pulse"></i></a>
                         </div>
                         @if ($user_id != auth()->user()->id_teacher)
                         <a href="/earnings/{{ $user_id }}/{{ date('m') }}/{{ date('Y') }}">
                            @else
                        <a href="/earnings/{{ date('m') }}/{{ date('Y') }}">
                        @endif
                        <div class="teacher-fee">
                                <p>Fee up to day&nbsp;</p>
                                <p class="bold teacher-fee-nominal">Rp{{$fees ?: '0'}}</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-8 col-xl-8 text-center">
                    <div class="d-inline-block">
                        <p class="teacher-profile-name bold yellow">{{$profile->name}}</p>
                        <p class="bold white" style="font-size: 20px;">Joined since</p>
                        <p class="bold teacher-join-time yellow">{{date('F',strtotime($profile->join_date))}} {{date('Y',strtotime($profile->join_date))}}</p>
                        <div class="teacher-status">
                            <p class="d-inline-block white bold teacher-status-individual"><i class="fa fa-check-circle" style="color: #38b6ff;font-size: 35px;"></i>&nbsp;
                            @if ($profile->status)
                                Active
                            @else
                                Inactive
                            @endif
                            </p>
                            @foreach ($position as $ps)
                            <p class="d-inline-block white bold teacher-status-individual"><i class="fa fa-check-circle" style="color: #38b6ff;font-size: 35px;"></i>&nbsp;{{$ps->position}}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="container">

        <h1 style="margin-top:30px;" class="bounce animated page-heading">Earnings</h1>
        <div style="margin: 0 0 15px 0px;">
            <select id="earning-month" class="form-control-lg select-box-single" onchange="changeEarnings()">
                @if ($month == 1)
                <option value="01" selected>January</option>
                @else
                <option value="01">January</option>
                @endif
                @if ($month == 2)
                <option value="02" selected>February</option>
                @else
                <option value="02">February</option>
                @endif
                @if ($month == 3)
                <option value="03" selected>March</option>
                @else
                <option value="03">March</option>
                @endif
                @if ($month == 4)
                <option value="04" selected>April</option>
                @else
                <option value="04">April</option>
                @endif
                @if ($month == 5)
                <option value="05" selected>May</option>
                @else
                <option value="05">May</option>
                @endif
                @if ($month == 6)
                <option value="06" selected>June</option>
                @else
                <option value="06">June</option>
                @endif
                @if ($month == 7)
                <option value="07" selected>July</option>
                @else
                <option value="07">July</option>
                @endif
                @if ($month == 8)
                <option value="08" selected>August</option>
                @else
                <option value="08">August</option>
                @endif
                @if ($month == 9)
                <option value="09" selected>September</option>
                @else
                <option value="09">September</option>
                @endif
                @if ($month == 10)
                <option value="10" selected>October</option>
                @else
                <option value="10">October</option>
                @endif
                @if ($month == 11)
                <option value="11" selected>November</option>
                @else
                <option value="11">November</option>
                @endif
                @if ($month == 12)
                <option value="12" selected>December</option>
                @else
                <option value="12">December</option>
                @endif

            </select>
            <select
                id="earning-year" class="form-control-lg select-box-single" required="" onchange="changeEarnings()">
                @foreach ($years as $y)
                    @if ($y->year == $year)
                        <option value="{{ $y->year }}" selected>{{ $y->year }}</option>
                    @else
                        <option value="{{ $y->year }}">{{ $y->year }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <h4 class="bold green">{{ date('F', mktime(0, 0, 0, $month, 10)) }}</h3>
        <h2 class="bold blue">FEES</h2>
        <table style="margin-top:20px; margin-bottom:30px;" id="progress-report-table" class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Teaching Info</th>
                        <th>Fee</th>
                        <th>Lunch Incentive</th>
                        <th>Transport Incentive</th>
                        <th>Total</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @for ($i = 0 ; $i < count($fee) ; $i+=$fee->where('date',$fee[$i]->date)->count())
                            <td rowspan="{{$fee->where('date',$fee[$i]->date)->count()}}">{{date('l',strtotime($fee[$i]->date))}}, {{date('d/m/Y',strtotime($fee[$i]->date))}}</td>
                            <td>button</td>
                            <td>{{$fee[$i]->fee_nominal}}</td>
                            <td>{{$fee[$i]->lunch_nominal}}</td>
                            <td>{{$fee[$i]->transport_nominal}}</td>
                            <td>{{$fee[$i]->fee_nominal + $fee[$i]->lunch_nominal + $fee[$i]->transport_nominal}}</td>
                            @if ($fee[$i]->approved)
                            <td><i class="fa fa-check-circle" style="font-size: 40px;color: #6ce679;"></i></td>
                            @else
                            <td><i class="fa fa-exclamation-circle" style="color: red;font-size: 40px;"></i></td>
                            @endif
                    </tr>
                            @for ($j = $i+1 ; $j < $fee->where('date',$fee[$i]->date)->count() ; $j++)
                            <tr>
                                <td>button</td>
                                <td>{{$fee[$j]->fee_nominal}}</td>
                                <td>{{$fee[$j]->lunch_nominal}}</td>
                                <td>{{$fee[$j]->transport_nominal}}</td>
                                <td>{{$fee[$j]->fee_nominal + $fee[$j]->lunch_nominal + $fee[$j]->transport_nominal}}</td>
                                @if ($fee[$j]->approved)
                                <td><i class="fa fa-check-circle" style="font-size: 40px;color: #6ce679;"></i></td>
                                @else
                                <td><i class="fa fa-exclamation-circle" style="color: red;font-size: 40px;"></i></td>
                                @endif
                            </tr>
                            @endfor
                        @endfor

                </tbody>
                <tfoot style="background-color:#fff5cc; text-align:right;">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Total : Rp{{$fee->sum('fee_nominal') + $fee->sum('lunch_nominal') + $fee->sum('transport_nominal')}}</th>
                </tfoot>
            </table>

        <h4 class="bold green">{{ date('F', mktime(0, 0, 0, $month, 10)) }}</h3>
        <h2 class="bold blue">SALARY</h2>
        <table style="margin-top:20px; margin-bottom:30px;" id="progress-report-table" class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Nominal</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>

                        @foreach ($salary as $s)
                        <tr>
                            <td>{{date('l',strtotime($s->date))}}, {{date('d/m/Y',strtotime($s->date))}}</td>
                            <td>{{$s->note}}</td>
                            <td>{{$s->nominal}}</td>
                            @if ($s->approved)
                            <td><i class="fa fa-check-circle" style="font-size: 40px;color: #6ce679;"></i></td>
                            @else
                            <td><i class="fa fa-exclamation-circle" style="color: red;font-size: 40px;"></i></td>
                            @endif
                            </tr>
                        @endforeach

                </tbody>
                <tfoot style="background-color:#fff5cc; text-align:right;">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Total : Rp{{$salary->sum('nominal') }}</th>
                </tfoot>
            </table>

            <h4 class="bold green">{{ date('F', mktime(0, 0, 0, $month, 10)) }}</h3>
            <h2 class="bold blue">INCENTIVES (outside of lunch and transport)</h2>
            <table style="margin-top:20px; margin-bottom:30px;" id="progress-report-table" class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Incentive Name</th>
                        <th>Nominal</th>
                        <th>Note</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($incentive as $i)
                    <tr>
                            <td>{{date('l',strtotime($i->date))}}, {{date('d/m/Y',strtotime($i->date))}}</td>
                            <td>{{ $i->name }}</td>
                            <td>{{$i->nominal}}</td>
                            <td>{{$i->note}}</td>
                            @if ($i->approved)
                            <td><i class="fa fa-check-circle" style="font-size: 40px;color: #6ce679;"></i></td>
                            @else
                            <td><i class="fa fa-exclamation-circle" style="color: red;font-size: 40px;"></i></td>
                            @endif
                            </tr>
                        @endforeach

                </tbody>
                <tfoot style="background-color:#fff5cc; text-align:right;">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Total : Rp{{$incentive->sum('nominal')}}</th>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="upload-modal" tabindex="-1" role="dialog" aria-labelledby="upload-modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Upload New Profile Picture</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
            <input type="file" name="image" id="upload_image">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!--another modal for cropper -->

        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
			  	<div class="modal-dialog modal-lg" role="document">
			    	<div class="modal-content">
			      		<div class="modal-header">
			        		<h5 class="modal-title">Crop Image Before Upload</h5>
			        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          			<span aria-hidden="true">×</span>
			        		</button>
			      		</div>
			      		<div class="modal-body">
			        		<div class="img-container">
			            		<div class="row">
			                		<div class="col-md-8">
			                    		<img style="width:inherit;" src="" id="sample_image" />
			                		</div>
			                		<div class="col-md-4">
			                    		<div class="preview"></div>
			                		</div>
			            		</div>
			        		</div>
			      		</div>
                          <div class="modal-footer">
			      			<button type="button" id="crop" class="btn btn-primary">Crop</button>
                            <button id="user_id" style="display:none;" name="user_id" value="{{$user_id}}">
                            @if ($user_id != auth()->user()->teacher_id)
                            <button id="view_type" style="display:none;" name="view_type" value="admin">
                            @else
                            <button id="view_type" style="display:none;" name="view_type" value="normal">
                            @endif
			        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			      		</div>
			    	</div>
			  	</div>
			</div>


</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('js/bs-init.js')}}"></script>

<script>

$(document).ready(function(){

	var $modal = $('#modal');

    var another_modal = document.getElementById('upload-modal');

	var image = document.getElementById('sample_image');

    var user_id = document.getElementById("user_id").value;

    var view_type = document.getElementById("view_type").value;

	var cropper;

	$('#upload_image').change(function(event){
		var files = event.target.files;

		var done = function(url){
			image.src = url;
			$modal.modal('show');
            $('#upload-modal').modal('toggle');

		};

		if(files && files.length > 0)
		{
			reader = new FileReader();
			reader.onload = function(event)
			{
				done(reader.result);
			};
			reader.readAsDataURL(files[0]);
		}
	});

	$modal.on('shown.bs.modal', function() {
		cropper = new Cropper(image, {
			aspectRatio: 1,
			viewMode: 3,
			preview:'.preview'
		});
	}).on('hidden.bs.modal', function(){
		cropper.destroy();
   		cropper = null;
	});

	$('#crop').click(function(){
		canvas = cropper.getCroppedCanvas({
			width:400,
			height:400
		});

		canvas.toBlob(function(blob){
			url = URL.createObjectURL(blob);
			var reader = new FileReader();
			reader.readAsDataURL(blob);
			reader.onloadend = function(){
				var base64data = reader.result;
                if (view_type == "normal"){

                    $.ajax({
					url:'/profile/upload',
					method:'POST',
					data:{"image":base64data,
                        "_token" : $("meta[name='csrf-token']").attr("content")},
					success:function(response)
					{
						$modal.modal('hide');
						$('#profile-pic').attr('src', response.data);
                        window.location.reload();
					}
				});

                }
                else{
                    $.ajax({
					url:'/profile/select/upload',
					method:'POST',
					data:{"image":base64data,
                        "user_id" : user_id,
                        "_token" : $("meta[name='csrf-token']").attr("content")},
					success:function(response)
					{
						$modal.modal('hide');
						$('#profile-pic').attr('src', response.data);
                        window.location.reload();
					}
				});
                }

			};
		});
	});

});
</script>
<script>
    function changeEarnings(){
        var month = document.getElementById("earning-month").value;
        var year = document.getElementById("earning-year").value;
        if (!year){year = 0;}
        var user_id_auth = {!! json_encode(auth()->user()->id_teacher) !!}
        var user_id = {!! json_encode($user_id) !!}
        if (user_id_auth != user_id){
            location.replace("/earnings/"+user_id+'/'+month+"/"+year);
        }
        else{
            location.replace("/earnings/"+month+"/"+year);
        }
    }
</script>

</html>
