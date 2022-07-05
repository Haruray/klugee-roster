<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" href="{{ asset('img/2.png') }}">
    <title>Password Reset</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
</head>

<body class="body-password">
    <div class="pass-reset-main">
        <div class="text-center pass-bird"><img src="assets/img/bird-key.png" width="200"></div>
        <h1 class="text-center" style="color: white;">Password Reset Request</h1>
        <div class="container text-center">
            <p class="text-center" style="font-size: 20px;"><strong>Hello!</strong><br>You are receiving this email because we received a password reset request for your account.<br><br></p><a class="btn btn-primary text-center d-inline-block" role="button" style="background-color: #fff5cc;color: #2dafff; margin:auto;"><strong>Reset Password</strong></a>
            <p
                style="font-size: 20px;"><br>This password reset link will expire in 60 minutes.<br>If you did not request a password reset, no further action is required.<br><br></p>
                <p><br>If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below<br>into your web browser: <a href="http://127.0.0.1:8000/password/reset/101c9a93eed09da399631307b48a4880b3e87126ed882ea964e8e0c02dc8a959?email=syafiqfaray2%40gmail.com">http://127.0.0.1:8000/password/reset/101c9a93eed09da399631307b48a4880b3e87126ed882ea964e8e0c02dc8a959?email=syafiqfaray2%40gmail.com</a><br><br></p>
        </div>
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{asset('js/bs-init.js')}}"></script>
</body>

</html>
