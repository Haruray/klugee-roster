<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Password Reset</title>
    <style>
        .body-password {
        background-color: #2dafff;
        }

        .pass-bird {
        margin-top: 20px;
        }

        .pass-reset-main {
        background-color: #53dee4;
        width: 700px;
        text-align: center;
        margin: auto;
        padding: 20px;
        margin-top: 50px;
        border-radius: 10px;
        box-shadow: 5px 5px #d9d9d9;
        }

        .container, .container-md, .container-sm {
	max-width: 720px;
}
.container {
	width: 100%;
	padding-right: 15px;
	padding-left: 15px;
	margin-right: auto;
	margin-left: auto;
}

body {
	font-family: "roboto";
}
.text-right {
	text-align: right !important;
}
.text-left {
	text-align: left !important;
}
.btn {
	display: inline-block;
	font-weight: 400;
	text-align: center;
	white-space: nowrap;
	vertical-align: middle;
	user-select: none;
	border: 1px solid transparent;
	padding: .375rem .75rem;
	font-size: 1rem;
	line-height: 1.5;
	border-radius: .25rem;
}
    </style>
</head>

<body class="body-password">
    <div class="pass-reset-main">
        <div class="text-center pass-bird"><img src="https://media.discordapp.net/attachments/943436922615889920/993849007807934524/bird-key.png?width=670&height=670" width="200"></div>
        <h1 class="text-center" style="color: white;">Password Reset Request</h1>
        <div class="container text-center">
            <p class="text-center" style="font-size: 20px;"><strong>Hello!</strong><br>You are receiving this email because we received a password reset request for your account.<br><br></p>
            <a href="{{URL::to('/reset-password/'.$token)  }}" class="btn btn-primary text-center d-inline-block" role="button" style="background-color: #fff5cc;color: #2dafff; margin:auto;"><strong>Reset Password</strong></a>
            <p
                style="font-size: 20px;"><br>This password reset link will expire in 60 minutes.<br>If you did not request a password reset, no further action is required.<br><br></p>
                <p><br>If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below<br>into your web browser: <a href="{{URL::to('/reset-password/'.$token)  }}">{{URL::to('/reset-password/'.$token)  }}</a><br><br></p>
        </div>
    </div>
</body>

</html>

