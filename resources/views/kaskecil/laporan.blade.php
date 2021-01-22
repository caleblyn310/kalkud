@extends('layouts/app')
@section('css')
<link href="{{ asset('js/tE/src/stable/css/tableexport.min.css') }}" rel="stylesheet">
@stop

@section('content')
@include('_partial.flash_message')
<div class="container">
    {!! Form::open(['url'=>'laporanka','method'=>'GET', 'class'=>'']) !!}
    <div class="container" style="padding-top: 15px;padding-left: 0px;">
		<div class="form-group row">
            <div class="col-lg-6">
                <div class="container">
                    <div class="row">
                        <div class="custom-control custom-radio custom-control-inline col-3">
                          <input class="custom-control-input" type="radio" id="kategori1" name="kategori" value="0">
                          <label class="custom-control-label" for="kategori1" style="color: darkgrey;"><strong>Kode D-Ger</strong></label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline col-3">
                          <input class="custom-control-input" type="radio" id="kategori2" name="kategori" value="1">
                          <label class="custom-control-label" for="kategori2" style="color: darkgrey;"><strong>SubKode</strong></label>
                        </div>
                        <div class="col-lg-4">
                            {!! Form::select('subkode',$sk_list,null, ['class' => 'custom-select custom-select-sm', 'id' => 'subkode', 'placeholder' => 'Pilih Sub Kode','autofocus'=>'']) !!}
                            {!! Form::select('coa', $coa_list,null, ['class' => 'custom-select custom-select-sm', 'id' => 'coa', 'placeholder' => 'Pilih COA', 'autofocus'=>'','style'=>'display:none']) !!}
                        </div>
                    </div>
                </div>    
            </div>
            @if ($errors->any())
                <div class = "form-group col-6{{ $errors->has('tanggal1') || $errors->has('tanggal2') ? 'has-error' : 'has-success' }}">
            @else
                <div class="form-group col-6">
            @endif
                	<div class="row"> 
                    <strong>FROM</strong>&nbsp;&nbsp;{!! Form::date('tanggal1','2019-03-01',['class'=>'form-control form-control-sm col-3','required'=>'','id'=>'tanggal1']) !!}&nbsp;<strong>TO</strong>&nbsp; 
                    {!! Form::date('tanggal2','2019-03-31',['class'=>'form-control form-control-sm col-3','required'=>'','id'=>'tanggal2']) !!}&nbsp;&nbsp;
                    {!! Form::submit('Get Data',['class'=>'btn btn-sm btn-warning','id'=>'getsk']) !!}
                    @if($errors->has('tanggal1'))<input type="hidden" class="col-md-4 errorTanggal">{{ $errors->first('tanggal1') }}</span>
                    @elseif($errors->has('tanggal2'))<input type="hidden" class="col-md-5 errorTanggal">{{ $errors->first('tanggal2') }}</span>
                    @endif
                </div>
                </div>
		</div>
	</div>
	{!! Form::close() !!}

    <div class="container">
        <div class="row" style="padding-top: 0px;">
            @if(!empty($transactions))
            <div class="card" style="width: 100%;">
                <div class="card-body" style="background-color: #d5f4e6;height:450px;overflow:auto;padding-top: 10px;">
                    <H6>Detail Transaksi @if (!empty($judul)) ({{ $judul }})&nbsp;@endif
                    <a href="#" class=" btn btn-sm btn-success" id="customxls">Convert to Excel</a>
                    <a href="#" class=" btn btn-sm btn-danger" id="custompdf">Print via PDF (F4)</a>
                    <input type="hidden" id="sk" value="{{ $sk }}">
                </H6><hr>
                <table class="table table-striped table-bordered table-hover table-sm" id="tblTrans">
                <colgroup><col><col><col></colgroup>
                <thead><tr>
                    <th>Tanggal</th><th>Kode D-Ger</th><th>Sub<br>Kode</th><th>No BPU</th><th style="width: 72%;">Deskripsi</th><th>Nominal</th>
                </tr></thead>
                <tbody>
                <?php $totalnominal = 0 ?>
                <?php foreach ($transactions as $trans) : ?>
                <tr>
                    <td>{{ Carbon\Carbon::parse($trans->tanggal_trans)->format('d/m/Y') }}</td>
                    <td>{{ $trans->kode_d_ger }}</td>
                    <td>{{ $trans->subkode }}</td>
                    <td>{{ $trans->no_bukti }}</td>
                    <td style="text-align: left;">{{ $trans->deskripsi }}</td>
                    <td style="text-align: right;">{{ number_format($trans->nominal,0,'','') }}</td>
                    <?php $totalnominal+= $trans->nominal ?>
                </tr>
                <?php endforeach ?>
                <tr>
                    <td colspan="5" style="text-align: right;"><strong>TOTAL</strong></td>
                    <td style="text-align: right;">{{ number_format($totalnominal,0,'','') }}</td>
                </tr>
                <tr><td colspan="6" style="text-align: center;background-color: grey;"></td></tr>
                @if(!empty($totalsk))
                <tr><td colspan="6" style="text-align: center;"><strong>Jumlah per SUB Kode</strong></td></tr>
                <tr><td colspan="5" style="text-align: right;"><strong>Sub Kode</strong></td>
                    <td><strong>Nominal</strong></td></tr>
                @for ($i=0; $i < count($totalsk); $i++)
                    <tr><td colspan="5" style="text-align: right;">{{ $totalsk[$i]['sub'] }}</td>
                        <td style="text-align: right;">{{ number_format($totalsk[$i]['total'],0,'','') }}</td></tr>
                @endfor
                <tr><td colspan="5" style="text-align: right;"><strong>TOTAL</strong></td>
                    <td style="text-align: right;">{{ number_format($totalnominal,0,'','') }}</td>
                    </tr>
                <tr><td colspan="6" style="text-align: center;background-color: grey;"></td></tr>
                @endif
                </tbody>
                <tfoot></tfoot>
            </table>
                </div>
            </div>@endif
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

