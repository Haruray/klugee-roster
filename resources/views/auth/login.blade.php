<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>klugee-roster</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body class="login-body">
    <div class="pulse animated login-form-div">
        <div class="login-logo"><img src="{{ asset('img/2.png') }}" width="80px">
            <p class="login-logo-text">Welcome to <br>Roster Management!</p>
        </div>
        <div class="login-form-div-actual">
            <div class="login-egg"><img class="login-egg-jerami" src="{{ asset('img/jerami.png') }}" width="180">
            <span id="egg"><img class="shake animated login-egg-actual" src="{{ asset('img/icon_grade_1.png') }}" width="120px;"></span></div>
            <div id="form-div" class="form-div">
                <form>
                    @csrf
                    <div class="login-form">
                        <p class="form-label">Email</p>
                        <input class="form-control" id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="example@email.com"></div>
                    <div class="login-password login-form">
                        <p class="form-label">Password</p>
                        <input class="form-control form-password" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password here">
                        <i id="eye" onclick="SeePassword()" class="fa fa-eye login-password-eye"></i>
                        <a class="password-forgot" href="{{ route('password.request') }}">Forgot your password?</a>
                    </div>
                    <div class="login-button">
                        <div class="login-button-actualbutton">
                            <p id="fail-message" class="fail-message"></p>
                            <button class="btn btn-primary login-button-actualbutton-actual" type="submit"
                            onclick="LoginButton(); return false;">Login</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
    <script>

        let LoginButton = function(){
                var form = $('form')[0];
                var formdata = new FormData(form);
                $.ajax({
                    url : "{{ route('login.check_login') }}",
                    type : 'post',
                    dataType : 'JSON',
                    cache : false,
                    contentType : false,
                    processData : false,
                    data : formdata,
                    success : function(response){
                        if (response.success){
                            var info = response.data['user_type']+" "+response.data['name'];
                            var redirect ="<p style=\"text-align: center; color:white; font-size:18px; margin-top:30px;\">Successfully logged in as <strong><span style=\"text-transform: capitalize;\"> <name> </span></strong><br>"+
                            "Please wait, you are being redirected...</p>"+
                            "<p style=\"text-align: center; color:white; font-size:14px; margin-top:70px;\">Still not redirected? <a style=\"font-weight: bold; font-style:italic;\" href=\"/\">Click here</a></p>"
                            redirect = redirect.replace('<name>',info);
                            var crackedEgg = "<img style=\"left: 105px;bottom: 0px;\" class=\"bounce animated login-egg-actual\" src=\"{{ asset('img/icon_grade_3.png') }}\" width=\"150px;\">";
                            //egg change
                            var targetElem = document.querySelector('#egg');
                            targetElem.innerHTML=crackedEgg;
                            //info change
                            var targetElem = document.querySelector('#form-div');
                            targetElem.innerHTML=redirect;
                            const myTimeout = setTimeout(function(){
                                window.location.href = "/";
                            }, 3000);
                        }
                        else{
                            Swal.fire({
                                icon : 'error',
                                title: 'Oops...',
                                text: 'Login Error.',
                            }).then(function(){
                                document.getElementById("fail-message").innerHTML="Wrong email or password";
                                var Egg = "<img class=\"shake animated login-egg-actual\" src=\"{{ asset('img/icon_grade_1.png') }}\" width=\"120px;\">";
                                var targetElem = document.querySelector('#egg');
                                targetElem.innerHTML=Egg;
                            });
                        }

                    },
                    error : function(response){
                        document.getElementById("fail-message").innerHTML="Wrong email or password";
                        var Egg = "<img class=\"shake animated login-egg-actual\" src=\"{{ asset('img/icon_grade_1.png') }}\" width=\"120px;\">";
                        var targetElem = document.querySelector('#egg');
                        targetElem.innerHTML=Egg;                    }
                })
            }
    let SeePassword = function(){
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
            document.getElementById("eye").className = "fa fa-eye-slash login-password-eye";
        } else {
            x.type = "password";
            document.getElementById("eye").className = "fa fa-eye login-password-eye";

        }
    }
    </script>
</body>

</html>
