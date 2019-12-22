<!DOCTYPE html>
<html>
<head>
	<title>Data Pegawai</title>
	<style>
		* {
			font-family: sans-serif;
		}
		h1 {
			text-align: center;
		}
		table {
			width: 100%;
			border-collapse: collapse;
		}
		td, th {
			padding: 8px;
			border: 1px solid black;
		}
	</style>
</head>
<body>
	<h1>DATA DIVISI</h1>
	Tanggal Ekspor : {{ date('Y-m-d') }}
	<br>
	<br>
	<table>
		<thead>
			<tr>
				<th>#</th>
				<th>Nama</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($data as $d)
			<tr>
				<td width="20px">{{ $loop->iteration }}</td>
				<td>{{ $d->nama }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	<script>
		window.print();
	</script>
</body>
</html>