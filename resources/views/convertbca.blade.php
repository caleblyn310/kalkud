@extends('layouts.app')

@section('content')
	<div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header"><H2>Convert .txt File to Excel</H2></div>
                <div class="card-body">
                    {!! Form::open (['url'=>'convertbca','files'=>true]) !!}
						<div class="form-group row">
						    {!! Form::label('uploaded_file','Upload File:',['class'=>'col-md-3 form-control-label']) !!}
						    <div class="col-md-9"><input type="file" name="filebca[]" multiple class="form-control-sm form-control" /></div>
						    </br>
						</div>
						<button type="submit" class="btn btn-sm btn-success">Upload & Convert</button>
						    <br>
						    <br>
						    <p style="color:  white;">Pilih opsi Laporan di halaman download Laporan VA BCA</p>
						    <p style="color:  white;">File txt-nya yang ada tulisan "rpt"</p>
						{!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>

@endsection