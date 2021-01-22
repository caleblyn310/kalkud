@extends('layouts/app')

@section('css')
<style type="text/css">
#frmInput {
    display: none;
}
.nopad > [class*='col-'] {
    margin-left: 0;
    margin-right: 0;
    padding-right:1ch;
    padding-left:1ch;
}
.nav-pills {
    background-color: #FFFFFF;
}
</style>
@stop

@section('content')
    @include('_partial.flash_message')
    <div class="container" id="frmInput">
        <div class="row" style="padding-top: 15px;">
            <div class="col mx-auto">
            <div class="card">
                <div class="card-body">
                    {!! Form::open(['url'=>'kaskecil','class'=>'']) !!}
                    {!! Form::hidden('kode_unit',Auth::user()->kode_unit) !!}

    <div class="form-group row nopad">
    <div class="col-lg-1">
        {!! Form::date('tanggal_trans',!empty($kaskecil) ? $kaskecil->tanggal_trans->format('Y-m-d') : date('Y-m-d')
        ,['class'=>'form-control form-control-sm','required'=>'']) !!}
    </div>

    <div class="col-lg-2">
        {!! Form::text('qqq', '', ['class'=>'form-control form-control-sm','id' =>  'qqq', 'placeholder' =>  'Cari kode d-ger',
        'autofocus'=>'']) !!}
        <span>kode_d_ger</span>
        {!! Form::hidden('kode_d_ger',null,['class'=>'form-control form-control-sm','placeholder'=>'KODE D-Ger',
        'required'=>'','maxlength'=>'10','pattern'=>'\d{3}(.)\d{2}(.)\d{3}','readonly'=>'']) !!}
    </div>
    
    <div class="col-lg-1">
        {!! Form::text('subkode',null,['class'=>'form-control form-control-sm','placeholder'=>'SK','maxlength'=>'2']) !!}
    </div>

    <div class="col-lg-1">
    {!! Form::text('no_bukti',null,['class'=>'form-control form-control-sm','placeholder'=>'No BPU','required'=>'',
    'maxlength'=>'10','pattern'=>'K(A|K|B)\d{5,}']) !!}
    </div>

    <div class="col-lg-4">
        {!! Form::text('deskripsi',null,['class'=>'form-control form-control-sm','placeholder'=>'Deskripsi','required'=>'']) !!}
    </div>

    <div class="col-lg-2">
        {!! Form::text('nominal',null,['class'=>'form-control form-control-sm','placeholder'=>'Nominal', 'maxlength'=>'8',
        'required'=>'']) !!}&nbsp;<span id="errmsg"></span>
    </div>

    <div class="col-md-2 col-md-offset-4">
        <button type="submit" class="btn btn-sm btn-success fa fa-save"></button>
        <a href="{{ asset('kaskecil') }}" class="btn btn-sm btn-danger">Cancel</a>
    </div>

    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>

    <div class="container table-responsive">
        <div class="row">
            <div class="tomb text-right btn-group-vertical" style="width: 80px; height: auto;">
                @if(Auth::user()->kode_unit < 10)
                <a href="kaskecil/create" class="btn btn-sm btn-info btn-block">ADD</a>
                @else
                <button class="btn btn-sm btn-primary" id="customxls">Excel</button>
                <button class="btn btn-sm btn-warning" id="checking">Requested</button>
                @endif
            </div>
                
        @if (!empty($kaskecil_list))
            <div class="col-lg-12">
            <ul class="nav nav-pills" role="tablist" style="background-color: rgba(211,244,255,.6);" id="myTab">
                <?php foreach ($kode_unit as $ku) : ?>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#menu{{ $ku->kode_unit }}">{{ App\KodeUnit::where('id',$ku->kode_unit)->value('middle')}}</a></li>
                <?php endforeach ?>
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#menuAll">Semua Unit</a>
                    </li>
            </ul>
            </div>     
            

            <div class="tab-content col-lg-12">
                <div class="tab-pane active" role="tabpanel" id="menuAll">
                    <table class="table table-striped table-bordered table-hover table-condensed table-sm" id="tblAll">
                        <thead>
                            <tr>
                                <th>Tanggal</th><th>Kode D-Ger</th><th>Kode<br>Unit</th><th>Sub<br>Kode</th><th>No BPU</th><th style="width: 70%;">Deskripsi</th><th>Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total=0 ?>
                            <?php foreach($kaskecil_list as $kaskecil) : ?>
                            <tr>
                                <td>{{ Carbon\Carbon::parse($kaskecil->tanggal_trans)->format('d-m-Y') }}</td>
                                <td>{{ $kaskecil->kode_d_ger }}</td>
                                <td>{{ $kaskecil->kode_unit }}</td>
                                <td>{{ $kaskecil->subkode }}</td>
                                <td>{{ $kaskecil->no_bukti }}</td>
                                <td style="text-align: left;">{{ $kaskecil->deskripsi }}</td>
                                <td style="text-align: right">{{ number_format($kaskecil->nominal,0,",",".") }}</td>
                                <?php $total += $kaskecil->nominal ?>
                            </tr>
                            <?php endforeach ?>
                            <tr><td colspan="6" style="text-align: right;"><b>Total: </b></td><td>{{ number_format($total,0,",",".") }}</td></tr>
                        </tbody>
                    </table>
                </div>
            <?php foreach($groupKaskecil as $gk) : ?>
                <div class="tab-pane fade" role="tabpanel" id="menu{{ $gk[0]->kode_unit }}">
                <table class="table table-striped table-bordered table-hover table-condensed table-sm" id="tbl{{ $gk[0]->kode_unit }}">
                @if(Auth::user()->kode_unit != 100)
                <caption style="caption-side: top;color: black;">Daftar kas kecil yang belum di upload <strong>(Plafon: Rp. {{ number_format($plafon,0,'','.') }},00 - Total Reimburse: <u>{{ number_format($totalreim,0,"",".") }}</u> | Sisa Saldo: <u>{{ number_format($plafon-$totalreim,0,"",".") }}</u>)</strong></caption>
                @endif
                <thead><tr>
                    <th>Tanggal</th><th>Kode D-Ger</th><th>Kode<br>Unit</th><th>Sub<br>Kode</th><th>No BPU</th><th style="width: 70%;">Deskripsi</th><th>Nominal</th>
                    @if(Auth::user()->kode_unit != 100)
                    <th style="width: 77px;">Action</th>
                    @endif
                </tr></thead>
                <tbody>
                <?php $total = 0 ?>
                <?php foreach ($gk as $kaskecil) : ?>
                <tr>
                    <td>{{ Carbon\Carbon::parse($kaskecil->tanggal_trans)->format('d-m-Y') }}</td>
                    <td>{{ $kaskecil->kode_d_ger }}</td>
                    <td>{{ $kaskecil->kode_unit }}</td>
                    <td>{{ $kaskecil->subkode }}</td>
                    <td>{{ $kaskecil->no_bukti }}</td>
                    <td style="text-align: left;">{{ $kaskecil->deskripsi }}</td>
                    <td style="text-align: right">{{ number_format($kaskecil->nominal,0,",",".") }}</td>
                    <?php $total += $kaskecil->nominal ?>
                    @if(Auth::user()->kode_unit != 100)
                    <td>
                        <div class="box-button">{{link_to('kaskecil/'.$kaskecil->id.'/edit','',['class'=>'btn btn-warning btn-sm fa fa-pencil-square-o'])}}</div>
                        <div class="box-button">{!! Form::open(['method'=>'DELETE', 'action'=>['KaskecilController@destroy',$kaskecil->id]]) !!}
                            {!! Form::button('', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm fa fa-trash',
                            'onclick'=> 'return confirm("Yakin nih mau dihapus??")']) !!}{!! Form::close() !!}</div>
                    </td>
                    @endif
                </tr>
                <?php endforeach ?>
                <tr><td colspan="6" style="text-align: right;"><b>Total: </b></td><td>{{ number_format($total,0,",",".") }}</td></tr>
                @if(Auth::user()->kode_unit != 100)
                <?php (count($totalcoa) > count($totalsk)) ? $totalrow=count($totalcoa) : $totalrow=count($totalsk); 
                if(count($totalsk)>0) $totalsk[0]->subkode = 'Kas Kecil'; ?>
                <tr><td colspan="4" style="text-align:right;">Total Nominal Per Sub Kode</td>
                    <td colspan="3" style="text-align:right;">Total Nominal Per COA</td></tr>
                <?php for ($i=0; $i < $totalrow; $i++) : ?>
                    <tr>
                    @if(count($totalsk)>$i)
                        <td colspan="2" style="text-align:right;">{{ $totalsk[$i]->subkode }}</td>
                        <td colspan="2" style="text-align:right">{{number_format($totalsk[$i]->total,0,'','.')}}</td>
                    @else <td colspan="4"></td> @endif
                    @if(count($totalcoa)>$i)
                        <td style="text-align:right;" colspan="2">{{$totalcoa[$i]->kode_d_ger}}</td>
                        <td style="text-align:right;">{{number_format($totalcoa[$i]->total,0,'','.')}}</td>
                    @else <td colspan="3"></td> @endif
                    </tr>
                <?php endfor ?>
                @endif
                </tbody>
            </table>
        </div>
            <?php endforeach ?>
        </div>

        @else
            <p>Tidak ada data kas kecil yang belum di upload</p>
        @endif
    </div></div>
