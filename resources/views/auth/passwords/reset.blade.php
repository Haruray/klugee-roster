<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" href="{{ asset('img/2.png') }}">
    <title>Klugee Roster Management</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/font-awesome.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body class="login-body">
    <div class="pulse animated login-form-div">
        <div class="login-logo">
            <img src="{{ asset('img/2.png') }}" width="80px">
            <p class="login-logo-text">Password Reset</p>
        </div>
        <div class="login-form-div-actual">
            <div class="login-egg" style="margin-top: 20px;">
                <span id="egg">
                    <img style="left: 105px;" class="animated login-egg-actual" src="{{ asset('img/bird-key.png') }}" width="140px;">
                    {{-- <img style="left: 110px;" class="animated login-egg-actual" src="{{ asset('img/bird-key-success.png') }}" width="170px;"> --}}

                </span>
            </div>

            <div id="form-div" class="form-div">
                <form>
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="login-form">
                        <p class="form-label">{{ __('E-Mail Address') }}</p>

                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>

                    <div class="login-form">
                        <p class="form-label">{{ __('Password') }}</p>

                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>

                    <div class="login-form">
                        <p class="form-label">{{ __('Confirm Password') }}</p>

                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <div class="login-button">
                        <div class="login-button-actualbutton">
                            <p id="fail-message" class="fail-message"></p>
                            <button type="submit" class="btn btn-primary" type="submit"
                            onclick="ResetPassword(); return false;">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let ResetPassword = function(){
                var form = $('form')[0];
                var formdata = new FormData(form);
                $.ajax({
                    url : "/reset-password",
                    type : 'post',
                    dataType : 'JSON',
                    cache : false,
                    contentType : false,
                    processData : false,
                    data : formdata,
                    success : function(response){
                        if (response.success){
                            var info = response.data['user_type']+" "+response.data['name'];
                            var redirect ="<p style=\"text-align: center; color:white; font-size:18px; margin-top:30px;\">Password Sucessfully Changed!<br>"+
                            "Please wait, you are being redirected...</p>"+
                            "<p style=\"text-align: center; color:white; font-size:14px; margin-top:70px;\">Still not redirected? <a style=\"font-weight: bold; font-style:italic;\" href=\"/\">Click here</a></p>"
                            redirect = redirect.replace('<name>',info);
                            var crackedEgg = "<img style=\"left: 110px;\" class=\"bounce animated login-egg-actual\" src=\"{{ asset('img/bird-key-success.png') }}\" width=\"170px;\">";
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
                                text: 'Password Reset Error.',
                            });
                        }

                    },
                    error : function(response){
                        document.getElementById("fail-message").innerHTML=response.message;
                    }
                })
            }
    </script>
</body>

</html>
