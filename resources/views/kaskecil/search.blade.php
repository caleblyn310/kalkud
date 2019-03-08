@extends('layouts.app')

@section('content')
    <div class="container">
        @include('_partial.flash_message')
        <div class="row" style="padding-top: 25px;">
            <div class="col-12">
                {!! Form::open(['url'=>'searchtransaction','method'=>'GET', 'class'=>'']) !!}
                <div class="input-group mb-3">
                    {!! Form::hidden('id',null,['id'=>'idkaskecil']) !!}
                    
                        <span class="input-group-addon" ><i class="fa fa-search"></i></span>
                    
                    {!! Form::text('No_bukti',null,['class'=>'form-control form-control-sm border-right-0 border-left-0','placeholder'=>'Silakan input nomor BPU atau keterangan transaksi kaskecil yang ingin dicari...','required'=>'','maxlength'=>'150','id'=>'qsearch']) !!}
                    
                        {!! Form::submit('Show Detail',['class'=>'btn btn-sm btn-outline-success']) !!}
                    
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    
    @if(!empty($kaskecil))
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-body" style="background-color: #d5f4e6;">
                    Detail Transaksi<hr>
                    <table class="table table-striped">
                        <tr>
                            <td>Tanggal BPU</td>
                            <td>{{ $kaskecil->tanggal_trans->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td>Kode D-Ger</td>
                            <td>{{ $kaskecil->kode_d_ger }}</td>
                        </tr>
                        <tr>
                            <td>Subkode</td>
                            <td>{{ $kaskecil->subkode }}</td>
                        </tr>
                        <tr>
                            <td>No Bukti</td>
                            <td>{{ $kaskecil->no_bukti }}</td>
                        </tr>
                        <tr>
                            <td>Deskripsi</td>
                            <td>{{ $kaskecil->deskripsi }}</td>
                        </tr>
                        <tr>
                            <td>Nominal</td>
                            <td>{{ number_format($kaskecil->nominal,'2','.',',') }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                @if( $kaskecil->status == 'bu' )
                                <div class="box-button">{{link_to('kaskecil/'.$kaskecil->id.'/edit',' Edit',['class'=>'btn btn-warning btn-sm fa fa-pencil-square-o'])}}</div>
                        <div class="box-button">{!! Form::open(['method'=>'DELETE', 'action'=>['KaskecilController@destroy',$kaskecil->id]]) !!}
                            {!! Form::button(' Delete', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm fa fa-trash',
                            'onclick'=> 'return confirm("Yakin nih mau dihapus??")']) !!}{!! Form::close() !!}</div>
                                @endif
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
        source: "http://"+location.hostname+"/searchkaskecil",
        minLength: 3,
        select: function(event, ui) {
            $('#qsearch').val(ui.item.value);
            $('#idkaskecil').val(ui.item.id);
        }
    });
</script>
@endsection