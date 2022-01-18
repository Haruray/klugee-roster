<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{asset('css/Login-Form-Clean.css')}}">
        <link rel="stylesheet" href="{{asset('css/styles.css')}}">

        </style>
    </head>
    <body>
        <nav class="navbar navbar-light navbar-expand-md">
            <div class="container-fluid"><a class="navbar-brand" href="#">Brand</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse"
                    id="navcol-1">
                    <ul class="nav navbar-nav">
                        <li class="nav-item" role="presentation"><a class="nav-link active" href="#">First Item</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="#">Second Item</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="#">Third Item</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <h1 class="heading-main">Welcome!</h1>
        <div>
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-main-button">
                        <a href="#">
                            <div class="main-button">
                                <p class="main-button-icon"><i class="fa fa-book"></i></p>
                                <p class="main-button-text">Student Attendance</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-main-button">
                        <a href="#">
                            <div class="main-button">
                                <p class="main-button-icon"><i class="fa fa-book"></i></p>
                                <p class="main-button-text">Teacher Attendance</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-main-button">
                        <a href="#">
                            <div class="main-button">
                                <p class="main-button-icon"><i class="fa fa-book"></i></p>
                                <p class="main-button-text">Student's Profile</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-main-button">
                        <a href="#">
                            <div class="main-button">
                                <p class="main-button-icon"><i class="fa fa-book"></i></p>
                                <p class="main-button-text">Teacher's Profile</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
