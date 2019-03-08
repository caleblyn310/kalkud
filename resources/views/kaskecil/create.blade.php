@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body"><h6>Tambah Kas Kecil</h6><hr>
                    {!! Form::open(['url'=>'kaskecil','class'=>'']) !!}
                    @include('kaskecil.form')
                    {!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>

    @endsection