{!! Form::hidden('invoices_no',$invoices_no,['id'=>'invoices_no']) !!}

<div class="row">
    <div class="col-6"> 
@if ($errors->any())
    <div class = "form-group row {{ $errors->has('dot') ? 'has-error' : 'has-success' }}">
@else
<div class="form-group row">
@endif
    {!! Form::label('dot','Tanggal',['class'=>'col-lg-4 col-md-6 col-sm-12 form-control-label']) !!}
    <div class="col-lg-8 col-sm-12">
        {!! Form::date('dot',!empty($invoices) ? $invoices->dot->format('Y-m-d') : date('Y-m-d')
        ,['class'=>'form-control','required'=>'']) !!}&nbsp;
    @if($errors->has('dot'))<span>{{ $errors->first('dot') }}</span>@endif</div>
</div>
</div>

<div class="col-6">
<div class="form-group row">
    {!! Form::label('bank','BANK',['class'=>'col-lg-4 col-sm-12 form-control-label']) !!}
    <div class="col-lg-8">
        {!! Form::select('bank',$bank_list,null,['class'=>'form-control','maxlength'=>'2']) !!}
    </div>
</div>
</div>
</div>

<div class="row">
    <div class="col-6">
<div class="form-group row">
    {!! Form::label('pay_to','Pay to',['class'=>'col-lg-4 col-sm-12 form-control-label']) !!}
    <div class="col-lg-8">
        {!! Form::text('pay_to',null,['class'=>'form-control','placeholder'=>'Name','required'=>'']) !!}
    </div>
</div>
</div>

<div class="col-6">
<div class="form-group row">
    {!! Form::label('give_to','Submit to',['class'=>'col-lg-4 col-md-6 col-sm-12 form-control-label']) !!}
    <div class="col-lg-8">
        {!! Form::text('give_to',null,['class'=>'form-control','placeholder'=>'Name','required'=>'']) !!}
    </div>
</div>
</div>
</div>

<button type="button" class="btn btn-sm btn-default add-modal">Add Detail</button>
<button type="button" class="btn btn-sm btn-default memo-modal">Add Memo</button><hr>

<table class="table table-striped table-bordered table-hover table-sm" id="postTable">
    <thead>
        <tr>
            <th>Description</th>
            <th style="width: 100px;">Nominal</th>
            <th style="width: 100px;">Actions</th>
        </tr>
        {{ csrf_field() }}
    </thead>
    <tbody>
        @if(!empty($invoicesdetail_list))
        @foreach($invoicesdetail_list as $invoicesdetail)
            <tr class="item{{$invoicesdetail->id}}">
                <td>{{$invoicesdetail->description}}&nbsp;({{$invoicesdetail->kode_d_ger}})</td>
                <td>{{$invoicesdetail->nominal}}</td>
                <td>
                    <button type='button' class="edit-modal btn btn-info btn-sm fa fa-pencil-square-o" data-id="{{$invoicesdetail->id}}" data-title="{{$invoicesdetail->description}}" data-content1="{{$invoicesdetail->nominal}}" data-content2="{{$invoicesdetail->kode_d_ger}}"></button>
                    <button type='button' class="delete-modal btn btn-danger btn-sm fa fa-trash" data-id="{{$invoicesdetail->id}}" data-title="{{$invoicesdetail->description}} ({{$invoicesdetail->kode_d_ger}})" data-content="{{$invoicesdetail->nominal}}"></button>
                </td>
            </tr>
        @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td>Total:</td>
            <td id="totalnominal">{{ (!empty($total)) ? $total : 0 }} </td>
            {!! Form::hidden('nominals',$total,['id'=>'nominals']) !!}
            {!! Form::hidden('user_id',Auth::user()->id,['id'=>'user_id']) !!}
            {!! Form::hidden('aiw',(!empty($aiw)) ? $aiw : 0,['id'=>'aiw']) !!}
        </tr>
        <tr>
            <td colspan="2" id="terbilang-output"></td>
        </tr>
    </tfoot>
</table>

<!--<a href="{{ asset('invoicesdetail/create') }}" class="btn btn-sm btn-info" data>Add Detail</a><hr>-->

<!--Memo modal-->
<div class="modal fade" id="memoModal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Memo</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <textarea rows="10" cols="50" name="memo" id="memo">{{!empty($invoices) ? $invoices->memo : ''}}</textarea>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>

