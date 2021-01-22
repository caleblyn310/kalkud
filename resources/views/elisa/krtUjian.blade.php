@extends('layouts.app')
@section('css')
<style type="text/css">
    #dftr_siswa, #title, #dot {
        max-height: 36px;
        min-height: 36px;
        box-shadow: none !important;
        border-width: 0 0 1px 0;
        border-radius: 0;
        background-color: #fbfff1;
        padding-left: 5px;
        padding-top: 0px;
        margin-top: 0px;
        font-size: 18px;
        color: black;
    }

    input:focus {
        border-color: #ccc !important;
    }

    .control-label {
        padding-left: 6px;
        padding-bottom: 0px;
        margin-top: 15px;
        margin-bottom: 0px;
        font-size: 12px;
        color: #00582c;
    }
</style>
@endsection

@section('content')
	@include('_partial.flash_message')
	<div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body"><H2 style="text-align: center;">Generate TEST card</H2>
                    {!! Form::open (['url'=>'ambildata','files'=>true]) !!}
                    	<label class="control-label">Choose an Excel File</label>
                        <input type="file" id="dftr_siswa" class="form-control form-control-sm" name="dftr_siswa"
                        required style="box-shadow: none !important;border-width: 0 0 1px 0; border-radius: 0;">
                        <label class="control-label">Title</label>
                        <input id="title" class="form-control form-control-sm" name="title" maxlength="38" 
                        required style="box-shadow: none !important;border-width: 0 0 1px 0; border-radius: 0;">
                        <div class="row">
                        	<div class="col">
                        		<label class="control-label">Date</label>
		                        <input type="date" id="dot" name="dot" class="form-control form-control-sm" value="{{ Carbon::now()->format('Y-m-d') }}" 
		                        required style="box-shadow: none !important;border-width: 0 0 1px 0; border-radius: 0;">
                        	</div>
                        	<div class="col">
		                        <div class="custom-control custom-checkbox mt-3" style="padding-left: 30px;">
		                        	<input id="logo" class="custom-control-input" type="checkbox" name="logo">
		                        	<label class="custom-control-label" for="logo" style="font-size: 16px;">Logo Dinas</label>
		                        </div>
                        	</div>
                        </div>
                        
						<div class="form-group row mt-4">
							<div class="col" style="text-align: center;">
								<button type="submit" class="btn btn-sm btn-success" style="border-radius: 25px; width: 150px;">Generate</button>
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
<script type="text/javascript">
	$('form').submit(function () {
		$('#loader').show();
	});
</script>
@endsection