
<div class="form-group row">
    {!! Form::label('description_edit','Description',['class'=>'col-4 form-control-label']) !!}
    <div class="col-8">
        {!! Form::text('description_edit',null,['class'=>'form-control','placeholder'=>'Description']) !!}
        <!--<p class="errorDescription text-center alert alert-danger hidden"></p>-->
    </div>
</div>

<div class="form-group row">
    {!! Form::label('kode_d_ger_edit','Kode COA',['class'=>'col-sm-12 col-md-6 col-lg-4 form-control-label']) !!}
    <div class="col-lg-8">
        {!! Form::text('q_edit', '', ['class'=>'form-control','id' =>  'q_edit', 'placeholder' =>  'Find d-ger code',
        'autofocus'=>'']) !!}
        {!! Form::text('kode_d_ger_edit',null,['class'=>'form-control','placeholder'=>'KODE D-Ger',
        'required'=>'','maxlength'=>'10','pattern'=>'\d{3}(.)\d{2}(.)\d{3}','readonly'=>'']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('nominal_edit','Nominal',['class'=>'col-4 form-control-label']) !!}
    <div class="col-8">
        {!! Form::text('nomedit',null,['class'=>'form-control','placeholder'=>'Nominal','maxlength'=>'10','id'=>'nomedit']) !!}
        <!--<p class="errorNominal text-center alert alert-danger hidden"></p>-->
    </div>
</div>