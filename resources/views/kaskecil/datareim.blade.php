@extends('layouts/app')

@section('content')
    <div class="container table-responsive">
        <H3>Daftar Reimbursement</H3>
        @if(!empty($tempf))
            @foreach($tempf as $fl)
                <a href="{{ asset('storage/'.$fl->namafile) }}">{{ $fl->namafile }}</a>
            @endforeach
        @endif
    </div>
@stop