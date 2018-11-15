@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-6 mx-auto" style="padding-top: 25px;">
                <div class="card">
                    <div class="card-header"><h2>Edit Kas Kecil</h2></div>
                    <div class="card-body">
                    {!! Form::model($kaskecil,['method'=>'PATCH', 'action'=>['KaskecilController@update',$kaskecil->id]]) !!}
                    <br>@include('kaskecil.form')
                    {!! Form::close() !!}
                    </div>
            </div>
        </div>
    </div>
    @endsection