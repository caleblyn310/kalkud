@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header"><H2>Add Transaction</H2></div>
                <div class="card-body">
                    {!! Form::open(['url'=>'jurnaladmin','class'=>'']) !!}
                    @include('jurnaladmin.form')
                    {!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection