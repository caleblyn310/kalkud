@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-7 mx-auto" style="padding-top: 25px;">
                <div class="card">
                    <div class="card-header"><h2>Edit Inventory</h2></div>
                    <div class="card-body">
                    {!! Form::model($inventory,['method'=>'PATCH', 'action'=>['InventoryController@update',$inventory->id]]) !!}
                    <br>@include('inventory.form')
                    {!! Form::close() !!}
                    </div>
            </div>
        </div>
    </div>
@stop