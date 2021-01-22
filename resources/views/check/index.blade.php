@extends('layouts/app')

@section('content')
<div class="container table-responsive">@include('_partial.flash_message')
    @if(Auth::user()->kode_unit != 100)
    <div class="col-lg-6 col-md-6 col-sm-12 text-right tomb">
        <a href="cheque/create" class="btn btn-sm btn-primary ">&nbsp;&nbsp;&nbsp;ADD&nbsp;&nbsp;&nbsp;</a></div>
    @endif
    
    @if (!empty($check_list))
    <table class="table table-striped table-bordered table-hover table-condensed table-sm">
        <caption style="caption-side: top; color: black;"><strong>List of Cheque</strong></caption>
        <thead><tr>
            <th>Date</th><th>No. Cheque</th><th>Reimburse Data</th>@if(Auth::user()->kode_unit == 100)<th>Unit</th>@endif<th>Nominal</th><th style="width: 150px;">Action</th>
        </tr></thead>
        <tbody>
        <?php $total = 0 ?>
        <?php foreach ($check_list as $check) : ?>
        <tr>
            <td>{{ Carbon\Carbon::parse($check->tanggal_cair)->format('d-m-Y') }}</td>
            <td>{{ $check->no_check }}</td>
            <td>{{ $check->data_reimburse }}</td>
            @if (Auth::user()->kode_unit == 100)
            <td>{{ App\KodeUnit::find($check->kode_unit)->middle }}</td>
            @endif
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
                    <div class="box-button">{{ link_to('http://kalamkudus.or.id/kaskecil/storage/'.str_replace('reimburse', 'reimburse', $check->data_reimburse),'',['class'=>'btn btn-info btn-sm fa fa-print','target'=>'_blank']) }}</div>
                    <div class="box-button">{{ link_to('exportExcel/'.$check->id,'',['class'=>'btn btn-success btn-sm fa fa-table']) }}</div>
            @endif
            </td>
        </tr>
        <?php endforeach ?>
        @if (Auth::user()->kode_unit == 1000)
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

@section('scripts')
<script type="text/javascript">
    /*$('.showDetail').click(function() {
        $.ajax({
            type: 'GET',
            url: '/cheque/' + $(this).data('id'),
            cache: false,
            success: function(data) {
                console.log(data);
            }
        })
    });*/
</script>
@stop