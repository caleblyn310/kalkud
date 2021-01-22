@extends('layouts.app')

@section('content')
<div class="container">
	<h5 style="margin-top: 10px;"><b>Get All Transactions</b></h5>
	<div class="row">
            <div class="col-12">
                <div class="form-group row" style="background-color: #eef9fd; padding: 10px 0px 10px 0px;">
                    <strong>{!! Form::label('periode','Periode:',['class'=>'col-md-6 col-sm-12 col-lg-2 form-control-label','style'=>'color:black;']) !!}</strong>
                    <div class="col-md-10">
                    	<div class="row"> 
                        {!! Form::date('tanggal1','2019-01-01',['class'=>'form-control form-control-sm col-md-2','required'=>'','id'=>'tanggal1']) !!}&nbsp;
                        &nbsp;{!! Form::button('Get Data',['class'=>'btn btn-sm btn-primary','id'=>'getgat']) !!}
                    </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript">
	var spinner = $('#loader');
$(function() {
  $('#getgat').click(function(e) {
    e.preventDefault();
    spinner.show();

    $.ajax({
    	type: 'GET',
    	url: '/getAllTrans',
    	data: {
    		'tanggal1': $('#tanggal1').val(),
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
@endsection