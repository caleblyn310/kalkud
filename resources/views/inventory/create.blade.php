@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-md-7 mx-auto">
            <div class="card">
                <div class="card-body">
                    <H2>Inventory</H2><hr>
                    {!! Form::open(['url'=>'inventory','class'=>'']) !!}
                    @include('inventory.form')
                    {!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>
@stop