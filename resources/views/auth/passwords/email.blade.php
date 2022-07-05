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

            @if ($message = Session::get('sukses'))
            <div class="login-egg">
                <span id="egg"><img style="left: 105px;" class="bounce animated login-egg-actual" src="{{ asset('img/bird-key-question.png') }}" width="140px;"></span>
            </div>
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
            @else
            <div class="login-egg">
                <span id="egg"><img style="left: 105px;" class="login-egg-actual" src="{{ asset('img/bird-key-question.png') }}" width="140px;"></span>
            </div>
            @endif

            <div id="form-div" class="form-div">

            <form method="POST" action="/forget-password">
                @csrf

                    <div class="login-form">
                        <p class="form-label">Email</p>

                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                <div class="login-button">
                    <div class="login-button-actualbutton">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Send Password Reset Link') }}
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
</body>

</html>
