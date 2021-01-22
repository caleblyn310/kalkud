@extends('layouts/app')

@section('content')
<div class="container">
        @include('_partial.flash_message')
        <div class="row" style="padding-top: 25px;">
            <div class="col-12">
                <div class="form-group row" style="background-color: #eef9fd; padding: 10px 0px 10px 0px;">
                    <strong>{!! Form::label('periode','SAA '.$uri,['class'=>'col-6 form-control-label','style'=>'color:black;']) !!}</strong>
                    <div class="col-6">
                    	<div class="row"> 
                        <b>FROM</b>&nbsp;&nbsp;&nbsp;{!! Form::date('tanggal1','2019-01-01',['class'=>'form-control form-control-sm col-md-3','required'=>'','id'=>'tanggal1']) !!}&nbsp;&nbsp;&nbsp;<b>TO</b>&nbsp;&nbsp;&nbsp; 
                        {!! Form::date('tanggal2','2019-01-31',['class'=>'form-control form-control-sm col-md-3','required'=>'','id'=>'tanggal2']) !!}&nbsp;&nbsp;&nbsp;{!! Form::button('Get Data',['class'=>'btn btn-sm btn-primary','id'=>'getsaa']) !!}
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
    var t = "{!! $uri !!}"; 

    $.ajax({
    	type: 'GET',
    	url: "/getSAA" + t,
    	data: {
    		'tanggal1': $('#tanggal1').val(),
    		'tanggal2': $('#tanggal2').val(),
    	},
    	success: function(res) {
    		toastr.success('Successfully added Description!', 'Success Alert', {timeOut: 2500});
    		var path = res.path;
    		location.href = path;
    		spinner.hide();
    	},
        error: function(xhr, status, error){
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            alert('Error - ' + errorMessage);
        }
    });
  });
});
</script>
@stop