<div class="form-group row">
    {!! Form::label('description_del','Description',['class'=>'col-4 form-control-label']) !!}
    <div class="col-8">
        {!! Form::text('description_del',null,['class'=>'form-control','placeholder'=>'Description','readonly'=>'']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('nominal_del','Nominal',['class'=>'col-4 form-control-label']) !!}
    <div class="col-8">
        {!! Form::text('nominal_del',null,['class'=>'form-control','placeholder'=>'Nominal','maxlength'=>'10','readonly'=>'']) !!}
    </div>
</div>