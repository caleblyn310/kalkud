@extends('layouts/app')

@section('content')
	@include('_partial.flash_message')
	<div class="container" style="margin-top: 60px;">
		<div class="row">
			<button id="pembelian" class="btn btn-danger col-2 mx-auto" style="height:200px;width:200px"><strong>Pembelian</strong></button>
			<button id="penerimaan" class="btn btn-success col-2 mx-auto" style="height:200px;width:200px"><strong>Penerimaan</strong></button>
			<a class="btn btn-warning col-2 mx-auto" style="height:200px;width:200px"><strong>Transfer Stok</strong></a>
			<a class="btn btn-primary col-2 mx-auto" style="height:200px;width:200px"><strong>Stok</strong></a>
		</div>
	</div>
@stop

@section('scripts')
<script type="text/javascript">
	$('#pembelian').click(function () {
		window.location = 'http://localhost/pembelian';
	});
	$('#penerimaan').click(function () {
		window.location = 'http://localhost/penerimaan';
	});
</script>
@stop