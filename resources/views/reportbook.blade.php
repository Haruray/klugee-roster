<!DOCTYPE HTML>
<html>

<head>
    <title>Report Book</title>
    <style>
        .halaman {
            width: 340mm;
            height: 473mm; /* Change to 299.2mm on firefox */
            background-color: #5cacec;
             /*border: 1px solid black; */
            /* margin-top: -8px;*/
            margin-left: -20px;
        }

        .halaman-no-bg{
            width: 340mm;
            height: 473mm; /* Change to 299.2mm on firefox */
             /*border: 1px solid black; */
            /* margin-top: -8px;*/
            margin-left: -20px;
        }

        .cover {
            font-family:'Open Sans',sans-serif;
            padding-top:30mm;
            padding-left:25mm;
        }

        #title {
            font-weight:700;
            font-size:6em;
            margin-right: 20mm;
        }

        #subtitle {
            font-weight:700;
            font-size:4em;
            margin-right: 20mm;
        }

        /* #page img {
            position: relative;
            top: 5mm;
            left: 10mm;
            width: 30mm;
            height: auto;
            z-index: 2;
        } */

        .page {
            font-family:'Open Sans',sans-serif;
            padding-top:40mm;
            padding-left:30mm;
            color: white;
        }

        #pagetitle {
            font-weight:700;
            font-size:4.5em;
            margin-right: 40mm;
            line-height:1.1em;
        }

        #pagesubtitle {
            font-weight:700;
            font-size:2.7em;
            margin-right: 40mm;
        }

        #pagebody {
            font-weight:500;
            font-size:1.7em;
            margin-right: 15mm;
        }

        #pagebody p {
            font-weight:450;
            font-size:0.95em;
        }

        #pagebody #final {
            font-weight:700;
            font-size:1.5em;
        }

        td[rowspan] {
            vertical-align: top;
            text-align: left;
        }

        #congrats {
            font-weight:700;
            font-size: 4.5em;
        }

        #congrats #name {
            font-size: 1.6em; /* I keep forgetting em inherits the above font size */
            margin-right: 10mm;
        }

        #congrats #seeyou {
            font-size: 0.8em;
            padding-top: 0.5em;
            /* line-height: 1.6em; */
        }

        .layer-logo {
            background-image: url('/img/white-logo-bg.png');
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow:hidden;
        }
        .text-right {
            text-align: right !important;
        }
        .text-left {
            text-align: left !important;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            overflow:hidden;
        }
        table {
            border-collapse: collapse;
        }
        .table thead th {
            vertical-align: bottom;
            border: none;
        }
        .table td, .table th {
            padding: .2rem;
            vertical-align: top;
        }
        *, ::after, ::before {
            box-sizing: border-box;
        }

    </style>
</head>
<body style="margin-left:-1px;">
<div class="halaman-no-bg">
    <img src="{{ public_path('img/cover-bird.png') }}" style="width:340mm; height:473mm; position: absolute; z-index: -1;">
    <div class="cover">
        <div id="title" style="color: white">
            Report Book
            </div>
        <div id="title" style="color: #fff952">
            {{ strtoupper($program) }} Level
            @for ($i = 0 ; $i < count($level) ; $i++)
                @if ($i != count($level)-1)
                    {{ $level[$i]->level }},
                @else
                    {{ $level[$i]->level }}
                @endif
            @endfor
        </div>
        <div id="subtitle" style="color: white; padding-top: 10mm">
            {{ $student->name }}
        </div>
    </div>
</div>

