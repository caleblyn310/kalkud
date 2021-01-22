@extends('layouts.app')

@section('content')
	<div class="container">
		<h5>Detail of </h5>
		<table class="table table-sm table-condensed table-striped table-bordered" id=tblKK>
			<thead>
				<tr>
					<th>Tanggal</th><th>Kode D-Ger</th><th>Sub<br>Kode</th><th>No BPU</th><th style="width: 70%;">Deskripsi</th><th>Nominal</th>
				</tr>
			</thead>
			<tbody>
				<?php $total = 0 ?>
				<?php foreach($kaskecil_list as $kaskecil) ?>
				<tr>
                    <td>{{ Carbon\Carbon::parse($kaskecil->tanggal_trans)->format('d-m-Y') }}</td>
                    <td>{{ $kaskecil->kode_d_ger }}</td>
                    <td>{{ $kaskecil->subkode }}</td>
                    <td>{{ $kaskecil->no_bukti }}</td>
                    <td style="text-align: left;">{{ $kaskecil->deskripsi }}</td>
                    <td style="text-align: right">{{ number_format($kaskecil->nominal,0,",",".") }}</td>
                    <?php $total += $kaskecil->nominal ?>
                </tr>
				<?php endforeach ?>
				<tr><td colspan="5"><<b>TOTAL</b></td></tr>
			</tbody>
		</table>
	</div>
@endsection