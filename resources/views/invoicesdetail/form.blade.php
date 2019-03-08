
<div class="form-group row">
    {!! Form::label('description','Description',['class'=>'col-4 form-control-label']) !!}
    <div class="col-8">
        {!! Form::text('description_add',null,['class'=>'form-control', 'id'=>'description_add', 'placeholder'=>'Description']) !!}
        <!--<p class="errorDescription text-center alert alert-danger hidden"></p>-->
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-12 col-md-6 col-lg-4">
    {!! Form::label('kode_d_ger','Kode COA',['class'=>' form-control-label']) !!}<br>
    <button class="btn btn-sm btn-default btnReset" type="button">Reset COA</button>
    </div>
    <div class="col-lg-8">
        {!! Form::text('q_add', '', ['class'=>'form-control','id' =>  'q_add', 'placeholder' =>  'Find d-ger code',
        'autofocus'=>'']) !!}
        {!! Form::text('kode_d_ger_add',null,['class'=>'form-control', 'id'=>'kode_d_ger_add', 'placeholder'=>'KODE D-Ger',
        'required'=>'','maxlength'=>'10','pattern'=>'\d{3}(.)\d{2}(.)\d{3}','readonly'=>'']) !!}

        @if ($errors->has('kode_d_ger'))
            <span class="help-block">{{ $errors->first('kode_d_ger') }}</span>@endif</div>
</div>

<div class="form-group row">
    {!! Form::label('nominal','Nominal',['class'=>'col-4 form-control-label']) !!}
    <div class="col-8">
        {!! Form::text('nominal',null,['class'=>'form-control','placeholder'=>'Nominal','maxlength'=>'10']) !!}
        <!--<p class="errorNominal text-center alert alert-danger hidden"></p>-->
    </div>
</div>