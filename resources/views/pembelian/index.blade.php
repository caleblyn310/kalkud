@extends('layouts/app')

@section('content')
	@include('_partial.flash_message')
    <div class="container table-responsive">
        <div class="row">
            <div class="tomb text-right btn-group-vertical" style="width: 80px; height: auto;">
                <a href="pembelian/create" class="btn btn-sm btn-info btn-block">ADD</a>
            </div>
                
        @if (!empty($daftar_pembelian))
            <table class="table table-striped table-bordered table-hover table-condensed table-sm" id="tblKaskcl">
                <caption style="caption-side: top;color: white;"><strong>Daftar Pembelian Kantin</strong></caption>
                <thead><tr>
                    <th>Tanggal</th><th>Supplier</th><th>Inv. No</th><th>Nominal</th><th style="width: 110px;">Action</th>
                </tr></thead>
                <tbody>
                <?php $total = 0 ?>
                <?php foreach ($daftar_pembelian as $dp) : ?>
                <tr>
                    <td>{{ $dp->dot->format('d-m-Y') }}</td>
                    <td>{{ $dp->supplier }}</td>
                    <td>{{ $dp->invoices_no }}</td>
                    <td style="text-align: right">{{ number_format($dp->nominal,0,",",".") }}</td>
                    <td>
                        @if ($dp->status == 's')
                        <div class="box-button">{{link_to('pembelian/'.$dp->id.'/edit','',['class'=>'btn btn-warning btn-sm fa fa-pencil-square-o'])}}</div>
                        <div class="box-button">{!! Form::open(['method'=>'DELETE', 'action'=>['PembelianController@destroy',$dp->id]]) !!}
                            {!! Form::button('', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm fa fa-trash',
                            'onclick'=> 'return confirm("Yakin nih mau dihapus??")']) !!}{!! Form::close() !!}</div>
                        <div class="box-button">{{link_to('pembelian/'.$dp->id.'/lock','',['class'=>'btn btn-primary btn-sm fa fa-lock'])}}</div>
                        @else
                        <div class="box-button"><span class="badge badge-pill badge-success" style="background-color: lightred;">(locked)</span></div>
                        @endif
                    </td>
                </tr>
                <?php endforeach ?>
                <tr><td colspan="7">
                    {!! $daftar_pembelian->links('vendor.pagination.bootstrap-4') !!}
                </td></tr>
                </tbody>
            </table>
        @else
            <p>Tidak ada data kas kecil yang belum di upload</p>
        @endif
    </div>
@stop