<div class="halaman-no-bg">
    <img src="{{  public_path('img/white-logo.png') }}" style="
                            position: relative;
                            width: 45mm;
                            height: auto;
                            float : right;
                            margin-top: 20px;
							margin-right: 50px;
                            ">
    <img src="{{ public_path('img/bird-thing-blue.png') }}" style="width:340mm; height:473mm; position: absolute; z-index: -1;">
    <div class="page">
        <div id="pagetitle" style="color: #fff952">
            Student's Bio
        </div>
        <div id="pagebody" style="color: white; padding-top:20mm">
            <table>
                <tr>
                    <td style="padding-right: 20mm;">Full name</td>
                    <td>:</td>
                    <td>{{ $student->name }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 20mm;">Nickname</td>
                    <td>:</td>
                    <td>{{ $student->nickname }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 20mm;">Program joined</td>
                    <td>:</td>
                    <td style="color:#fff952">{{ $program }}</td>
                </tr>
                <tr style="height: 1em">
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td style="padding-right: 20mm;">Place of Birth</td>
                    <td>:</td>
                    <td>{{ $student->birthplace }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 20mm;">Date of birth</td>
                    <td>:</td>
                    <td>{{ date('d',strtotime($student->birthdate))}} {{ date('F',strtotime($student->birthdate))}} {{ date('Y',strtotime($student->birthdate))}}</td>
                </tr>
                <tr style="height: 1em">
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td style="padding-right: 20mm;">School and Grade</td>
                    <td>:</td>
                    <td>{{ $student->school_name }}</td>
                </tr>
                <tr style="height: 1em">
                    <td colspan="3"></td>
                </tr>
                <tr style="font-weight: 600">
                    <td style="padding-right: 20mm;">Parent's name</td>
                    <td>:</td>
                    <td>{{ $student->parent_name }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 20mm;">Parent's contact</td>
                    <td>:</td>
                    <td>{{ $student->parent_contact }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 20mm;">Address</td>
                    <td>:</td>
                    <td>{{ $student->address }}</td> <!-- Idk how to solve text wrapping, mb -->
                </tr>
                </table>
            </table>
        </div>
    </div>
</div>

<div class="halaman">
    <img src="{{  public_path('img/white-logo.png') }}" style="
                                position: relative;
                                width: 45mm;
                                height: auto;
                                float : right;
                                margin-top: 20px;
    							margin-right: 50px;
                                ">
    <div class="page">
        <div id="pagetitle" style="color: #fff952">
            @if ($program=="Smartie")
            Math in English Progress
            @else
            {{ $program }} Progress
            @endif

        </div>
        <div id="pagebody" style="color: white; padding-top:10mm;">
            <p style="color: #fff952">
                {{ $desc_eng }}
            </p>
            <p>
                {{ $desc_ind }}
            </p>
            <div id="final" style="color: #fff952">
                @if ($program=="Smartie")
                Mathematics in English Progress Score: <br>
                {{ $score }}
                @else
                {{ $program }} Progress Score: <br>
                {{ $score }}
                @endif

            </div>
            <div class="table-responsive" style="padding-top: 10mm; font-weight:400">
                <table class="table">
                    <tr>
                        <td style="padding-right: 20mm;">Head Teacher</td>
                        <td>Teacher</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 20mm;">{{ $head_teacher->name }}</td>
                        <td>{{ $teacher }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@for ($k = 0 ; $k < count($data) ; $k+=$data->where('level',$data[$k]->level)->count() > 10? 10: $data->where('level',$data[$k]->level)->count())
<div class="halaman" style="background-color: #00c2cb;">
    <img src="{{  public_path('img/white-logo.png') }}" style="
                                position: relative;
                                width: 45mm;
                                height: auto;
                                float : right;
                                margin-top: 20px;
    							margin-right: 50px;
                                ">

    <div class="page">
        <div id="pagetitle" style="color: #fff952">
            Score
        </div>
        <div id="pagesubtitle">
            Level
            @if (count($level) == 2)
            @for ($i = 0 ; $i < count($level) ; $i++)
                @if ($i != count($level)-1)
                    {{ $level[$i]->level }} and
                @else
                    {{ $level[$i]->level }}
                @endif
            @endfor
            @elseif (count($level) > 2)
            @for ($i = 0 ; $i < count($level) ; $i++)
                @if ($i != count($level)-1)
                    {{ $level[$i]->level }},
                @else
                    and {{ $level[$i]->level }}
                @endif
            @endfor

            @else
            @for ($i = 0 ; $i < count($level) ; $i++)
                    {{ $level[$i]->level }}
            @endfor
            @endif
        </div>
        <div id="pagebody" style="padding-top:20mm">
            <div class="table-responsive progress-report-table" style="overflow-y:hidden;">
                <table id="progress-report-table" class="table" style="color: white; text-align:center;">
                    <thead>
                        <tr>
                            <th style="background-color: #38b6ff; padding:10px; width:15%; ">LEVEL</th>
                            <th style="background-color: #38b6ff; padding:10px; width:25%">UNIT</th>
                            <th style="background-color: #38b6ff; padding:10px;">LESSON</th>
                            <th style="background-color: #38b6ff; text-align:right; padding:10px; width:10%;">SCORE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = $k ; $i < ($data->where('level',$data[$k]->level)->count()+$k > 10 ? 10+$k : $data->where('level',$data[$k]->level)->count()+$k) ; $i+= $data->where('level',$data[$i]->level)->count())
                        <tr>
                            <td style="background-color: white; color:black; border:1px solid black; padding:10px; width:15%; text-align:center;" rowspan="{{ $data->where('level',$data[$i]->level)->count() }}">{{strtoupper($data[$i]->level ?: '')}}</td>
                            <td style="background-color: white; color:black; border:1px solid black; padding:10px; width:25%">{{strtoupper($data[$i]->unit ?: '')}}</td>
                            <td style="background-color: white; color:black; border:1px solid black; padding:10px;">{{strtoupper($data[$i]->last_exercise ?: '')}}</td>
                            <td style="background-color: white; color:black; text-align:right; border:1px solid black; padding:10px 15px 10px 10px; width:10%">{{strtoupper($data[$i]->score ?: '')}}</td>
                        </tr>
                        @for ($j = $i+1 ; $j < $i+$data->where('level',$data[$i]->level)->count() ; $j++)
                        <tr>
                            <td style="background-color: white; color:black; border:1px solid black; padding:10px; width:25%">{{strtoupper($data[$j]->unit ?: '')}}</td>
                            <td style="background-color: white; color:black; border:1px solid black; padding:10px;">{{strtoupper($data[$j]->last_exercise ?: '')}}</td>
                            <td style="background-color: white; color:black; text-align:right; border:1px solid black; padding:10px 15px 10px 10px; width:10%">{{strtoupper($data[$j]->score ?: '')}}</td>
                        </tr>
                        @endfor
                        @endfor
                    </tbody>
                </table>
        </div>
        </div>

    </div>
</div>
@endfor


<div class="halaman-no-bg">
    <!-- <div class="layer-logo"></div> -->
    <img src="{{ public_path('img/final_bg.png') }}" style="width:340mm; height:473mm; position: absolute; z-index: -1;">
    <div class="page">
        <div id="congrats">
            Congratulations
            <div id="name" style="color:#fff952">
                {{ $student->name }}
            </div>
            <div id="seeyou">
                See you again in
                <div style="color:#fff952">
                    {{ $farewell }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="halaman" style="page-break-after:avoid; margin-bottom:-10px;">
    <div class="page">
        <div id="pagebody" style="text-align: center; padding-top: 140mm">
            <img src="{{  public_path('img/white-logo.png') }}" style="
                                            position: relative;
                                            width: 50mm;
                                            height: auto;
                                            ">
             <br/> <br/>
            <span style="font-weight: bolder">Jl. Kalpataru 24 Malang <br/>
                www.klugee.wixsite.com/website</span>
        </div>
    </div>
</div>
</body>

</html>
