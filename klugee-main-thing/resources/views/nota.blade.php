<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>klugee-roster</title>
    <style>
        .nota-header {
    background-color: #38b6ff;
  }

  .nota-body {
    width: 276mm;
  }

  #nota-table-head {
    background-color: #38b6ff;
  }

  .nota-thead {
    background-color: #38b6ff;
    border: none;
  }

  .nota-tr {
    border: none;
  }

  .table thead th {
    vertical-align: bottom;
    border: none;
  }

  .nota-td {
    border: none;
  }

  .nota-payment-detail {
    font-size: 20px;
    margin-bottom: -3px;
  }

  .nota-payment {
    font-weight: bold;
    font-size: 23px;
    color: #3079c8;
    margin-bottom: -3px;
  }

  .nota-desc {
    font-weight: bold;
    font-size: 20px;
    float: left;
  }

  .nota-tanggal {
    font-weight: bold;
    font-size: 20px;
    color: #3079c8;
    float: right;
    margin-left: 200px;
  }

  .nota-ttd {
    font-weight: bold;
    font-size: 25px;
  }

  .nota-ttd-name {
    font-size: 23px;
    float: right;
  }

  .nota-ttd-box {
    margin: 50px 10px 20px 10px;
  }

  .nota-details {
    margin: 20px 10px 20px 10px;
  }

  .table-external-header {
    background-color: #38b6ff;
    height: 50px;
  }

  .nota-table-head-desc {
    color: white;
    font-weight: bold;
    margin: 10px 10px 10px 20px;
  }

  .nota-table-head-total {
    color: white;
    font-weight: bold;
    margin: 10px 100px 10px 0px;
  }

  .nota-grand-total {
    border-top: 5px solid grey;
    font-size: 18PX;
  }

  .nota-table {
    font-weight: bold;
  }

  .ttd-cap {
    width: 150px;
    text-align: right;
    z-index: -5;
    position: absolute;
    right: 100px;
    opacity: 0.25;
  }

  .nota-lunas {
    color: #dba0a0;
    font-weight: bolder;
    font-size: 45px;
    margin-right: 50px;
    margin-bottom: -10px;
    float: right;
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
}body {
	font-size: 1rem;
	font-weight: 400;
	line-height: 1.5;
	color: #212529;
	text-align: left;
}
.row {
	display: flex;
	flex-wrap: wrap;
	margin-right: -15px;
	margin-left: -15px;
}
.col-md-6 {
	flex: 0 0 50%;
	max-width: 50%;
}
.col, .col-1, .col-10, .col-11, .col-12, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-auto, .col-lg, .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-auto, .col-md, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-auto, .col-sm, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-auto, .col-xl, .col-xl-1, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-auto {
	position: relative;
	width: 100%;
	padding-right: 15px;
	padding-left: 15px;
}
.table-responsive {
	display: block;
	width: 100%;
	overflow-x: auto;
}
.text-right {
	text-align: right !important;
}
.text-left {
	text-align: left !important;
}
p {
	margin-top: 0;
	margin-bottom: 1rem;
}
.table {
	width: 100%;
	margin-bottom: 1rem;
	color: #212529;
}
thead {
	background-color: #00c2cb;
	color: white;
}
table {
	border-collapse: collapse;
}
.table thead th {
	vertical-align: bottom;
	border: none;
}
.table td, .table th {
	padding: .75rem;
	vertical-align: top;
	border-top: 1px solid #dee2e6;
}
*, ::after, ::before {
	box-sizing: border-box;
}
.nota-lunas-box {
    position: relative;
  }
    </style>
</head>

<body class="nota-body" style="margin-left: -1px;">
    <div class="nota-header">
        <div class="container" style="margin-left: -5px;"><img src="{{ public_path('img/klageealamat.png') }}" style="width: 247px;"></div>
    </div>
    <div class="nota-details">
        @if ($data->nominal>0)
        <p class="nota-desc">INCOME TRANSACTION : {{ strtoupper($data->transaction_type) }}</p>
        @else
        <p class="nota-desc">EXPENSE TRANSACTION : {{ strtoupper($data->transaction_type) }}</p>
        @endif
            <p class="nota-tanggal">MALANG,<br>{{date('d',strtotime($data->date))}} {{ date('F',strtotime($data->date)) }} {{ date('Y',strtotime($data->date)) }}</p>
    </div>
    <div>
        <div class="table-responsive nota-table">
            <table class="table">
                <thead class="nota-thead">
                    <tr class="nota-tr">
                        <th class="text-left nota-td">DESCRIPTION</th>
                        <th class="text-left nota-td">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="nota-tr">
                        <td class="nota-td">{{ strtoupper($data->sub_transaction.', '.$data->detail) }}</td>
                        @if ($data->nominal < 0)
                        <td class="nota-td">- Rp.{{ $data->nominal*-1 }}</td>
                        @else
                        <td class="nota-td">Rp.{{ $data->nominal }}</td>
                        @endif
                    </tr>
                    <tr class="nota-tr nota-grand-total">
                        <td class="nota-td nota-lunas-box">GRAND TOTAL
                            @if ($data->nominal>0)
                            <h1 class="float-right nota-lunas">LUNAS</h1>
                            @endif
                        </td>
                        @if ($data->nominal < 0)
                        <td class="nota-td">- Rp.{{ $data->nominal*-1 }}</td>
                        @else
                        <td class="nota-td">Rp.{{ $data->nominal }}</td>
                        @endif

                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="nota-ttd-box">
            <div style="float: left;">
                <p class="nota-payment">PAYMENT DETAILS<br></p>
                <p class="nota-payment-detail">{{ $data->payment_method }}</p>
            </div>
                <div style="float: right; position:relative;">
                    <img class="ttd-cap" src="{{ public_path('img/1.png') }}">
                    <p class="text-right nota-ttd">HEAD TEACHER</p>
                    <p class="text-right nota-ttd-name">Teacher's Name</p>
                </div>
    </div>
</body>

</html>
