@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body"><strong>Edit Transaction</strong><hr>
                    {!! Form::model($jurnaladmin,['method'=>'PATCH', 'action'=>['JurnalController@update',$jurnaladmin->id]]) !!}
                    @include('jurnaladmin.form')
                    {!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection