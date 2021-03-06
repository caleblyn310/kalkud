@extends('layouts.app')

@section('content')
    <div class="container">
        @include('_partial.flash_message')<br>
        {!! Form::open(['url'=>'search/transaction','method'=>'GET', 'class'=>'']) !!}
        <div class="form-group row" style="background-color: #eef9fd; padding: 10px 0px 10px 0px;">
            <strong class="col-md-2">{!! Form::label('no_bukti','Search Inv. No.',['class'=>'form-control-label','style'=>'color:black;']) !!}</strong>
            <div class="col-md-10 form-inline">
                {!! Form::text('No_bukti',null,['class'=>'form-control form-control-sm','placeholder'=>'No Bukti yang ingin dicari','required'=>'','maxlength'=>'150','id'=>'qsearch']) !!}&nbsp;&nbsp;&nbsp;{!! Form::submit('Search',['class'=>'btn btn-sm btn-primary']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    @if(!empty($ja))
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-body" style="background-color: #d5f4e6;">
                    <strong>Transaction Detail</strong><hr>
                    <table class="table table-striped table-sm">
                        <tr>
                            <td>Tanggal</td>
                            <td>{{ Carbon\Carbon::parse($ja->Tanggal)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td>No Account</td>
                            <td>{{ $ja->No_account }}</td>
                        </tr>
                        <tr>
                            <td>No Bukti</td>
                            <td>{{ $ja->No_bukti }}</td>
                        </tr>
                        <tr>
                            <td>Deskripsi</td>
                            <td>{{ $ja->Uraian }}</td>
                        </tr>
                        <tr>
                            <td>Debit</td>
                            <td>{{ number_format($ja->Debet,'2','.',',') }}</td>
                        </tr>
                        <tr>
                            <td>Kredit</td>
                            <td>{{ number_format($ja->Kredit,'2','.',',') }}</td>
                        </tr>
                        <tr>
                            <td>Kontra Account</td>
                            <td>{{ $ja->Kontra_acc }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="box-button">{{link_to('jurnaladmin/'.$ja->id.'/edit',' Edit',['class'=>'btn btn-warning btn-sm fa fa-pencil-square-o'])}}</div>
                        <div class="box-button">{!! Form::open(['method'=>'DELETE', 'action'=>['JurnalController@destroy',$ja->id]]) !!}
                            {!! Form::button(' Delete', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm fa fa-trash',
                            'onclick'=> 'return confirm("Yakin nih mau dihapus??")']) !!}{!! Form::close() !!}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('scripts')
<script type="text/javascript">
    $( "#qsearch" ).autocomplete({
        source: "http://"+location.hostname+"/search/jurnalautocomplete",
        minLength: 3,
        select: function(event, ui) {
            $('#qsearch').val(ui.item.value);
            //$('#kode_d_ger').val(ui.item.id);
        }
    });
</script>
@endsection