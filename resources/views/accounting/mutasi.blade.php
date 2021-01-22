@extends('layouts.app')

@section('content')
	<div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">
                	Insert bank mutation (.txt file)<hr>
                    {!! Form::open (['url'=>'mutasibank','files'=>true]) !!}
						<div class="form-group row">
						    {!! Form::label('uploaded_file','Upload File:',['class'=>'col-md-2 form-control-label']) !!}
						    <div class="col-md-10"><input type="file" name="filebank" class="form-control-sm form-control" /></div>
						    </br>
						</div>
						<button type="submit" class="btn btn-sm btn-success">Insert</button>
						    <br>
						    <br>
						    <p style="color: #026670;">Pastikan file berekstensi "txt"</p>
						{!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection