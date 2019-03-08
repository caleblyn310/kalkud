@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-6 mx-auto" style="padding-top: 25px;">
                <div class="card">
                    <div class="card-body"><h6>Edit Kas Kecil</h6><hr>
                    {!! Form::model($kaskecil,['method'=>'PATCH', 'action'=>['KaskecilController@update',$kaskecil->id]]) !!}
                    @include('kaskecil.form')
                    {!! Form::close() !!}
                    </div>
            </div>
        </div>
    </div>
    @endsection