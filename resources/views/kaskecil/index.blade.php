@extends('layouts/app')

@section('content')
    @include('_partial.flash_message')
    <div class="container table-responsive">
        <div class="row">
            <div class="tomb text-right btn-group-vertical" style="width: 80px; height: auto;">
                <a href="kaskecil/create" class="btn btn-sm btn-info btn-block">ADD</a>
                @if($kaskecil_list->count() > 0)
                    <a href="mpdf/req" class="btn btn-sm btn-danger btn-block" onclick="return confirm('Sudah yakin mau reimburse??')">REQUEST</a>
                @endif
            </div>
                
        @if (!empty($kaskecil_list))
            <table class="table table-striped table-bordered table-hover table-condensed table-sm" id="tblKaskcl">
                <caption style="caption-side: top;color: #171717;">Daftar kas kecil yang belum di upload <strong>(Plafon: Rp. {{ number_format($plafon,0,'','.') }},00 - Total Reimburse: <u>{{ number_format($totalreim,0,"",".") }}</u> | Sisa Saldo: <u>{{ number_format($plafon-$totalreim,0,"",".") }}</u>)</strong></caption>
                <thead><tr>
                    <th>Tanggal</th><th>Kode D-Ger</th><th>Sub<br>Kode</th><th>No BPU</th><th style="width: 62%;">Deskripsi</th><th>Nominal</th><th style="width: 77px;">Action</th>
                </tr></thead>
                <tbody>
                <?php $total = 0 ?>
                <?php foreach ($kaskecil_list as $kaskecil) : ?>
                <tr>
                    <td>{{ $kaskecil->tanggal_trans->format('d-m-Y') }}</td>
                    <td>{{ $kaskecil->kode_d_ger }}</td>
                    <td>{{ $kaskecil->subkode }}</td>
                    <td>{{ $kaskecil->no_bukti }}</td>
                    <td style="text-align: left;">{{ $kaskecil->deskripsi }}</td>
                    <td style="text-align: right">{{ number_format($kaskecil->nominal,0,",",".") }}</td>
                    <?php $total += $kaskecil->nominal ?>
                    <td>
                        <div class="box-button">{{link_to('kaskecil/'.$kaskecil->id.'/edit','',['class'=>'btn btn-warning btn-sm fa fa-pencil-square-o'])}}</div>
                        <div class="box-button">{!! Form::open(['method'=>'DELETE', 'action'=>['KaskecilController@destroy',$kaskecil->id]]) !!}
                            {!! Form::button('', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm fa fa-trash',
                            'onclick'=> 'return confirm("Yakin nih mau dihapus??")']) !!}{!! Form::close() !!}</div>
                    </td>
                </tr>
                <?php endforeach ?>
                <tr><td colspan="7">
                    {!! $kaskecil_list->links('vendor.pagination.bootstrap-4') !!}
                </td></tr>
                <?php (count($totalcoa) > count($totalsk)) ? $totalrow=count($totalcoa) : $totalrow=count($totalsk); 
                if(count($totalsk)>0) $totalsk[0]->subkode = 'Kas Kecil'; ?>
                <tr><td colspan="4" style="text-align:right;">Total Nominal Per Sub Kode</td>
                    <td colspan="3" style="text-align:right;">Total Nominal Per COA</td></tr>
                <?php for ($i=0; $i < $totalrow; $i++) : ?>
                    <tr>
                    @if(count($totalsk)>$i)
                        <td colspan="2" style="text-align:right;">{{ $totalsk[$i]->subkode }}</td>
                        <td colspan="2" style="text-align:right">{{number_format($totalsk[$i]->total,0,'','.')}}</td>
                    @else <td colspan="4"></td> @endif
                    @if(count($totalcoa)>$i)
                        <td style="text-align:right;" colspan="2">{{$totalcoa[$i]->kode_d_ger}}</td>
                        <td style="text-align:right;">{{number_format($totalcoa[$i]->total,0,'','.')}}</td>
                    @else <td colspan="3"></td> @endif
                    </tr>
                <?php endfor ?>
                </tbody>
            </table>
        @else
            <p>Tidak ada data kas kecil yang belum di upload</p>
        @endif
    </div>
@endsection