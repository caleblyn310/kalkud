@extends('layouts/app')
@section('css')
<link href="{{ asset('js/tE/src/stable/css/tableexport.min.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="container">
    @include('_partial.flash_message')
    <div style="padding-top: 15px;">
        {!! Form::open(['url'=>'boa','method'=>'GET', 'class'=>'']) !!}
            @if ($errors->any())
                <div class = "form-group row {{ $errors->has('kode_d_ger') ? 'has-error' : 'has-success' }}">
            @else
                <div class="form-group row">
            @endif
                {!! Form::label('kode_d_ger','Kode COA',['class'=>'col-md-2 form-control-label','style'=>'color:black;']) !!}
                
                <div class="col-md-10">
                    <div class="row"> 
                    {!! Form::text('q', '', ['class'=>'form-control form-control-sm col-md-4','id' =>  'q', 'placeholder' =>  'Find d-ger code',
                    'autofocus'=>'','required'=>'']) !!}&nbsp;
                    {!! Form::text('kode_d_ger',null,['class'=>'form-control form-control-sm col-md-2','id'=>'kode_d_ger','required'=>'','maxlength'=>'10', 'pattern'=>'\d{3}(.)\d{2}(.)\d{3}','readonly'=>'']) !!}
                    @if($errors->has('kode_d_ger'))<span class="col-md-4">{{ $errors->first('kode_d_ger') }}</span>@endif
                </div>
                </div>
                </div>
            @if ($errors->any())
                <div class = "form-group row {{ $errors->has('tanggal1') || $errors->has('tanggal2') ? 'has-error' : 'has-success' }}">
            @else
                <div class="form-group row">
            @endif
                {!! Form::label('periode','Periode',['class'=>'col-md-2 form-control-label','style'=>'color:black;']) !!}
                <div class="col-md-10">
                	<div class="row"> 
                    FROM&nbsp;{!! Form::date('tanggal1','2017-12-01',['class'=>'form-control form-control-sm col-md-2','required'=>'','id'=>'tanggal1']) !!}&nbsp;TO&nbsp; 
                    {!! Form::date('tanggal2','2017-12-31',['class'=>'form-control form-control-sm col-md-2','required'=>'','id'=>'tanggal2']) !!}&nbsp;&nbsp;{!! Form::submit('Get Data',['class'=>'btn btn-sm btn-primary','id'=>'getboa']) !!}
                    @if($errors->has('tanggal1'))<span class="col-md-4">{{ $errors->first('tanggal1') }}</span>
                    @elseif($errors->has('tanggal2'))<span class="col-md-5">{{ $errors->first('tanggal2') }}</span>
                    @endif
                </div>
                </div>
                </div>
                </div>
        {!! Form::close() !!}
