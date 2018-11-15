@extends('layouts/app')

@section('content')
<div class="container table-responsive">@include('_partial.flash_message')
    <div class="row" style="padding-top: 25px;">
    <h4 class="col-lg-6 col-md-6 col-sm-12">Daftar Cheque</h4>
    @if(Auth::user()->kode_unit != 100)
    <div class="col-lg-6 col-md-6 col-sm-12 float-rigt">
        <a href="cheque/create" class="btn btn-sm btn-primary float-right">Tambah Data Cheque</a></div>
    </div>
    @endif
    @if(Auth::user()->kode_unit == 100)
    <div class="col-lg-6 col-md-6 col-sm-12 float-rigt">
        <a href="{{ asset('downloadbpb') }} " class="float-right btn btn-sm btn-primary">Download BPB</a></div>
    </div>
    @endif
    
    @if (!empty($check_list))
    <table class="table table-striped table-bordered table-hover table-condensed table-sm">
        <thead><tr>
            <th>Tanggal</th><th>No Cheque</th><th>Data Reimburse</th><th>Nominal</th><th>Action</th>
        </tr></thead>
        <tbody>
        <?php $total = 0 ?>
        <?php foreach ($check_list as $check) : ?>
        <tr>
            <td>{{ $check->tanggal_cair->format('d-m-Y') }}</td>
            <td>{{ $check->no_check }}</td>
            <td>{{ $check->data_reimburse }}</td>
            <td style="text-align: right">{{ number_format($check->nominal,0,",",".") }}</td>
            <td>
            @if ($check->mode == 'print')
                <div class="box-button">{{ link_to('cheque/'.$check->id.'/edit','',['class'=> 'btn btn-warning btn-sm fa fa-pencil-square-o']) }}</div>
                <div class="box-button">{!! Form::open(['method'=>'DELETE', 'action'=>['ChequeController@destroy',$check->id]]) !!}
                    {!! Form::button('',['type' => 'submit', 'class' => 'btn btn-danger btn-sm fa fa-trash',
                    'onclick'=> 'return confirm("Yakin nih mau dihapus??")']) !!} {!! Form::close() !!}</div>
                <div class="box-button">{{ link_to('mpdf/'.$check->id,'',['class'=>'btn btn-info btn-sm fa fa-table']) }}</div>
            @else
                @if (Auth::user()->kode_unit == 100 && $check->mode == 'final')
                <div class="box-button">{{ link_to('cheque/'.$check->id.'/edit','',['class'=> 'btn btn-warning btn-sm fa fa-pencil-square-o']) }}</div>
                <div class="box-button">{{ link_to('ja/'.$check->id,'',['class'=> 'btn btn-danger btn-sm fa fa-floppy-o', 'id'=>'ja','onclick'=> 'return confirm("Apakah sudah yakin mau simpan data??")']) }}</div>
                @elseif (Auth::user()->kode_unit == 100 && $check->mode == 'saved')
                Saved
                @endif
                    <div class="box-button">{{ link_to('storage/'.str_replace('reimburse', 'laporan', $check->data_reimburse),'',
                    ['class'=>'btn btn-info btn-sm fa fa-print','target'=>'_blank']) }}</div>
                    <div class="box-button">{{ link_to('exportExcel/'.$check->id,'',['class'=>'btn btn-success btn-sm fa fa-table']) }}</div>
            @endif
            </td>
        </tr>
        <?php endforeach ?>
        @if (Auth::user()->kode_unit == 100)
            <tr><td colspan="5">
                {!! $check_list->links('vendor.pagination.bootstrap-4') !!}
            </td></tr>
        @endif
        </tbody>
        </table>
    @else
        <p>Tidak ada data check</p>
    @endif
    </div>
@stop