<!--add modal-->
<div class="modal fade" id="addModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Transaction Detail</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          @include('invoicesdetail.form')
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-success add" data-dismiss="modal">Add</button>
            <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>

    <!--edit modal-->
  <div id="editModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    @include('invoicesdetail.formedit')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary edit" data-dismiss="modal">Edit</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!--delete invoice-->
    <div id="deleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <h6 class="text-center">Are you sure you want to delete the following detail?</h6>
                    <br />
                    @include('invoicesdetail.formdelete')   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger delete" data-dismiss="modal">Delete</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<div class="form-group row">
    <div class="col-md-8 col-md-offset-4">
        <button type="submit" class="btn btn-sm btn-default" name="submitbutton" value="save">SAVE</button>
        <button type="submit" class="btn btn-sm btn-default" name="submitbutton" value="saveprint">SAVE & Print</button>
        @if($fs == 'new')
            <a href="{{ asset('invoicecancel/'.$invoices_no) }}" class="btn btn-sm btn-default">Cancel</a>
        @else
            <a href="{{ asset('invoices') }}" class="btn btn-sm btn-default">Close</a>
        @endif
    </div>
</div>

@section ('scripts')
<script src="{{ asset('js/webprint.js') }}"></script>
<script src="{{ asset('terbilang/jTerbilang.js') }}"></script>
<script>
    function myTerbilang() {
        $('#totalnominal').terbilang({
            style : 3,
            input_type : "text",
            output_div : "terbilang-output",
            akhiran : "Rupiah"
          });
        $('#aiw').val($('#terbilang-output').html());
    }
    $(document).ready(function () {
        $("#totalnominal").val($('#totalnominal').html());
        myTerbilang();
    });
    
    $( "#q_edit" ).autocomplete({
        source: "http://"+location.hostname+ "/search/autocomplete",
        minLength: 3,
        select: function(event, ui) {
            $('#q_edit').val(ui.item.value);
            $('#kode_d_ger_edit').val(ui.item.id);
        }
    });

    //Add memo description
    $(document).on('click', '.memo-modal', function() {
        $('.modal-title').text('Input your memo');
        $('#memoModal').modal('show');
    });

    //Add description and nominal invoice
    $(document).on('click', '.add-modal', function() {
        $('.modal-title').text('Add Description');
        $('#description').val('');
        $('#nominal').val('');
        $('#q').val('');
        $('#kode_d_ger').val('');
        $('#addModal').modal('show');
    });
    $('.modal-footer').on('click', '.add', function() {
        if ($('#description').val() == "") {
            alert('please fill description field');
            return false;
        }
        else if ($('#nominal').val() == "") {
            alert('please fill nominal field');
            return false;
        }
        else {
        $.ajax({
            type: 'POST',
            url: '/invoicesdetail',
            data: {
                '_token': $('input[name=_token]').val(),
                'invoices_no': $('#invoices_no').val(),
                'description': $('#description').val(),
                'nominal': $('#nominal').val(),
                'kode_d_ger': $('#kode_d_ger').val()
            },
            success: function(data) {
                $('.errorDescription').addClass('hidden');
                $('.errorNominal').addClass('hidden');

                if ((data.errors)) {
                    setTimeout(function () {
                        $('#addModal').modal('show');
                        toastr.error('Validation error!', 'Error Alert', {timeOut: 2500});
                    }, 500);

                    if (data.errors.title) {
                        $('.errorDescription').removeClass('hidden');
                        $('.errorDescription').text(data.errors.title);
                    }
                    if (data.errors.content) {
                        $('.errorNominal').removeClass('hidden');
                        $('.errorNominal').text(data.errors.content);
                    }
                } else {
                    toastr.success('Successfully added Description!', 'Success Alert', {timeOut: 2500});
                    $('#postTable').append("<tr class='item" + data.id + "'><td>" + data.description + " (" + data.kode_d_ger + ")</td><td>" + data.nominal + "</td><td><button type='button' class='edit-modal btn btn-info btn-sm fa fa-pencil-square-o' data-id='" + data.id + "' data-title='" + data.description + "' data-content1='" + data.nominal + "' data-content2='" + data.kode_d_ger + "'></button> <button type='button' class='delete-modal btn btn-danger btn-sm fa fa-trash' data-id='" + data.id + "' data-title='" + data.description + " (" + data.kode_d_ger + ")' data-content='" + data.nominal + "'></button></td></tr>");
                    $('#totalnominal').html(parseInt($('#totalnominal').html()) + parseInt(data.nominal));
                    $('#nominals').val($('#totalnominal').html());
                    $("#totalnominal").val($('#totalnominal').html());
                    myTerbilang();
                }
            },
        });
    }
    });

    //Edit description and nominal invoice
    $(document).on('click', '.edit-modal', function() {
            $('.modal-title').text('Edit Description');
            $('#description_edit').val($(this).data('title'));
            $('#nomedit').val($(this).data('content1'));
            $('#kode_d_ger_edit').val($(this).data('content2'));
            $('#q').val('');
            id = $(this).data('id');
            nom_edit = $(this).data('content1');
            $('#editModal').modal('show');
        });
    $('.modal-footer').on('click', '.edit', function() {
        if ($('#description_edit').val() == "") {
            alert('please fill description field');
            return false;
        }
        else if ($('#nomedit').val() == "") {
            alert('please fill nominal field');
            return false;
        }
        else {
        $.ajax({
            type: 'PUT',
            url: "/invoicesdetail/"+id,
            data: {
                '_token': $('input[name=_token]').val(),
                'id': id,
                'description': $('#description_edit').val(),
                'nominal': $('#nomedit').val(),
                'kode_d_ger': $('#kode_d_ger_edit').val()
            },
            success: function(data) {
                $('.errorTitle').addClass('hidden');
                $('.errorContent').addClass('hidden');

                if ((data.errors)) {
                    setTimeout(function () {
                        $('#editModal').modal('show');
                        toastr.error('Validation error!', 'Error Alert', {timeOut: 2500});
                    }, 500);

                    if (data.errors.title) {
                        $('.errorTitle').removeClass('hidden');
                        $('.errorTitle').text(data.errors.title);
                    }
                    if (data.errors.content) {
                        $('.errorContent').removeClass('hidden');
                        $('.errorContent').text(data.errors.content);
                    }
                } else {
                    toastr.success('Successfully updated Post!', 'Success Alert', {timeOut: 2500});
                    $('.item' + data.id).replaceWith("<tr class='item" + data.id + "'><td>" + data.description + " (" + data.kode_d_ger + ")</td><td>" + data.nominal + "</td><td><button type='button' class='edit-modal btn btn-info btn-sm fa fa-pencil-square-o' data-id='" + data.id + "' data-title='" + data.description + "' data-content1='" + data.nominal + "' data-content2='" + data.kode_d_ger + "'></button> <button type='button' class='delete-modal btn btn-danger btn-sm fa fa-trash' data-id='" + data.id + "' data-title='" + data.description + " (" + data.kode_d_ger + ")' data-content='" + data.nominal + "'></button></td></tr>");
                    $('#totalnominal').html(parseInt($('#totalnominal').html()) + parseInt(data.nominal) - parseInt(nom_edit));
                    $('#nominals').val($('#totalnominal').html());
                    $("#totalnominal").val($('#totalnominal').html());
                    myTerbilang();
                }
            }
        });
    }
    });

    //Delete description and nominal invoice
    $(document).on('click', '.delete-modal', function() {
            $('.modal-title').text('Delete Description');
            $('#description_del').val($(this).data('title'));
            $('#nominal_del').val($(this).data('content'));
            $('#deleteModal').modal('show');
            id = $(this).data('id');
    });
    $('.modal-footer').on('click', '.delete', function() {
        $.ajax({
            type: 'DELETE',
            url: "/invoicesdetail/"+id,
            data: {
                '_token': $('input[name=_token]').val(),
            },
            success: function(data) {
                toastr.success('Successfully deleted Post!', 'Success Alert', {timeOut: 2500});
                $('#totalnominal').html(parseInt($('#totalnominal').html()) - parseInt(data.nominal));
                $('#nominals').val($('#totalnominal').html());
                $('.item' + data['id']).remove();
                $("#totalnominal").val($('#totalnominal').html());
                myTerbilang();
            }
        });
    });
    /*webprint = new WebPrint(true, {
            relayHost: "127.0.0.1",
            relayPort: "8080",
            readyCallback: function(){}
        });

    $(document).on('click', '.saveprint', function() {
        $.ajax({
            type: 'POST',
            url: 'http://kaskecil.app/invoices',
            data: {
                '_token': $('input[name=_token]').val(),
                'dot': $('#dot').val(),
                'bank': $('#bank').val(),
                'pay_to': $('#pay_to').val(),
                'give_to': $('#give_to').val(),
                'nominal': $('#nominals').val(),
                'user_id': $('#user_id').val(),
                'invoices_no': $('#invoices_no').val(),
                'submitbutton': 'saveprint',
            },
            success: function(data, textStatus, jq) {
                webprint.printRaw(data, 'lx300');
                toastr.success('Successfully printed!', 'Success Alert', {timeOut: 2500});
                location.href = "http://kaskecil.app/invoices";
            }
        });

        $.ajax({
            type: 'GET',
            url: 'http://kaskecil.app/invoices/' + $(this).data('id') + '/print',
            async: false,
            cache: false,
            dataType: "text",
            success: function(data, textStatus, jq) {
                webprint.printRaw(data, 'lx300');
                toastr.success('Successfully printed!', 'Success Alert', {timeOut: 2500});
            }
        });
    });*/
</script>
@stop