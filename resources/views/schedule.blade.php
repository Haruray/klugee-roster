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
    <h1 class="bounce animated page-heading" style="margin-top:20px;">Schedule</h1>

    <div class="container">
        @for ($i = 0 ; $i < count($schedule) ; $i+= $schedule->where('day',$schedule[$i]->day)->count())
        <h2 class="bold blue">{{$schedule[$i]->day}}</h2>
        <div class="table-responsive progress-report-table">
            <table id="progress-report-table" class="table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Class Type</th>
                        <th>Program</th>
                        <th>Subject</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($k = $i ; $k < $schedule->where('day',$schedule[$i]->day)->count()+$i ; $k+= $schedule->where('id',$schedule[$i]->id)->count())
                        <tr>
                            <td>{{$schedule[$k]->name}}</td>
                            <td rowspan="{{$schedule->where('id',$schedule[$k]->id)->count()}}">{{$schedule[$k]->begin}}</td>
                            <td rowspan="{{$schedule->where('id',$schedule[$k]->id)->count()}}">{{$schedule[$k]->classroom_type}}</td>
                            <td rowspan="{{$schedule->where('id',$schedule[$k]->id)->count()}}">{{$schedule[$k]->classroom_students}}</td>
                            <td rowspan="{{$schedule->where('id',$schedule[$k]->id)->count()}}">{{$schedule[$k]->program}}</td>
                            <td rowspan="{{$schedule->where('id',$schedule[$k]->id)->count()}}">{{$schedule[$k]->subject}}</td>
                            <td rowspan="{{$schedule->where('id',$schedule[$k]->id)->count()}}"><a href="/attendance/input"><button class="btn btn-primary" type="button">Attendance Input</button></a></td>
                        </tr>
                        @for ($j = $k+1 ; $j < $schedule->where('id',$schedule[$k]->id)->count()+$k ; $j++)
                            <tr>
                                <td>{{$schedule[$j]->name}}</td>
                            </tr>
                        @endfor
                    @endfor
                </tbody>
            </table>
        </div>
        @endfor

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


    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
</body>

</html>
