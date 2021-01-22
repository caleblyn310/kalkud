@extends('layouts.app')

@section('content')
    <div class="container table-responsive" >
        @if(Auth::user()->kode_unit == 100)
            @if(!empty($reimburse_detail))
                <table class="table table-striped table-bordered table-hover table-condensed table-sm" id="tblReim">
                    <caption style="caption-side: top; font-weight: bold; color: black;">Daftar transaksi kas kecil yang sedang di cek
                        <button class="btn btn-sm btn-warning" id="btnBack">Back</button>
                        <button class="btn btn-sm btn-info" id="convertExcel"><i class="fa fa-arrow-right"></i> Excel</button></caption>
                    <thead>
                        <tr><th>Tanggal</th><th>Kode D-Ger</th><th>Sub<br>Kode</th><th>No BPU</th><th>Deskripsi</th><th style="width: 10%;">Nominal</th></tr>
                    </thead>
                    <tbody>
                        <?php $total = 0?>
                        <?php foreach ($reimburse_detail as $rd) : ?>
                        <tr>
                            <td>{{ Carbon\Carbon::parse($rd->tanggal_trans)->format('d-m-Y') }}</td>
                            <td>{{ $rd->kode_d_ger }}</td>
                            <td>{{ $rd->subkode }}</td>
                            <td>{{ $rd->no_bukti }}</td>
                            <td style="text-align: left;">{{ $rd->deskripsi }}</td>
                            <td style="text-align: right;">{{ number_format($rd->nominal,0,".",",") }}</td>
                            <?php $total += $rd->nominal?>
                        </tr>
                        <?php endforeach?>
                        <tr class="table-success"><td colspan="5" style="text-align: right"><b>Total: </b></td>
                    <td style="text-align: right"><b>{{ number_format($total,0,".",",") }}</b></td></tr>
                    </tbody>
                </table>
            @endif
        @else
        @include('_partial.flash_message')
        <div class="tomb text-right"><a href="{{ url('mpdf/'.$namafile) }}" class="btn btn-danger btn-sm" onclick="return confirm('Sudah yakin mau Reimburse??')">Request</a></div>
        @if (!empty($kaskecil_list))
            <table class="table table-striped table-bordered table-hover table-condensed table-sm">
                <caption style="caption-side: top;color: #171717"><strong>Daftar Kas Kecil yang Akan Di Edit</strong></caption>
                <thead><tr>
                    <th>Tanggal</th><th>Kode D-Ger</th><th>Sub<br>Kode</th><th>No BPU</th><th>Deskripsi</th><th>Nominal</th><th style="width:90px;">Action</th>
                </tr></thead>
                <tbody>
                <?php $total = 0?>
                <?php foreach ($kaskecil_list as $kaskecil) : ?>
                <tr>
                    <td>{{ Carbon\Carbon::parse($kaskecil->tanggal_trans)->format('d-m-Y') }}</td>
                    <td>{{ $kaskecil->kode_d_ger }}</td>
                    <td>{{ $kaskecil->subkode }}</td>
                    <td>{{ $kaskecil->no_bukti }}</td>
                    <td style="text-align: left;">{{ $kaskecil->deskripsi }}</td>
                    <td style="text-align: right;">{{ number_format($kaskecil->nominal,0,",",".") }},00</td>
                    <?php $total += $kaskecil->nominal ?>
                    <td>
                        <div class="box-button">{{ link_to('datareim/'.$namafile.'z'.$kaskecil->id.'z/edit','',['class'=> 'btn btn-warning btn-sm fa fa-pencil-square-o']) }}</div>
                        <div class="box-button">{!! Form::open(['method'=>'DELETE', 'action'=>['DatareimController@destroy',$kaskecil->id]]) !!}
                            {!! Form::hidden('nv',$namafile) !!}
                            {!! Form::button('',['type' => 'submit', 'class' => 'btn btn-danger btn-sm fa fa-trash',
                            'onclick'=> 'return confirm("Apakah Bapak/Ibu yakin mau hapus??")']) !!} {!! Form::close() !!}</div>
                    </td>
                </tr>
                <?php endforeach ?>
                <tr class="table-success"><td colspan="5" style="text-align: right">Total</td>
                    <td style="text-align: right"><b>Rp. {{ number_format($totalreim,0,"",".") }},00</b></td></tr>
                </tbody>
            </table>
        @endif
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
<script type="text/javascript">
    $("#btnBack").click(function() {
        location.replace("/datareim");
    });

    $("#convertExcel").click(function() {
        var tbl = document.getElementById('tblReim');
        var temp = new TableExport(tbl, {
            formats: ['xls'],
            filename: 'Requested KK',
            exportButtons: false
        });
        var expData = temp.getExportData()['tblReim']['xls'];
        temp.export2file(expData.data, expData.mimeType, expData.filename, expData.fileExtension);
    });
</script>
@endsection