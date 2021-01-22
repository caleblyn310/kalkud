@extends('layouts.app')

@section('content')
    <div class="container" style="padding-top: 25px">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body">
                <h4>Edit Kas Kecil</h4><hr>
                {!! Form::model($kaskecil,['method'=>'PATCH', 'action'=>['DatareimController@update',$kaskecil->id]]) !!}
                    {!! Form::hidden('nv',$namafile) !!}

                @if ($errors->any())
                    <div class = "form-group row {{ $errors->has('tanggal_trans') ? 'has-error' : 'has-success' }}">
                @else
                    <div class="form-group row">
                @endif
                    {!! Form::label('tanggal_trans','Tanggal BPU',['class'=>'col-md-3 form-control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::date('tanggal_trans',
                        !empty($kaskecil) ? Carbon\Carbon::parse($kaskecil->tanggal_trans) : null,
                        ['class'=>'form-control form-control-sm','required'=>'']) !!}
                    </div>
                    @if($errors->has('tanggal_trans'))
                        <span class="help-block">{{ $errors->first('tanggal_trans') }}</span>@endif
                </div>

                    <div class="form-group row">
                    {!! Form::label('kode_d_ger','Kode D-Ger',['class'=>'col-md-3 form-control-label']) !!}

                    <div class="col-md-8">
                        {!! Form::text('q', '', ['class'=>'form-control form-control-sm','id' =>  'q', 'placeholder' =>  'Cari kode d-ger',
                        'autofocus'=>'']) !!}
                        {!! Form::text('kode_d_ger',null,['class'=>'form-control form-control-sm','placeholder'=>'KODE D-Ger',
                        'required'=>'','maxlength'=>'10','readonly'=>'']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('subkode','Sub Kode',['class'=>'col-md-3 form-control-label']) !!}

                    <div class="col-md-8">
                        {!! Form::text('subkode',null,['class'=>'form-control form-control-sm','placeholder'=>'Sub KODE','maxlength'=>'2']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('no_bukti','No BPU',['class'=>'col-md-3 form-control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('no_bukti',null,['class'=>'form-control form-control-sm','placeholder'=>'Awali dengan KA atau KK','required'=>'',
                        'maxlength'=>'10','pattern'=>'K(A|K|B)\d{5,}']) !!}
                        @if ($errors->has('no_bukti'))
                            <span class="help-block">{{ $errors->first('no_bukti') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('deskripsi','Deskripsi',['class'=>'col-md-3 form-control-label']) !!}

                    <div class="col-md-8">
                        {!! Form::text('deskripsi',null,['class'=>'form-control form-control-sm','placeholder'=>'Deskripsi','required'=>'']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('nominal','Nominal',['class'=>'col-md-3 form-control-label']) !!}

                    <div class="col-md-8">
                        {!! Form::text('nominal',null,['class'=>'form-control form-control-sm','placeholder'=>'Sisa Saldo: '. $sisaSaldo,
                        'required'=>'']) !!}&nbsp;<span id="errmsg"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-sm btn-primary" id="btnSave">SAVE</button>
                        <a href="{{ asset('datareim/'.$namafile.'/edit') }}" class="btn btn-sm btn-primary">Cancel</a>
                    </div>
                </div>
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script type="text/javascript">
    $('#nominal').keyup(function () {
        var sisaSaldo = {{ $sisaSaldo }};
        var nominal = parseInt($('#nominal').val(), 10);
        if(nominal > sisaSaldo)
            $('#btnSave').prop('disabled',true);
        else
            $('#btnSave').prop('disabled',false);
    });
</script>
@endsection