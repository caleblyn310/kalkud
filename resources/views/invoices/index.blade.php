@extends('layouts/app')

@section('content')
<div class="container table-responsive">@include('_partial.flash_message')
    <div class="tomb">
        <a href="invoices/create" class="btn btn-sm btn-warning float-right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ADD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
    </div>
    @if (!empty($invoices_list))
        <table class="table table-striped table-bordered table-hover table-condensed table-sm">
            <caption style="caption-side: top;color: black;"><strong>Bank Out Transaction List</strong></caption>
            <thead><tr>
                <th>Date</th><th>Bank</th><th>Trans. No</th><th>To</th><th>Nominal</th><th style="width: 115px;">Action</th>
            </tr></thead>
            <tbody>
            <?php $total = 0 ?>
            <?php foreach ($invoices_list as $invoices) : ?>
            <tr>
                <td>{{ $invoices->dot->format('d-m-Y') }}</td>
                <td>{{ App\Bank::find($invoices->bank)->name }}</td>
                <td>{{ $invoices->invoices_no }}</td>
                <td>{{ $invoices->pay_to }}</td>
                <td style="text-align: right">{{ number_format($invoices->nominal,2,".",",") }}</td>
                <td>
                    @if ($invoices->status == 's')
                    @if (Auth::user()->kode_unit == 100)
                    <div class="box-button">{{link_to('invoices/'.$invoices->id.'/edit','',['class'=>'btn btn-warning btn-sm fa fa-pencil-square-o'])}}</div>
                    <div class="box-button">{!! Form::open(['method'=>'DELETE', 'action'=>['InvoicesController@destroy',$invoices->id]]) !!}
                        {!! Form::button('', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm fa fa-trash',
                        'onclick'=> 'return confirm("Are you sure you want to delete it??")']) !!}{!! Form::close() !!}</div>
                    <button type='button' class="printdm btn btn-info btn-sm fa fa-print" data-id="{{$invoices->id}}" data-st="print"></button>
                    @else
                    <button type='button' class="showinv btn btn-info btn-sm fa fa-eye" data-id="{{$invoices->id}}" data-st="show"></button>
                    @endif
                    @else
                    <button type='button' class="printdm btn btn-info btn-sm fa fa-print" data-id="{{$invoices->id}}" data-st="reprint"> Reprint</button>
                    @endif
                    <!--<div class="box-button">{{ link_to('invoices/'.$invoices->id.'/print','',['class'=>'btn btn-primary btn-sm fa fa-print'])}}</div>-->
                    
                </td>
            </tr>
            <?php endforeach ?>
            <tr><td colspan="7">
                    {!! $invoices_list->links('vendor.pagination.bootstrap-4') !!}
                </td></tr>
            </tbody>
        </table>
    @else
        <p>Tidak ada data invoices yang belum di upload</p>
    @endif            
</div>
@stop

@section('scripts')
<script src="{{ asset('js/webprint.js') }}"></script>
<script>
    webprint = new WebPrint(true, {
            relayHost: "127.0.0.1",
            relayPort: "8080",
            readyCallback: function(){
                
            }
        });

    /*$(function(){
        $.ajax({
            url: "http://kaskecil.app/",
            async: false,
            cache: false,
            dataType: "json",
            success: function (data, textStatus, jq) {
                buffer = "";
                buffer = data.string;
            }
        });
    });*/

    /*$(document).on('click', '.edit-modal', function() {
            $('.modal-title').text('Edit Description');
            $('#description_edit').val($(this).data('title'));
            $('#nominal_edit').val($(this).data('content'));
            id = $(this).data('id');
            nom_edit = $(this).data('content');
            $('#editModal').modal('show');
        });*/
    $(document).on('click', '.showinv', function() {
        var id = $(this).data('id');
        location.replace("http://localhost/invoices/"+id);
    });

    $(document).on('click', '.printdm', function() {
        //alert($(this).data('id'));
        var id = $(this).data('id');
        var st = $(this).data('st');
        $.ajax({
            type: 'GET',
            url: '/invoices/' + id + '/print',
            cache: false,
            dataType: "text",
            success: function(data, textStatus, jq) {
                var r = confirm(data);
                //for(i = 1;i<3;i++)
                if (r == true) {
                    webprint.printRaw(data, 'lx300');
                    toastr.success('Successfully printed!', 'Success Alert', {timeOut: 2500});
                    location.reload(); }
                else {
                    if(st == "print") {
                        $.ajax({
                            type: 'GET',
                            url:'/cancelprint/' + id + 'inv',
                            cache: false,
                            dataType: "text",
                            success: function(dt, textStatus, jq) {
                                toastr.warning('Printing cancelled!', 'Alert', {timeOut: 2500});
                                location.reload();
                            }
                        });
                    }
                }
            }
        });
    });
</script>
@stop