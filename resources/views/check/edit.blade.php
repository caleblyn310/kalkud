@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-body"><h6>Edit Cheque</h6><hr>
                    {!! Form::model($cheque,['method'=>'PATCH', 'action'=>['ChequeController@update',$cheque->id]]) !!}
                    @include('check.form')
                    {!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>

    @endsection