@extends('layouts.app')

@section('content')
	<div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">
                	Convert .txt file to excel file<hr>
                    {!! Form::open (['url'=>'convertbca','files'=>true]) !!}
						<div class="form-group row">
						    {!! Form::label('uploaded_file','Upload File:',['class'=>'col-md-2 form-control-label']) !!}
						    <div class="col-md-10"><input type="file" name="filebca[]" multiple class="form-control-sm form-control" /></div>
						    </br>
						</div>
						<button type="submit" class="btn btn-sm btn-success">Upload & Convert</button>
						    <br>
						    <br>
						    <p style="color: #026670;">Pilih opsi Laporan di halaman download Laporan VA BCA</p>
						    <p style="color: #026670;">File txt-nya yang ada tulisan "rpt"</p>
						{!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>

@endsection