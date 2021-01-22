@extends('layouts.app')

@section('content')
<div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body"><h6>Inventory Adjustment</h6><hr>
                	{!! Form::open(['url'=>'adjust','class'=>'']) !!}
                    <div class="form-group row">
					    {!! Form::label('doa','Date',['class'=>'col-sm-3 form-control-label']) !!}
					    <div class="col-lg-8">
					    {!! Form::date('doa',date('Y-m-d'),['class'=>'form-control form-control-sm','required'=>'',]) !!}
					    </div>
					</div>
				    <div class="form-group row">
				    {!! Form::label('inven','Item',['class'=>'col-sm-3 form-control-label']) !!}
				    <div class="col-lg-8">
				        {!! Form::text('qInven', '', ['class'=>'form-control form-control-sm','id' => 'qInven', 'placeholder' =>  'Search inventory',
				        'autofocus'=>'']) !!}
				        {!! Form::text('inven',null,['class'=>'form-control form-control-sm','placeholder'=>'Inventory description',
				        'required'=>'','readonly'=>'']) !!}
				    </div></div>
					<div class="form-group row">
					    {!! Form::label('description','Description',['class'=>'col-sm-3 form-control-label']) !!}
					    <div class="col-lg-8">
					    {!! Form::text('description',null,['class'=>'form-control form-control-sm','placeholder' => 'Adjustment description','required'=>'',]) !!}
					    </div>
					</div>
					<div class="form-group row">
					    {!! Form::label('oldNBV','Current NBV',['class'=>'col-sm-3 form-control-label']) !!}
					    <div class="col-lg-8">
					    {!! Form::text('oldNBV',null,['class'=>'form-control form-control-sm','placeholder' => 'Current NBV','required'=>'','readonly'=>'']) !!}
					    </div>
					</div>
					<div class="form-group row">
					    {!! Form::label('newNBV','New NBV',['class'=>'col-sm-3 form-control-label']) !!}
					    <div class="col-lg-8">
					    {!! Form::text('newNBV',null,['class'=>'form-control form-control-sm','placeholder' => 'Type new NBV','required'=>'',]) !!}
					    </div>
					</div>
					<div class="form-group row">
					    {!! Form::label('adjDepreciation','Adjed. Depre',['class'=>'col-sm-3 form-control-label']) !!}
					    <div class="col-lg-8">
					    {!! Form::text('adjDepreciation',null,['class'=>'form-control form-control-sm','placeholder' => 'Adjustment description','required'=>'','readonly'=>'']) !!}
					    </div>
					</div>
					<div class="form-group row">
					    {!! Form::label('oldUL','Current UL',['class'=>'col-sm-3 form-control-label']) !!}
					    <div class="col-lg-8">
					    {!! Form::text('oldUL',null,['class'=>'form-control form-control-sm','placeholder' => 'Current UL','required'=>'','readonly'=>'']) !!}
					    </div>
					</div>
					<div class="form-group row">
					    {!! Form::label('newUL','New UL',['class'=>'col-sm-3 form-control-label']) !!}
					    <div class="col-lg-8">
					    {!! Form::text('newUL',null,['class'=>'form-control form-control-sm','placeholder' => 'Type new useful life','required'=>'',]) !!}
					    </div>
					</div>
					<div class="form-group row">
					    <div class="col-md-8 col-md-offset-4">
					        <button type="submit" class="btn btn-sm btn-success" id="btnSave">SAVE</button>
					        <a href="{{ asset('inventory') }}" class="btn btn-sm btn-danger">Cancel</a>
					    </div>
					</div>
					{!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/numeral.min.js') }}"></script>
<script type="text/javascript">
	var oldNBV = 0;
	$("#oldNBV,#newNBV,#adjDepreciation").blur(function () {
        //var oldNBV = $("#oldNBV").val();
        var newNBV = $("#newNBV").val();
        $("#adjDepreciation").val(parseFloat(oldNBV) - parseFloat(newNBV));
    });

    $( "#qInven" ).autocomplete({
        source: "http://"+location.hostname+"/search/inven",
        minLength: 3,
        select: function(event, ui) {
            $('#qInven').val(ui.item.value);
            $('#inven').val(ui.item.id);
            oldNBV = ui.item.oldNBV;
            $('#oldNBV').val(oldNBV);
            $('#oldUL').val(ui.item.oldUL);
            $('#newUL').val($('#oldUL').val());
        }
    });

    $("#newNBV,#oldUL,#newUL").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
            //display error message
            $("#errmsg").html("Hanya boleh angka").show().fadeOut(3000);
            return false;
        }
    });

</script>
@endsection