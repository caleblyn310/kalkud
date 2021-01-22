<div class="form-group row">
    <div class="col-sm-12 col-md-6 col-lg-3">
    {!! Form::label('kode_brg','Kode Barang',['class'=>' form-control-label']) !!}
    </div>
    <div class="col-lg-8">
        {!! Form::text('q_edit', '', ['class'=>'form-control form-control-sm','id' =>  'q_edit', 'placeholder' =>  'Cari berdasarkan nama barang . . .',
        'autofocus'=>'']) !!}</div>
</div>

<div class="form-group row">
    {!! Form::label('kosong','      ',['class'=>'col-3 form-control-label']) !!}
    <div class="col-lg-8">{!! Form::text('kode_brg_edit',null,['class'=>'form-control form-control-sm', 'id'=>'kode_brg_edit', 'placeholder'=>'KODE Barang','required'=>'','readonly'=>'']) !!}</div>
</div>

<div class="form-group row">
    <div class="col">
    <div class="form-group row">
        {!! Form::label('qty','Kuantiti 1',['class'=>'col-3 form-control-label']) !!}
        <div class="col-8">
            {!! Form::text('kuantiti1_edit',null,['class'=>'form-control form-control-sm', 'id'=>'kuantiti1_edit', 'placeholder'=>'Kuantiti 1']) !!}
            <!--<p class="errorDescription text-center alert alert-danger hidden"></p>-->
        </div>
    </div>
    <div class="form-group row">
    {!! Form::label('qty','Kuantiti 2',['class'=>'col-3 form-control-label']) !!}
        <div class="col-8">
            {!! Form::text('kuantiti2_edit',null,['class'=>'form-control form-control-sm', 'id'=>'kuantiti2_edit', 'placeholder'=>'Kuantiti 2']) !!}
            <!--<p class="errorDescription text-center alert alert-danger hidden"></p>-->
        </div>
    </div>
    <div class="form-group row">
    {!! Form::label('nominal','Nominal',['class'=>'col-3 form-control-label']) !!}
    <div class="col-8">
        {!! Form::text('nominal_edit',null,['class'=>'form-control form-control-sm', 'id'=>'nominal_edit', 'maxlength'=>'10']) !!}
        <!--<p class="errorNominal text-center alert alert-danger hidden"></p>-->
    </div>
    </div>
     <div class="form-group row">
    {!! Form::label('diskon','Diskon',['class'=>'col-3 form-control-label']) !!}
    <div class="col-8">
        {!! Form::text('diskon_edit',null,['class'=>'form-control form-control-sm','id'=>'diskon_edit','maxlength'=>'10']) !!}
        <!--<p class="errorNominal text-center alert alert-danger hidden"></p>-->
    </div>
    </div>
    <div class="form-group row">
    <div class="col-3">
    {!! Form::label('hrg_sat','Harga Satuan',['class'=>'form-control-label','style'=>'margin-bottom:1px;']) !!}
    <span class="badge badge-pill badge-success" style="background-color: lightred;">(setelah diskon)</span></div>
    <div class="col-8">
        {!! Form::text('hrg_sat_edit',null,['class'=>'form-control form-control-sm','id'=>'hrg_sat_edit','placeholder'=>'0','readonly'=>'']) !!}
        <!--<p class="errorNominal text-center alert alert-danger hidden"></p>-->
    </div>
</div>
    </div>
</div>