@extends('layouts.app')

@section('content')
    <div class="container table-responsive" >
        
        @include('_partial.flash_message')
        <div class="tomb text-right"><a href="{{ url('mpdf/'.$namafile) }}" class="btn btn-danger btn-sm" onclick="return confirm('Sudah yakin mau Reimburse??')">Request</a></div>
        @if (!empty($kaskecil_list))
            <table class="table table-striped table-bordered table-hover table-condensed table-sm">
                <caption style="caption-side: top;color: #171717"><strong>Daftar Kas Kecil yang Akan Di Edit</strong></caption>
                <thead><tr>
                    <th>Tanggal</th><th>Kode D-Ger</th><th>Sub<br>Kode</th><th>No BPU</th><th>Deskripsi</th><th>Nominal</th><th style="width:90px;">Action</th>
                </tr></thead>
                <tbody>
                <?php $total = 0?>
                <?php foreach ($kaskecil_list as $kaskecil) : ?>
                <tr>
                    <td>{{ Carbon\Carbon::parse($kaskecil->tanggal_trans)->format('d-m-Y') }}</td>
                    <td>{{ $kaskecil->kode_d_ger }}</td>
                    <td>{{ $kaskecil->subkode }}</td>
                    <td>{{ $kaskecil->no_bukti }}</td>
                    <td style="text-align: left;">{{ $kaskecil->deskripsi }}</td>
                    <td style="text-align: right;">{{ number_format($kaskecil->nominal,0,",",".") }},00</td>
                    <?php $total += $kaskecil->nominal ?>
                    <td>
                        <div class="box-button">{{ link_to('datareim/'.$namafile.'z'.$kaskecil->id.'z/edit','',['class'=> 'btn btn-warning btn-sm fa fa-pencil-square-o']) }}</div>
                        <div class="box-button">{!! Form::open(['method'=>'DELETE', 'action'=>['DatareimController@destroy',$kaskecil->id]]) !!}
                            {!! Form::hidden('nv',$namafile) !!}
                            {!! Form::button('',['type' => 'submit', 'class' => 'btn btn-danger btn-sm fa fa-trash',
                            'onclick'=> 'return confirm("Apakah Bapak/Ibu yakin mau hapus??")']) !!} {!! Form::close() !!}</div>
                    </td>
                </tr>
                <?php endforeach ?>
                <tr class="table-success"><td colspan="5" style="text-align: right">Total</td>
                    <td style="text-align: right"><b>Rp. {{ number_format($totalreim,0,"",".") }},00</b></td></tr>
                </tbody>
            </table>
        @endif
    </div>


@stop