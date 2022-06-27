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
    <h1 class="bounce animated page-heading">New Student Registration</h1>
    <div id="box" class="attendance-box">
        <h2 class="page-sub-heading"><i class="fa fa-pencil-square"></i></h2>
        <form name="studentadd" id="studentadd" method="POST" action="/student/add/process" onsubmit="return validateForm();">
            @csrf
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-id-badge"></i></div>
                            <input name="official-id" class="form-control attendance-input" type="text" placeholder="Official ID" required=""></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-user"></i></div><input name="name" class="form-control attendance-input" type="text" placeholder="Name" required=""></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-user-o"></i></div><input name="nickname" class="form-control attendance-input" type="text" placeholder="Nickname" required=""></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-map"></i></div><input name="address" class="form-control attendance-input" type="text" placeholder="Address" required=""></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-map-marker"></i></div>
                            <input style="margin-left: 16px;" name="birthplace" class="form-control attendance-input" type="text" placeholder="Birth Place" required=""></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <p style="color: white; font-weight:bold; margin:1px 0px 5px 40px;">Birthdate</p>
                            <div class="attendance-icon align-middle"><i class="fa fa-calendar"></i></div>
                            <input style="margin-left: 16px;" name="date" class="form-control attendance-input" type="date" required="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-university"></i></div>
                            <input style="margin-left: 16px;" name="school" class="form-control attendance-input" type="text" placeholder="School Name" required=""></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-user-circle-o"></i></div>
                            <input style="margin-left: 16px;" name="parent" class="form-control attendance-input" type="text" placeholder="Parent (Mom or Dad)" required=""></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-user-circle-o"></i></div>
                            <input style="margin-left: 16px;" name="parent-name" class="form-control attendance-input" type="text" placeholder="Parent Name" required=""></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-mobile"></i></div>
                            <input style="margin-left: 16px;" name="telp" class="form-control attendance-input" type="text" placeholder="Parent Contact" required="" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-envelope"></i></div><input id="email" name="email" class="form-control attendance-input" type="text" placeholder="Email" required=""></div>
                    </div>
                </div>
            </div>
            <div id="program-container" class="container" style="padding-top:10px; padding-bottom:10px;">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div" style="text-align: center;">
                            <p style="color:white; font-weight:bold; font-size:20px; text-align:center;">Applied Program</p>
                            @foreach ($program as $p)
                            <div id="student-program-checkbox" class="form-check" style="display: inline-block; margin-right:5px; color:white; ">
                                <div style="text-align:center; height:150px; width:150px; border-radius:10px; margin-bottom:15px;">
                                    <img style="display: block; margin:auto; border:1px solid white; border-radius:50%;" src="{{ asset('img/'.$p->program.'-logo.png') }}" alt="" width="100">
                                    <input style="display: inline-block; text-align:center; margin-left:-7px; margin-top:5px;" class="form-check-input" name="student-program[]" type="checkbox" value="{{ $p->program }}" id="{{ explode(" ",$p->program)[0] }}" onclick="ProgramClicked({{ explode(' ',$p->program)[0] }})">
                                    <br>
                                    <label class="form-check-label" for="{{ explode(" ",$p->program)[0] }}">
                                      {{ $p->program }}
                                    </label>
                                </div>
                              </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-dollar"></i></div>
                            <input style="margin-left: 16px;" name="registration-nominal" class="form-control attendance-input" type="text" placeholder="Registration Amount (IDR)" required="" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-exclamation"></i></div>
                            <input style="margin-left: 16px;" name="note" class="form-control attendance-input" type="text" placeholder="Registration Note"></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="form-row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="attendance-input-div">
                            <div class="attendance-icon align-middle"><i class="fa fa-user"></i></div>
                            <select style="margin-left: 17px;" name="pic" class="form-control attendance-input">
                                <optgroup label="PIC">
                                    <option value="" disabled selected>PIC</option>
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
            <input type="hidden" name="referral-bool" id="referral-bool" value="0">
            <div id="referral" class="container">
                <button class="btn btn-primary" onclick="AddReferral()">Add Referral</button>
            </div>

            <div class="attendance-input-button-box"><button class="btn btn-primary attendance-input-button" type="submit" value="submit" name="submit">Submit</button></div>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>

