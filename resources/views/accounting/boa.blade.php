@extends('layouts/app')
@section('css')
<link href="{{ asset('js/tE/src/stable/css/tableexport.min.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="container">
    @include('_partial.flash_message')
    <div style="background-color: #eef9fd; padding: 15px 10px 1px 10px; margin-top: 10px; margin-bottom: 10px;">
        {!! Form::open(['url'=>'boa','method'=>'GET', 'class'=>'']) !!}
            @if ($errors->any())
                <div class = "form-group row {{ $errors->has('kode_d_ger') ? 'has-error' : 'has-success' }}">
            @else
                <div class="form-group row">
            @endif
                {!! Form::label('kode_d_ger','Kode COA',['class'=>'col-md-2 form-control-label','style'=>'color:black; font-weight: bold;']) !!}
                <div class="col-lg-8">
                    <div class="row"> 
                    {!! Form::text('qBOA', '', ['class'=>'form-control form-control-sm col-md-4','id' =>  'qBOA', 'placeholder' =>  'Find d-ger code',
                    'autofocus'=>'','required'=>'']) !!}&nbsp;&nbsp;&nbsp;
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
                {!! Form::label('periode','Periode:',['class'=>'col-lg-2 form-control-label','style'=>'color:black; font-weight: bold;']) !!}
                <div class="col-lg-8">
                	<div class="row"> 
                    <b>FROM</b>&nbsp;&nbsp;&nbsp;{!! Form::date('tanggal1','2019-03-01',['class'=>'form-control form-control-sm col-md-2','required'=>'','id'=>'tanggal1']) !!}&nbsp;&nbsp;&nbsp;<b>TO</b>&nbsp;&nbsp;&nbsp; 
                    {!! Form::date('tanggal2','2019-03-31',['class'=>'form-control form-control-sm col-md-2','required'=>'','id'=>'tanggal2']) !!}&nbsp;&nbsp;&nbsp;{!! Form::submit('Get Data',['class'=>'btn btn-sm btn-primary','id'=>'getboa']) !!}
                    @if($errors->has('tanggal1'))<span class="col-md-4">{{ $errors->first('tanggal1') }}</span>
                    @elseif($errors->has('tanggal2'))<span class="col-md-5">{{ $errors->first('tanggal2') }}</span>
                    @endif
                </div>
                </div>
                </div>
                </div>
        {!! Form::close() !!}