</div>

    @if(!empty($transactions))
    <div class="container">
        <div class="row" style="padding-top: 0px;">
            <div class="mx-auto">
            <div class="card">
                <div class="card-header" style="padding-bottom: 0px;"><H6>Detail Transaksi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="#" class=" btn btn-sm btn-success" id="customxls">Convert to Excel</a>
                    <a href="#" class=" btn btn-sm btn-danger" id="custompdf">Print via PDF</a>
                </H6></div>
                <div class="card-body" style="background-color: #d5f4e6;height:450px;overflow:auto;padding-top: 0px;">
                <table class="table table-striped table-bordered table-hover table-sm" id="tblTrans">
                <colgroup><col><col><col></colgroup>
                <thead><tr>
                    <td style="width:50px;">Tanggal</td><td style="width: 175px;">No bukti</td><td>Uraian</td><td>Debit</td><td>Kredit</td><td>Saldo</td><td style="width: 77px;">Kontra Acc</td>
                </tr></thead>
                <tbody>
                <?php $tempsaldo = 0 ?><?php $tdebit = 0 ?><?php $tkredit = 0 ?>
                <?php foreach ($transactions as $trans) : ?>
                <tr>
                    <td>{{ Carbon\Carbon::parse($trans->Tanggal)->format('d/m/y') }}</td>
                    <td>{{ $trans->No_bukti }}</td>
                    <td style="text-align: left;">{{ $trans->Uraian }}</td>
                    @if($trans->No_account == $coa)
                        <td style="text-align: left;">{{ $trans->Debet }}</td>
                        <td style="text-align: right">{{ $trans->Kredit }}</td>
                        <?php $tdebit += $trans->Debet ?>
                        <?php $tkredit += $trans->Kredit ?>
                        @if($flag == 'D')
                            <?php $tempsaldo += $trans->Debet - $trans->Kredit ?>
                        @else
                            <?php $tempsaldo += $trans->Kredit - $trans->Debet ?>
                        @endif
                        <td>{{ $tempsaldo }}</td>
                        <td>{{ $trans->Kontra_acc }}</td>
                    @elseif ($trans->Kontra_acc == $coa)
                        @if($trans->Kredit == 0)
                            <?php $tkredit += $trans->Debet ?>
                            <td style="text-align: right">0</td>
                            <td style="text-align: left;">{{ $trans->Debet }}</td>
                        @elseif($trans->Debet == 0)
                            <?php $tdebit += $trans->Kredit ?>
                            <td style="text-align: right">{{ $trans->Kredit }}</td>
                            <td style="text-align: left;">0</td>
                        @endif

                        @if($flag == 'K')
                            <?php $tempsaldo += $trans->Debet - $trans->Kredit ?>
                        @else
                            <?php $tempsaldo += $trans->Kredit - $trans->Debet ?>
                        @endif

                        <td>{{ $tempsaldo }}</td>
                        <td>{{ $trans->No_account }}</td>
                    @endif
                </tr>
                <?php endforeach ?>
                <tr>
                    <td colspan="3" style="text-align: right;">Total: </td>
                    <td>{{ $tdebit }}</td>
                    <td>{{ $tkredit }}</td>
                </tr>
                <tr><td colspan="7">&nbsp;</td></tr>
                <tr><td colspan="3" style="text-align: right;">Saldo Awal</td>
                    <td colspan="3" style="text-align: left;">{{ $saw }}</td><td></td></tr>
                <tr><td colspan="3" style="text-align: right;">Saldo Akhir</td>
                    <td colspan="3" style="text-align: left;">{{ $saw + $tempsaldo }}</td><td></td></tr>
                </tbody>
                <tfoot></tfoot>
            </table>
                </div>
            </div>
            </div>
        </div>
    </div>
    @endif
</div>
@stop

@section('scripts')
<script src="{{ asset('js/tableExport/tableExport.js') }}"></script>
<script src="{{ asset('js/tableExport/jquery.base64.js') }}"></script>
<!--<script src="{{ asset('js/FileSaver.min.js') }}"></script>
<script src="{{ asset('js/tE/src/stable/js/tableexport.min.js') }}"></script>-->
<script src="{{ asset('js/tableExport/jspdf/libs/sprintf.js')}}"></script>
<script src="{{ asset('js/tableExport/jspdf/jspdf.js')}}"></script>
<script src="{{ asset('js/tableExport/jspdf/libs/base64.js')}}"></script>
<script>
    $("#customxls").click(function() {
        var tbl = document.getElementById('tblTrans');
        var temp = new TableExport(tbl, {
            formats: ['xls'],
            exportButtons: false
        });
        var expData = temp.getExportData()['tblTrans']['xls'];
        temp.export2file(expData.data, expData.mimeType, expData.filename, expData.fileExtension);
    });

    $("#custompdf").click(function() {
        spinner.show();
        $.ajax({
            type: 'GET',
            url: '/mpdf',
            data: {
                'tanggal1': $('#tanggal1').val(),
                'tanggal2': $('#tanggal2').val(),
                'kode_d_ger': $('#kode_d_ger').val()
            },
            success: function(data) {
                spinner.hide();
                window.open('/storage/' + data.name,'_blank')
                //window.location.assign('/storage/' + data.name,'_blank');
            },
        });
    });
</script>
@stop