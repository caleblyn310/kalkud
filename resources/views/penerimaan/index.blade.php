@extends('layouts/app')

@section('content')
	@include('_partial.flash_message')
    <div class="container table-responsive">
        <div class="row" style="margin-top: 25px;">
        <div class="col-6">
        <div class="d-flex p-2 flex-column bg-secondary text-white">
            <div class="p-3 bg-info">
        @if (!empty($daftar_penerimaan))
        <h6><strong>Daftar Penerimaan Kantin</strong></h6>
            <table class="table table-striped table-bordered table-hover table-condensed table-sm" id="tblKaskcl">
                <thead><tr>
                    <th style="width: 100px;">Tanggal</th><th>Nominal</th><th style="width: 77px;">Action</th>
                </tr></thead>
                <tbody>
                <?php $total = 0 ?>
                <?php foreach ($daftar_penerimaan as $dp) : ?>
                <tr>
                    <td>{{ $dp->dot->format('d-M-Y') }}</td>
                    <td style="text-align: right">{{ number_format($dp->nominal,2,",",".") }}</td>
                    <td>@if ($dp->status == 's')
                        <div class="box-button">{!! Form::button('',['type' => 'button', 'id' => 'btnEdit', 'class'=>'btn btn-warning btn-sm fa fa-pencil-square-o', 'data-id' => $dp->id]) !!}</div>
                        <div class="box-button">{!! Form::open(['method'=>'DELETE', 'action'=>['PenerimaanController@destroy',$dp->id]]) !!}
                            {!! Form::button('', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm fa fa-trash',
                            'onclick'=> 'return confirm("Apakah Anda yakin mau dihapus??")']) !!}{!! Form::close() !!}</div>
                        @endif
                    </td>
                </tr>
                <?php endforeach ?>
                <tr><td colspan="7">
                    {!! $daftar_penerimaan->links('vendor.pagination.bootstrap-4') !!}
                </td></tr>
                </tbody>
            </table>
        @else
            <p>Tidak ada data kas kecil yang belum di upload</p>
        @endif
        </div>
    </div></div>

    <div class="col-6">
        <div class="d-flex p-2 flex-column bg-secondary">
            <div class="p-3 bg-warning">
                {!! Form::open(['url'=>'penerimaan','class'=>'']) !!}
                <input type="hidden" value="0" id="idMode" name="idMode">
                <h6><strong>Input Penerimaan</strong></h6>
                @if (Session::has('flash_message'))
                    <input type="hidden" value="{{ Session::get('flash_message') }}" id="msg">
                @endif

                <div class="row">
                    <div class="col-lg-9">
                    @if ($errors->any())
                        <div class = "form-group row {{ $errors->has('dot') ? 'has-error' : 'has-success' }}">
                    @else
                        <div class="form-group row">
                    @endif
                    <label class="col-lg-3 col-md-6 col-sm-12 form-control-label"><b>Tanggal</b></label>
                    <div class="col-lg-9 col-sm-12">
                        {!! Form::date('dot',!empty($pembelian) ? $pembelian->dot->format('Y-m-d') : date('2019-12-01')
                        ,['class'=>'form-control-sm form-control','required'=>'']) !!}</div>
                    </div>

                    <div class="form-group row">
                    <label class="col-lg-3 col-md-6 col-sm-12 form-control-label"><b>Nominal</b></label>
                    <div class="col-lg-9 col-sm-12">
                        {!! Form::text('nominal',null,['class'=>'form-control form-control-sm','maxlength'=>'10']) !!}
                        <!--<p class="errorNominal text-center alert alert-danger hidden"></p>-->
                    </div>
                    </div>
                    </div>

                    <div class="col-lg-2"><button type="submit" class="btn btn-sm btn-info" id="btnSimpan"><b>Simpan</b></button></div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
    </div>
@stop

@section('scripts')
<script type="text/javascript">
    $('#btnEdit')
</script>
@stop