<script>
	$(document).ready(function () {
		//$('#subkode').append('<option value="0">Semua</option>');
        //$('#ui-id-2-button').hide();$('#ui-id-2').hide();$('.coa').hide();
        //$("span#ui-id-2-button").hide();
        $('#coa').hide();
        @if(!empty($sk))
            var sk = $('#sk').val().substring(0,1);
            if(sk == '0')
                {
                    $("#kategori1").prop("checked", true);
                    $('#coa').show();$('#subkode').hide();
                    /*$("#kategori1").prop("checked", true).button("refresh");
                    $("#kategori2").prop("checked", false).button("refresh");
                    $('.coa').selectmenu("widget").show();
                    $('.subkode').selectmenu("widget").hide();*/
                }
            else if(sk == '1')
                {
                    $("#kategori2").prop("checked", true);
                    $('#coa').hide();$('#subkode').show();
                    /*$("#kategori1").prop("checked", false).button("refresh");
                    $("#kategori2").prop("checked", true).button("refresh");
                    $('.coa').selectmenu("widget").hide();
                    $('.subkode').selectmenu("widget").show();*/
                }
        @endif

        if($(".errorTanggal").length) {
        //$(".errorTanggal").hide();
        toastr.error($(".errorTanggal").html(), {timeOut: 5000});
         }
	});

    $("#customxls").click(function() {
        var tbl = document.getElementById('tblTrans');
        var temp = new TableExport(tbl, {
            formats: ['xls'],
            filename: 'Rekap Kas Admin',
            exportButtons: false
        });
        var expData = temp.getExportData()['tblTrans']['xls'];
        temp.export2file(expData.data, expData.mimeType, expData.filename, expData.fileExtension);
    });

    $("#custompdf").click(function() {
        spinner.show();
        $.ajax({
            type: 'GET',
            url: 'laporanka/mpdf',
            data: {
                'tanggal1': $('#tanggal1').val(),
                'tanggal2': $('#tanggal2').val(),
                'subkode': $('#sk').val()
                },
            success: function(data) {
                spinner.hide();
                window.open('/storage/' + data.name,'_blank')
                //window.location.assign('/storage/' + data.name,'_blank');
            },
        });
    });

    $("#kategori1").click(function() {
        $('#coa').show();
        $('#subkode').hide();
        /*$('.subkode').selectmenu("widget").hide();
        $('.coa').selectmenu("widget").show();*/
    })
    $("#kategori2").click(function() {
        $('#coa').hide();
        $('#subkode').show();
        /*$('.subkode').selectmenu("widget").show();
        $('.coa').selectmenu("widget").hide();*/
    })
</script>
@stop