</body>
<script>

    function validateForm() {
        let x = document.forms["studentadd"];
        let mail = document.getElementById('email').value;
        if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))){
                Swal.fire({
                    icon : 'error',
                    title: 'Oops...',
                    text: 'Invalid Email Address.'
                });
                return false;
        }

        if ($('div#student-program-checkbox :checkbox:checked').length <= 0){
            Swal.fire({
                icon : 'error',
                title: 'Oops...',
                text: 'Teaching Program checkbox must be filled out.'
            });
            return false;
        }
    }

    function ProgramClicked(program_text){
        if (program_text.checked){
            NominalHTML = "<div id=\""+program_text.id+"-nominal\">"+
                "<div class=\"form-row\">"+
                    "<div class=\"col-md-12 col-lg-12 col-xl-12\">"+
                        "<div class=\"attendance-input-div\">"+
                            "<div class=\"attendance-icon align-middle\"><i class=\"fa fa-dollar\"></i></div>"+
                            "<input name=\""+program_text.id+"-nominal\" class=\"form-control attendance-input\" "+
                            "type=\"text\" placeholder=\""+program_text.value+" Nominal (IDR)\" required=\"\" "+
                            "inputmode=\"numeric\" "+
                            "oninput=\"this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\\..*)\\./g, '$1');\"></div>"+
                    "</div></div></div>"
            JatahHTML = "<div id=\""+program_text.id+"-jatah\">"+
                "<div class=\"form-row\">"+
                    "<div class=\"col-md-12 col-lg-12 col-xl-12\">"+
                        "<div class=\"attendance-input-div\">"+
                            "<div class=\"attendance-icon align-middle\"><i class=\"fa fa-check-circle\"></i></div>"+
                            "<input name=\""+program_text.id+"-jatah\" class=\"form-control attendance-input\" "+
                            "type=\"text\" placeholder=\" Jatah Pertemuan "+program_text.value+"\" required=\"\" "+
                            "inputmode=\"numeric\" "+
                            "oninput=\"this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\\..*)\\./g, '$1');\"></div>"+
                    "</div></div></div>"

            $('#program-container').append(NominalHTML);
            $('#program-container').append(JatahHTML);
        }

        else{
            let targetElem1 = document.querySelector('#'+program_text.id+'-nominal');
            let targetElem2 = document.querySelector('#'+program_text.id+'-jatah');
            targetElem1.remove();
            targetElem2.remove();
        }

    }

    function AddReferral(){
        let ReferrerHTML="<div class=\"\">"+
                    "<div class=\"form-row\">"+
                        "<div class=\"col-md-12 col-lg-12 col-xl-12\">"+
                            "<div class=\"attendance-input-div\">"+
                            "<div class=\"attendance-icon align-middle\"><i class=\"fa fa-check-circle\"></i></div>"+
                            "<select style=\"margin-left:20px;\" name=\"referrer\" class=\"form-control attendance-input\" required>"+
                                "<optgroup label=\"Referral Parent Name\">"+
                                    "<option value=\"\" disabled selected>Referral Parent Name</option>";
        let ReffererClosingHTML = "</optgroup></select></div></div></div></div>";
        let ReferralNote = "<div class=\"\">"+
                    "<div class=\"form-row\">"+
                        "<div class=\"col-md-12 col-lg-12 col-xl-12\">"+
                            "<div class=\"attendance-input-div\">"+
                            "<div class=\"attendance-icon align-middle\"><i class=\"fa fa-exclamation\"></i></div>"+
                            "<input style=\"margin-left:20px;\" name=\"referral-note\" class=\"form-control attendance-input\" type=\"text\" placeholder=\"Referral Note\"></div>"+
                    "</div></div></div>"
        let DeleteReferral = "<button class=\"btn btn-primary\" onclick=\"DeleteReferral()\">Delete Referral</button>";

        let loadingHTML="<div class=\"loader\"></div>"
        let targetElem = document.querySelector('#referral');
        targetElem.innerHTML = loadingHTML;
        $.ajax({
            url:'/get/parent-partner',
            type : 'get',
            dataType : 'json',
            success : function(response){
                if (response.success){
                    for (var i = 0 ; i < response.parent.length ; i++){
                        var OptionHTML = "<option value=\""+response.parent[i].parent_student_id+"\">"+response.parent[i].referrer_name+"</option>";
                        ReferrerHTML+=OptionHTML;
                    }
                    ReferrerHTML+=ReffererClosingHTML;
                    targetElem.innerHTML = ReferrerHTML;
                    $('#referral').append(ReferralNote);
                    $('#referral').append(DeleteReferral);
                    document.getElementById("referral-bool").value="1";
                }
                else{
                    //alert
                }
            }
        })
    }

    function DeleteReferral(){
        let AddReferral = "<button class=\"btn btn-primary\" onclick=\"AddReferral()\">Add Referral</button>";
        let targetElem = document.querySelector('#referral');
        document.getElementById("referral-bool").value="0";
        targetElem.innerHTML = AddReferral;
    }
</script>
</html>
