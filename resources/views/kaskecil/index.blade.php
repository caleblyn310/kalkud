@extends('layouts/app')

@section('content')
    <div class="container table-responsive">
        <div class="row" style="padding-top: 25px;">
        <h5 class="float-left col">Daftar Kas Kecil yang Belum Di-Upload</h5>
        <h5 class="col" style="text-align: right;">Plafon: Rp. {{ number_format($plafon,0,'','.') }},00</h5></div>
        @include('_partial.flash_message')
        <hr>
        <div class="row">
            <div class="col-6 tomb" style=" height: 33px;">
                <a href="create" class="btn btn-sm btn-primary">Tambah</a>
                @if($kaskecil_list->count() > 0)
                    <a href="mpdf/req" class="btn btn-sm btn-danger" onclick="return confirm('Sudah yakin mau reimburse??')">Request Reimburse</a>
                @endif
            </div>
                <div class="col-6 float-right">
                <h6 style="text-align: right; padding-top: 7px; height: 33px;">
                Total Reimburst: {{ number_format($totalreim,0,"",".") }} | Sisa Saldo: {{ number_format($plafon-$totalreim,0,"",".") }}</h6>
            </div></div>
        @if (!empty($kaskecil_list))
            <table class="table table-striped table-bordered table-hover table-condensed table-sm">
                <thead><tr>
                    <th>Tanggal</th><th>Kode D-Ger</th><th>Sub<br>Kode</th><th>No BPU</th><th>Deskripsi</th><th>Nominal</th><th style="width: 77px;">Action</th>
                </tr></thead>
                <tbody>
                <?php $total = 0 ?>
                <?php foreach ($kaskecil_list as $kaskecil) : ?>
                <tr>
                    <td>{{ $kaskecil->tanggal_trans->format('d-m-Y') }}</td>
                    <td>{{ $kaskecil->kode_d_ger }}</td>
                    <td>{{ $kaskecil->subkode }}</td>
                    <td>{{ $kaskecil->no_bukti }}</td>
                    <td style="text-align: left;">{{ $kaskecil->deskripsi }}</td>
                    <td style="text-align: right">{{ number_format($kaskecil->nominal,0,",",".") }}</td>
                    <?php $total += $kaskecil->nominal ?>
                    <td>
                        <div class="box-button">{{link_to('kaskecil/'.$kaskecil->id.'/edit','',['class'=>'btn btn-warning btn-sm fa fa-pencil-square-o'])}}</div>
                        <div class="box-button">{!! Form::open(['method'=>'DELETE', 'action'=>['KaskecilController@destroy',$kaskecil->id]]) !!}
                            {!! Form::button('', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm fa fa-trash',
                            'onclick'=> 'return confirm("Yakin nih mau dihapus??")']) !!}{!! Form::close() !!}</div>
                    </td>
                </tr>
                <?php endforeach ?>
                <tr><td colspan="7">
                    {!! $kaskecil_list->links('vendor.pagination.bootstrap-4') !!}
                </td></tr>
                </tbody>
            </table>
        @else
            <p>Tidak ada data kas kecil yang belum di upload</p>
        @endif            
    </div>
@stop