@extends('layouts.app')

@section('content')
	<div class="container">
		<div style="margin-top: 10px;"><h5><b>Reimburses request list</b></h5></div>
		@if(!empty($reimburse_list))
		<table class="table table-sm table-striped table-bordered">
			<thead>
				<tr>
					<th>No</th><th>Unit</th><th>Description</th><th>Nominal</th><th style="width: 10%;">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php $no = 1 ?>
				<?php foreach ($reimburse_list as $rl) : ?>
				<tr>
					<td>{{ $no }}</td>
					<td>{{ App\KodeUnit::find($rl->kode_unit)->unit }}</td>
					<td>{{ $rl->namafile }}</td>
					<td>{{ number_format($rl->nominal,0,'.',',') }}</td>
					<td><button type="button" class="btn btn-sm btn-info fa fa-eye reimburse" data-id="{{ str_replace(".pdf","",$rl->namafile) }}"></button></td>
				</tr>
				<?php $no++ ?>
				<?php endforeach ?>
			</tbody>
		</table>
		@endif
	</div>
@endsection

@section('scripts')
<script type="text/javascript">
	$('.reimburse').click(function () {
		location.replace("/getRD/" + $(this).data('id'));
	});
</script>
@endsection