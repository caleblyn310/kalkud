@extends('layouts/app')

@section('content')
    @include('_partial.flash_message')
    <div class="container table-responsive">
        <div class="row">
            <div class="tomb text-right btn-group-vertical" style="width: 80px; height: auto;">
                <a href="uangmuka/create" class="btn btn-sm btn-info btn-block">ADD</a>
            </div>
                
        @if (!empty($um_list))
            <table class="table table-striped table-bordered table-hover table-condensed table-sm" id="tblKaskcl">
                <caption style="caption-side: top;color: #171717;">Daftar Transaksi UANG MUKA</caption>
                <thead><tr>
                    <th>Tanggal</th><th style="width: 72%;">Deskripsi</th><th>Nominal</th><th style="width: 77px;">Action</th>
                </tr></thead>
                <tbody>
                <?php $total = 0 ?>
                <?php foreach ($um_list as $um) : ?>
                <tr>
                    <td>{{ $um->dot->format('d-m-Y') }}</td>
                    <td style="text-align: left;">{{ $um->description }}</td>
                    <td style="text-align: right">{{ number_format($um->nominal,2,",",".") }}</td>
                    <?php $total += $um->nominal ?>
                    <td>
                        <div class="box-button">{{link_to('uangmuka/'.$um->id.'/edit','',['class'=>'btn btn-warning btn-sm fa fa-pencil-square-o'])}}</div>
                        <div class="box-button">{!! Form::open(['method'=>'DELETE', 'action'=>['UMController@destroy',$um->id]]) !!}
                            {!! Form::button('', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm fa fa-trash',
                            'onclick'=> 'return confirm("Yakin nih mau dihapus??")']) !!}{!! Form::close() !!}</div>
                    </td>
                </tr>
                <?php endforeach ?>
                <tr><td colspan="7">
                    {!! $um_list->links('vendor.pagination.bootstrap-4') !!}
                </td></tr>
                </tbody>
            </table>
        @else
            <p>Tidak ada data transaksi UANG MUKA yang sudah di input</p>
        @endif
    </div>
@endsection

@section('css')
<style type="text/css">
	body {
		background-image: url('blue-heart.jpeg');
        background-size: 150px 150px !important;
        background-repeat: repeat !important;
	}
</style>
@endsection