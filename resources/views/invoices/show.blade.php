@extends('layouts.app')

@section('css')
<style type="text/css">
.judulLabel {
    text-align: left;
    width: 10%;
}
.semiColon {
    width: 2%;
}
.isiLabel {
    text-align: left;
    font-weight: bold;
}
</style>
@endsection

@section('content')
    @if(!empty($invoices))
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-body" style="background-color: #d5f4e6;">
                    Bukti Pengeluaran Bank<hr>
                    <table class="table table-striped table-condensed table-bordered table-sm">
                        <tbody>
                        <tr>
                            <td class="judulLabel">Invoices No</td>
                            <td class="semiColon">:</td>
                            <td class="isiLabel">{{ $invoices->invoices_no }}</td>
                            <td rowspan="3" style="font-weight: bold;">{{ $invoices->dot->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="judulLabel">Pay To</td>
                            <td class="semiColon">:</td>
                            <td class="isiLabel">{{ $invoices->pay_to }}</td>
                            
                        </tr>
                        <tr>
                            <td class="judulLabel">Submit to</td>
                            <td class="semiColon">:</td>
                            <td class="isiLabel">{{ $invoices->give_to }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-weight: bold;">Deskripsi</td>
                            <td style="font-weight: bold; width: 15%;">Nominal (IDR)</td>
                        </tr>
                            <?php foreach ($trans_list as $trans) : ?>
                            <tr><td colspan="3" style="text-align: left;">{{$trans->description}}</td>
                                <td style="font-weight: bold; text-align: right;">{{ number_format($trans->nominal,'2','.',',') }}</td></tr>
                            <?php endforeach ?>
                        <tr><td colspan="3" style="text-align: right; font-weight: bold;">Total: </td>
                            <td style="text-align: right; font-weight: bold;">{{ number_format($invoices->nominal,'2','.',',') }}</td></tr>

                        <tr><td colspan="4" style="background-color: #343434; padding-top: 0px;"></td></tr>
                        <tr><td colspan="4" style="text-align: center;">
                            <button class="btn btn-sm btn-danger" type="button" id="Cancel">Batal</button>&nbsp;&nbsp;
                            <button class="btn btn-sm btn-warning" type="button" id="Close">Tutup</button>&nbsp;&nbsp;
                            <button class="btn btn-sm btn-success" type="button" id="Approve">Setuju</button>&nbsp;&nbsp;
                        </td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('scripts')
<script type="text/javascript">
    $('#Close').click(function() {
        location.replace("/");
    });
    $('#Cancel').click(function() {
        location.replace("/");
    });
    $('#Approve').click(function() {
        location.replace("/");
    });
</script>
@endsection