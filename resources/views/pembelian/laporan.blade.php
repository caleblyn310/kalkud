@extends('layouts/app')

@section('css')
<style type="text/css">
	table.table-bordered{
    border:1px solid grey !important;
  }
table.table-bordered > thead > tr > th{
    border:1px solid grey !important;
}
table.table-bordered > tbody > tr > td{
    border:1px solid grey !important;
}
</style>
@stop

@section('content')
<div class="container">
    @include('_partial.flash_message')
    {!! Form::open(['url'=>'lappembelian','method'=>'GET', 'class'=>'']) !!}
    <div class="container" style="padding-top: 15px;padding-left: 0px;">
		<div class="form-group row">
			<div class="col-lg-6">
				<div class="container">
				<div class="row">
				<div class="custom-control custom-radio custom-control-inline col-2">
			      <input type="radio" class="custom-control-input" id="kategori1" name="kategori" value="0" checked="">
			      <label class="custom-control-label" for="kategori1" style="color: white;"><strong>Global</strong></label>
			    </div>
			    <div class="custom-control custom-radio custom-control-inline col-3">
			      <input type="radio" class="custom-control-input" id="kategori2" name="kategori" value="1">
			      <label class="custom-control-label" for="kategori2" style="color: white;"><strong>per Item</strong></label>
			    </div>
			    <div class="col-6">
			    {!! Form::text('q_brg', '', ['class'=>'form-control form-control-sm','id' =>  'q_brg', 'placeholder' =>  'Cari berdasarkan nama barang . . .',
		        'autofocus'=>'', 'disabled']) !!}
		        {!! Form::hidden('kode_brg', 0,['id'=>'kode_brg']) !!}
		        </div>
		        </div>
		    </div>
            </div>
            @if ($errors->any())
                <div class = "form-group col-lg-6{{ $errors->has('tanggal1') || $errors->has('tanggal2') ? 'has-error' : 'has-success' }}">
            @else
                <div class="form-group col-lg-6">
            @endif
            	<div class="container">
            	<div class="row"> 
                {!! Form::select('periode', $per,null, ['class'=>'form-control form-control-sm col-lg-3','id' => 'periode', 'placeholder' => 'Pilih Periode . . .','autofocus'=>'']) !!}&nbsp;&nbsp;
                {!! Form::submit('Get Data',['class'=>'btn btn-sm btn-warning','id'=>'getsk']) !!}
                @if($errors->has('tanggal1'))<span class="col-md-4">{{ $errors->first('tanggal1') }}</span>
                @elseif($errors->has('tanggal2'))<span class="col-md-5">{{ $errors->first('tanggal2') }}</span>
                @endif
                </div>
            </div>
		</div>
	</div>
</div>
	{!! Form::close() !!}

    <div class="container">
        <div class="row" style="padding-top: 0px;">
            @if(!empty($pd))
            <div class="card" style="width: 100%;">
                <div class="card-body" style="background-color: #d5f4e6;height:1000px;overflow:auto;padding-top: 10px;">
                    <H6>Detail Pembelian Kantin&nbsp;
                    <a href="#" class=" btn btn-sm btn-success" id="customxls">Convert to Excel</a>
                    <a href="#" class=" btn btn-sm btn-danger" id="custompdf" data-id="{{ $kode_brg }}">Print via PDF (F4)</a>
                </H6><hr>
                <table class="table table-striped table-bordered table-hover table-sm" id="tblTrans">
                <colgroup><col><col><col></colgroup>
                <thead><tr>
                    <th>Tanggal</th><th>Nama<br>Barang</th><th>Qty</th><th>Harga<br>Satuan</th><th>Total</th><th>Jumlah</th>
                </tr></thead>
                <tbody>
                <?php $total = 0; $jumlah = 0;?>
                @for ($i=0; $i < count($pd); $i++)
                <tr>
                    <td>{{ Carbon\Carbon::parse($pd[$i]->dot)->format('d/m/Y') }}</td>
                    <td style="text-align: left;">{{ App\DaftarBarang::where('id',$pd[$i]->id_barang)->value('nama_barang') }}</td>
                    <td>{{ $pd[$i]->qty }}</td>
                    <td style="text-align: right;">{{ number_format($pd[$i]->hrg_sat,0,'.',',') }}</td>
                    <td style="text-align: right;">{{ number_format($pd[$i]->hrg_total,0,'.',',') }}</td>
                    <?php $jumlah+= $pd[$i]->hrg_total; $total += $pd[$i]->hrg_total; ?>
                    @if ($i == (count($pd)-1) || $pd[$i]->id_pembelian != $pd[$i+1]->id_pembelian ) 
                    	<td style="text-align: right; background-color: lightgrey;">{{ number_format($total,0,'.',',') }}</td> <?php $total = 0;?>
                    @else <td></td>
                    @endif
                </tr>
                @endfor
                <tr>
                    <td colspan="5" style="text-align: right;"><strong>TOTAL</strong></td>
                    <td style="text-align: right;">{{ number_format($jumlah,0,'.',',') }}</td>
                </tr>
                <tr><td colspan="6" style="text-align: center;background-color: grey;"></td></tr>
                </tbody>
                <tfoot></tfoot>
            </table>
                </div>
            </div>@endif
    </div>
    
</div>
</div>
@stop

@section('scripts')
<script src="{{ asset('js/jspdf.min.js') }}"></script>
<script src="{{ asset('js/jspdf.plugin.autotable.js') }}"></script>
<script src="{{ asset('js/tableExport/tableExport.js') }}"></script>
<script src="{{ asset('js/tableExport/jquery.base64.js') }}"></script>
<script src="{{ asset('js/FileSaver.min.js') }}"></script>
<script src="{{ asset('js/tE/src/stable/js/tableexport.min.js') }}"></script>
<script type="text/javascript">
	$('#kategori1').click(function() {
		$('#q_brg').prop('disabled',true);
	});
	$('#kategori2').click(function() {
		$('#q_brg').prop('disabled',false);
	});
	$( "#q_brg" ).autocomplete({
        source: "http://"+location.hostname+ "/search/barang",
        minLength: 3,
        select: function(event, ui) {
            $('#q_brg').val(ui.item.value);
            $('#kode_brg').val(ui.item.id);
        }
    });
    $("#customxls").click(function() {
        var tbl = document.getElementById('tblTrans');
        var temp = new TableExport(tbl, {
            formats: ['xls'],
            filename: "lappembelian",
            exportButtons: false
        });
        var expData = temp.getExportData()['tblTrans']['xls'];
        temp.export2file(expData.data, expData.mimeType, expData.filename, expData.fileExtension);
    });
    $("#custompdf").click(function() {
        @if(!empty($pd))
        var kode_brg = {{ $kode_brg }};
        var periode = {{ $periode }};
        spinner.show();
        $.ajax({
            type: 'GET',
            url: '/pdfReport',
            data: {
                'periode': periode,
                'kode_brg': kode_brg,
            },
            success: function(data) {
                spinner.hide();
                window.open('/storage/' + data.name,'_blank')
                //window.location.assign('/storage/' + data.name,'_blank');
            },
        });
        @endif
        /*var radioVal = $("input[name='kategori']:checked").val();
        if(radioVal == 1) if($("#q_brg").val() == "") {alert("silakeun pilih barang dulu yee...")} else {alert($("#q_brg").val());}
        else alert("ieu mah kabeuhna");*/
    });
</script>
@stop