</div>

    <div class="container">
        <div class="row" style="padding-top: 0px;">
            @if(!empty($transactions))
            <div class="card" style="width: 100%;">
                <div class="card-body" style="background-color: #d5f4e6;height:450px;overflow:auto;padding-top: 10px;">
                    <H6>Detail Transaksi ({{ $coa }})&nbsp;
                    <a href="#" class=" btn btn-sm btn-success" id="customxls">Convert to Excel</a>
                    <a href="#" class=" btn btn-sm btn-danger" id="custompdf">Print via PDF</a>
                </H6><hr>
                <table class="table table-striped table-bordered table-hover table-sm" id="tblTrans">
                <colgroup><col><col><col></colgroup>
                <thead><tr>
                    <td style="width:50px;">Tanggal</td><td style="width: 175px;">No bukti</td><td>Uraian</td><td>Debit</td><td>Kredit</td><td>Saldo</td><td style="width: 77px;">Kontra Acc</td>
                </tr></thead>
                <tbody>
                <?php $tempsaldo = 0 ?><?php $tdebit = 0 ?><?php $tkredit = 0 ?>
                <?php foreach ($transactions as $trans) : ?>
                <tr>
                    <td>{{ Carbon\Carbon::parse($trans->Tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $trans->No_bukti }}</td>
                    <td style="text-align: left;">{{ $trans->Uraian }}</td>
                    @if($trans->No_account == $coa)
                        <td style="text-align: right;">{{ number_format($trans->Debet,2,',','.') }}</td>
                        <td style="text-align: right">{{ number_format($trans->Kredit,2,',','.') }}</td>
                        <?php $tdebit += $trans->Debet ?>
                        <?php $tkredit += $trans->Kredit ?>
                        @if($flag == 'D' or $flag == 'DR')
                            <?php $tempsaldo += $trans->Debet - $trans->Kredit ?>
                        @else
                            <?php $tempsaldo += $trans->Kredit - $trans->Debet ?>
                        @endif
                        <td style="text-align: right">{{ number_format($tempsaldo,2,',','.') }}</td>
                        <td>{{ $trans->Kontra_acc }}</td>
                    @elseif ($trans->Kontra_acc == $coa)
                        @if($trans->Kredit == 0)
                            <?php $tkredit += $trans->Debet ?>
                            <td style="text-align: right">0</td>
                            <td style="text-align: right;">{{ number_format($trans->Debet,2,',','.') }}</td>
                        @elseif($trans->Debet == 0)
                            <?php $tdebit += $trans->Kredit ?>
                            <td style="text-align: right">{{ number_format($trans->Kredit,2,',','.') }}</td>
                            <td style="text-align: right;">0</td>
                        @endif

                        @if($flag == 'K' or $flag == "CR")
                            <?php $tempsaldo += $trans->Debet - $trans->Kredit ?>
                        @else
                            <?php $tempsaldo += $trans->Kredit - $trans->Debet ?>
                        @endif

                        <td style="text-align: right;">{{ number_format($tempsaldo,2,',','.') }}</td>
                        <td>{{ $trans->No_account }}</td>
                    @endif
                </tr>
                <?php endforeach ?>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>TOTAL</strong></td>
                    <td style="text-align: right;">{{ number_format($tdebit,2,',','.') }}</td>
                    <td style="text-align: right;">{{ number_format($tkredit,2,',','.') }}</td>
                    <td colspan="2"></td>
                </tr>
                <tr><td colspan="7" style="background-color: grey;"></td></tr>
                <tr><td colspan="3" style="text-align: right;">Saldo Awal</td>
                    <td colspan="4" style="text-align: left;">{{ number_format($saw,2,',','.') }}</td></tr>
                <tr><td colspan="3" style="text-align: right;">Saldo Akhir</td>
                    <td colspan="4" style="text-align: left;">{{ number_format($saw + $tempsaldo,2,',','.') }}</td></tr>
                <tr><td colspan="7" style="text-align: center;background-color: grey;"></td></tr>
                <tr><td colspan="7" style="text-align: center;"><strong>Jumlah per COA</strong></td></tr>
                <tr><td colspan="2" style="text-align: right;"><strong>Kontra COA</strong></td>
                    <td><strong>Keterangan</strong></td>
                    <td><strong>Debet</strong></td>
                    <td><strong>Kredit</strong></td><td colspan="2"></td></tr>
                <?php $totDeb = 0 ?><?php $totKre = 0 ?>
                @for ($i=0; $i < count($sums); $i++)
                    <tr><td colspan="2" style="text-align: right;">{{ $sums[$i]['coa'] }}</td>
                        <td style="text-align: left;">{{ $sums[$i]['ket'] }}</td>
                        <td style="text-align: right;">{{ number_format($sums[$i]['totDeb'],2,',','.') }}</td>
                        <td style="text-align: right;">{{ number_format($sums[$i]['totKre'],2,',','.') }}</td>
                        <td colspan="2"></td></tr>
                    <?php $totDeb += $sums[$i]['totDeb']?><?php $totKre += $sums[$i]['totKre']?>
                @endfor
                <tr><td colspan="3" style="text-align: right;"><strong>TOTAL</strong></td>
                    <td style="text-align: right;">{{ number_format($totDeb,2,',','.') }}</td>
                    <td style="text-align: right;">{{ number_format($totKre,2,',','.') }}</td>
                    <td colspan="2"></td></tr>
                <tr><td colspan="7" style="text-align: center;background-color: grey;"></td></tr>
                </tbody>
                <tfoot></tfoot>
            </table>
                </div>
            </div>
            @elseif(!empty($saw))
            <div class="card" style="width: 100%;">
                <div class="card-body" style="background-color: #d5f4e6;height:450px;overflow:auto;padding-top: 10px;"><H6>Detail Transaksi</H6><hr>
                    <table class="table table-striped table-bordered table-hover table-sm" id="tblTrans" style="width: 100%;">
                        <tr><td colspan="3" style="text-align: right;">Saldo Awal</td>
                        <td colspan="3" style="text-align: left;">{{ number_format($saw,2,',','.') }}</td></tr>
                        <tr><td colspan="3" style="text-align: right;">Saldo Akhir</td>
                        <td colspan="3" style="text-align: left;">{{ number_format($saw,2,',','.') }}</td></tr>
                    </table>
                </div>
            </div>
    </div>
    @endif
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
    $("#customxls").click(function() {
        var str = $('#qBOA').val().slice(0,($('#qBOA').val().indexOf("|")-1));
        var tbl = document.getElementById('tblTrans');
        var temp = new TableExport(tbl, {
            formats: ['xls'],
            filename: str,
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

    $("#custompdf2").click(function() {
        spinner.show();
        var doc = new jsPDF();
        doc.setFontSize(10);
        doc.setFontStyle('bold');
        doc.text("Laporan " + $('#qBOA').val() + " - (" + $('#tanggal1').val() + " - " + $('#tanggal2').val() + ")", 5,7);
        doc.autoTable({html: '#tblTrans',
            willDrawCell: function(data) {
                if (data.cell.text == "Saldo Awal") {
                    doc.setTextColor(231, 76, 60); // Red
                    doc.setFontStyle('bold');
                    doc.cell.colSpan = 5;
                }
            },
            styles: {fontSize: 6,cellPadding: 0.6,lineWidth:0.2}, tableLineWidth: 0.3,
            theme: 'grid' , startY: 9, margin: {left: 5, right: 7}});
        doc.save('table.pdf');
        spinner.hide();
    });

    $( "#qBOA" ).autocomplete({
        source: "http://"+location.hostname+"/search/autocompleteBOA",
        minLength: 3,
        select: function(event, ui) {
            $('#qBOA').val(ui.item.value);
            $('#kode_d_ger').val(ui.item.id);
        }
    });
</script>
@stop