<div class="container">
    @if(!empty($tempf))
        <div class="form-row" style="padding-top: 25px;">
            <div class="col-auto">
            {!! Form::label('namafile','Daftar Reimbursement',['class'=>'col-auto form-control-label',
            'style'=>'color:black']) !!}</div>
            <div class="col-auto">
                <select id="namafile" name="namafile" class="form-control">
                    <option value=""></option>
                @foreach($tempf as $fl)
                    <option value="{{ $fl->namafile.'|'.$fl->mode }}">{{ $fl->namafile }}</option>
                    @endforeach
                </select></div>
            <div class="col-auto">
                <a href="{{ asset('storage/') }}" class="btn btn-primary" id="printPdf" target="_blank">Print</a>
                <a href="" class="btn btn-warning" id="editKK">Edit</a>
                <span id="errmsg"></span>
                </div>
            </div>
        </div>
    @endif
</div>