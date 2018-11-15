@extends('layouts/app')

@section('content')
<div class="container">
        @include('_partial.flash_message')
        <div class="row" style="padding-top: 25px;">
            <div class="col-12">
                
                <div class="form-group row">
                    {!! Form::label('periode','Periode',['class'=>'col-md-6 col-sm-12 col-lg-2 form-control-label','style'=>'color:black;']) !!}
                    <div class="col-md-10">
                    	<div class="row"> 
                        FROM&nbsp;{!! Form::date('tanggal1','2017-12-01',['class'=>'form-control col-md-2','required'=>'','id'=>'tanggal1']) !!}&nbsp;TO&nbsp; 
                        {!! Form::date('tanggal2','2017-12-31',['class'=>'form-control col-sm-2','required'=>'','id'=>'tanggal2']) !!}&nbsp;{!! Form::button('Get Data',['class'=>'btn btn-sm btn-primary','id'=>'getsaa']) !!}
                    </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@stop

@section('scripts')
<script>
var spinner = $('#loader');
$(function() {
  $('#getsaa').click(function(e) {
    e.preventDefault();
    spinner.show();

    $.ajax({
    	type: 'GET',
    	url: '/getSAA',
    	data: {
    		'tanggal1': $('#tanggal1').val(),
    		'tanggal2': $('#tanggal2').val(),
    	},
    	success: function(res) {
    		toastr.success('Successfully added Description!', 'Success Alert', {timeOut: 2500});
    		var path = res.path;
    		location.href = path;
    		spinner.hide();
    	}
    });
  });
});
</script>
@stop