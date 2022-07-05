<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Salary Check</title>
    <style>
        .nota-header {
  background-color: #38b6ff;
}

.salary-body {
  width: 1476px;
  margin:0;
  padding-left: 1px;
  padding-right:1px;
}

.nota-body {
  width: 800px;
}

#nota-table-head {
  background-color: #38b6ff;
}

.salary-thead {
  background-color: #38b6ff;
  border: none;
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

.salary-payment-detail {
  font-size: 25px;
  margin-bottom: -3px;
}

.nota-payment-detail {
  font-size: 20px;
  margin-bottom: -3px;
}

.salary-payment {
  font-weight: bold;
  font-size: 23px;
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
  font-size: 25px;
  float: left;
}
.salary-tanggal {
  font-weight: bold;
  font-size: 25px;
  color: black;
  float: right;
}

.nota-tanggal {
  font-weight: bold;
  font-size: 20px;
  color: #3079c8;
}

.salary-ttd {
  font-weight: bold;
  font-size: 30px;
}

.nota-ttd {
  font-weight: bold;
  font-size: 25px;
}

.salary-ttd-name {
  font-size: 23px;
}

.nota-ttd-name {
  font-size: 23px;
}

.nota-ttd-box {
  margin: 10px 10px 200px 10px;
}

.salary-details {
  margin: 20px 10px 20px 10px;
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
  font-size: 19px;
}

.salary-ttd-cap {
  width: 200px;
  text-align: right;
  z-index: -5;
  position: absolute;
  right: -100px;
  opacity: 0.25;
  top: -50px;
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

.salary-jabatan {
  font-size: 23px;
}

.salary-legal-stuff {
  font-size: 19px;
  float: left;
  clear: both;
}
    </style>
</head>

<body class="salary-body">
    <div class="nota-header">
        <div>
            <img src="{{ public_path('img/klageealamat.png') }}" style="width: 247px;">
            <h1 style="float: right; color:#eeeeee; margin-top:45px; margin-right:40px;">SLIP GAJI</h1>
        </div>
    </div>
    <div class="salary-details">
            <p class="nota-desc">NAMA&nbsp; &nbsp; &nbsp;
                &nbsp;&nbsp;&nbsp;
                &nbsp;: {{ strtoupper($teacher->name) }}<br>
                <span class="salary-jabatan">Jabatan&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ ucwords($teacher->user_type) }}</span><br></p>
            <p class="salary-legal-stuff">Status&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;: {{ ucwords($teacher->position) }}<br>
                Nomor Kontrak&nbsp;&nbsp;: {{ $nk }}<br>
                Divisi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;: {{ $div }}
            </p>
            <p class="text-right salary-tanggal">Periode : {{ date('d F Y', strtotime($date)) }}</p>
    </div>
    <div>
        <div class="table-responsive nota-table">
            <table class="table">
                <thead class="nota-thead">
                    <tr class="nota-tr">
                        <th>Jenis Upah</th>
                        <th>Keterangan Tambahan</th>
                        <th class="text-left nota-td">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salary as $s)
                    <tr class="nota-tr">
                        <td class="nota-td">Gaji Pokok {{ ucwords($teacher->user_type) }} {{ ucwords($teacher->position) }}</td>
                        <td class="nota-td"></td>
                        <td class="nota-td">Rp. {{ $s->nominal }}</td>
                    </tr>
                    @endforeach
                    <tr class="nota-tr">
                        <td class="nota-td">Fee Mengajar {{ ucwords($teacher->user_type) }} Bulan {{ date('F Y',strtotime($date)) }}</td>
                        <td class="nota-td">Total {{ $fee->count() }} Jam</td>
                        <td class="nota-td">Rp. {{ $fee->sum('fee_nominal') }}</td>
                    </tr>
                    <tr class="nota-tr">
                        <td class="nota-td">Insentif Lunch Money</td>
                        <td class="nota-td">{{ $lunch->count() }} Hari</td>
                        <td class="nota-td">Rp. {{ $lunch->sum('lunch_nominal') }}</td>
                    </tr>
                    <tr class="nota-tr">
                        <td class="nota-td">Insentif Transport Money</td>
                        <td class="nota-td">{{ $transport->count() }} Hari</td>
                        <td class="nota-td">Rp. {{ $transport->sum('transport_nominal') }}</td>
                    </tr>
                    @foreach ($incentive as $i)
                        <tr class="nota-tr">
                            <td class="nota-td">{{ $i->name }}</td>
                            <td class="nota-td">{{ $i->note }}</td>
                            <td class="nota-td">Rp. {{ $i->total }}</td>
                        </tr>
                    @endforeach
                    <tr class="nota-tr">
                        <td></td>
                        <td class="nota-td">Total Upah</td>
                        <td class="nota-td">Rp. {{ $incentive->sum('total') + $salary->sum('nominal') + $fee->sum('fee_nominal')
                        + $lunch->sum('transport_nominal') + $transport->sum('transport_nominal') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="nota-ttd-box" style="position: absolute; top:840px;">
        <div>
                <div style="float: left; position: relative;"><img class="salary-ttd-cap" src="{{ public_path('img/1.png') }}">
                    <p class="text-left salary-ttd">Kepala Lembaga</p>
                    <p class="text-left salary-ttd-name">{{ $head->name }}</p>
                </div>
                <div style="float: right;position: absolute; left:950px; width:500px;">
                    <p class="text-right salary-payment">Telah ditransfer dan diterima<br>di tanggal {{ date('d F Y',strtotime($date)) }}</p>
                    <p class="text-right salary-payment-detail">Via {{ $via }}</p>
                </div>
        </div>
    </div>
    <footer class="bg-light text-center text-lg-start">
        <!-- Copyright -->
        <div class="text-center p-3" style="height: 50px; background-color:#38b6ff; padding:15px 10px 0 10px; position: absolute;
        top:990px; width:1481px; margin-left:-5px;">
            <p style="color: white; float: left; font-size:17px;">NPSN <strong>{{ $npsn }}</strong></p>
            <p style="color: white; float: right; font-size:17px;">Nomor Izin Lembaga Pendidikan Non Formal {{ $nilnf }}</p>
        </div>
        <!-- Copyright -->
      </footer>

</body>

</html>