@endsection

@section('scripts')
<script src="{{ asset('js/jspdf.min.js') }}"></script>
<script src="{{ asset('js/jspdf.plugin.autotable.js') }}"></script>
<script src="{{ asset('js/tableExport/tableExport.js') }}"></script>
<script src="{{ asset('js/tableExport/jquery.base64.js') }}"></script>
<script src="{{ asset('js/FileSaver.min.js') }}"></script>
<script src="{{ asset('js/tE/src/stable/js/tableexport.min.js') }}"></script>

<script>
    $("#customxls").click(function() {
        var idTbl = $('.nav.nav-pills>li>a.active').attr('href').replace("#menu","tbl");alert(idTbl);
        var tbl = document.getElementById(idTbl);
        var temp = new TableExport(tbl, {
            formats: ['xls'],
            filename: "Kaskecil Berjalan " + $('.nav.nav-pills>li>a.active').text(),
            exportButtons: false
        });
        var expData = temp.getExportData()[idTbl]['xls'];
        temp.export2file(expData.data, expData.mimeType, expData.filename, expData.fileExtension);
    });

    $('#checking').click(function () {
        location.replace('/datareim');
    });

    /*$("#custompdf").click(function() {
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
        doc.text("Laporan " + $('#q').val() + " - (" + $('#tanggal1').val() + " - " + $('#tanggal2').val() + ")", 5,7);
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
    });*/
</script>
@stop