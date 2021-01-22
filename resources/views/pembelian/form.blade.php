@if (Session::has('flash_message'))
    <input type="hidden" value="{{ Session::get('flash_message') }}" id="msg">
@endif

<div class="row">
    <div class="col-lg-2"> 
        <div class="form-group row">
        <div class="col">
        {!! Form::date('dot',!empty($pembelian) ? $pembelian->dot->format('Y-m-d') : date('Y-m-d')
        ,['class'=>'form-control-sm form-control','required'=>'']) !!}</div>
        </div>
    </div>
    <div class="col-lg-3">
<div class="form-group row">
    <div class="col">
        {!! Form::text('supplier',null,['class'=>'form-control-sm form-control','placeholder'=>'Supplier . . .','required'=>'']) !!}
    </div>
</div>
</div>
    <div class="col-lg-3">
        <div class="form-group row">
    <div class="col">
    {!! Form::text('invoices_no',null,['class'=>'form-control form-control-sm','id' => 'invoices_no','placeholder'=>'Invoice Number...','required'=>'']) !!}</div>
    </div>
</div>
</div>

<table class="table table-striped table-bordered table-hover table-sm" id="postTable">
	<caption style="caption-side: top;color: #026670;"><strong>Daftar barang yang di beli</strong>&nbsp;&nbsp;
		<button type="button" class="btn btn-sm btn-info add-modal">Tambah</button></caption>
    <thead>
        <tr>
            <th>Nama Barang</th>
            <th>Kuantiti 1</th>
            <th>Kuantiti 2</th>
            <th style="width: 100px;">Harga</th>
            <th style="width: 100px;">Diskon</th>
            <th style="width: 100px;">Net</th>
            <th style="width: 100px;">Actions</th>
        </tr>
        {{ csrf_field() }}
    </thead>
    <tbody>
        @if(!empty($pembeliandetail_list))
        @foreach($pembeliandetail_list as $pembeliandetail)
            <tr class="item{{$pembeliandetail->id}}">
                <td>{{App\DaftarBarang::where('id',$pembeliandetail->id_barang)->value('nama_barang')}}</td>
                <td>{{$pembeliandetail->qty1}}</td>
                <td>{{$pembeliandetail->qty2}}</td>
                <td>{{$pembeliandetail->hrg_tot}}</td>
                <td>{{$pembeliandetail->diskon}}</td>
                <td>{{floatval($pembeliandetail->hrg_tot - $pembeliandetail->diskon)}}</td>
                <td>
                    <button type='button' class="edit-modal btn btn-info btn-sm fa fa-pencil-square-o" data-id="{{$pembeliandetail->id}}" data-title="{{App\DaftarBarang::where('id',$pembeliandetail->id_barang)->value('nama_barang')}}" data-content1="{{$pembeliandetail->qty1}}" data-content2="{{$pembeliandetail->qty2}}"data-content3="{{$pembeliandetail->hrg_tot}}" data-content4="{{$pembeliandetail->diskon}}" data-content5="{{$pembeliandetail->hrg_sat}}" data-content6="{{$pembeliandetail->id_barang}}"></button>
                    <button type='button' class="delete-modal btn btn-danger btn-sm fa fa-trash" data-id="{{$pembeliandetail->id}}" data-title="{{App\DaftarBarang::where('id',$pembeliandetail->id_barang)->value('nama_barang')}}" data-content1="{{floatval($pembeliandetail->hrg_tot - $pembeliandetail->diskon)}}"></button>
                </td>
            </tr>
        @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align: right;">Total:&nbsp;&nbsp;</td>
            <td id="totalnominal">{{ (!empty($total)) ? $total : 0 }} </td><td></td>
            {!! Form::hidden('nominals',(!empty($total)) ? $total : 0,['id'=>'nominals']) !!}
            {!! Form::hidden('user_id',Auth::user()->id,['id'=>'user_id']) !!}
            {!! Form::hidden('aiw',(!empty($aiw)) ? $aiw : 0,['id'=>'aiw']) !!}
            {!! Form::hidden('iddet', 0,['id'=>'iddet']) !!}
        </tr>
        <tr>
            <td colspan="7" id="terbilang-output">Belum ada barang yang diinput</td>
        </tr>
    </tfoot>
</table>

<!--<a href="{{ asset('invoicesdetail/create') }}" class="btn btn-sm btn-info" data>Add Detail</a><hr>-->

<!--Memo modal-->
<div class="modal fade" id="memoModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Memo</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
          <textarea class="form-control-sm col" rows="10" cols="45" name="memo" id="memo" wrap="hard">{{!empty($invoices) ? $invoices->memo : ''}}</textarea>
          </div>
          </div>
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
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Transaction Detail</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          @include('pembeliandetail.form')
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-success add" data-dismiss="modal">Add</button>
            <button type="button" class="btn btn-sm btn-warning" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>

    <!--edit modal-->
  <div id="editModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    @include('pembeliandetail.formedit')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-success edit" data-dismiss="modal">Update</button>
                    <button type="button" class="btn btn-sm btn-warning" data-dismiss="modal">Close</button>
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
                    <h6 class="text-center">Apakah kamu sudah yakin ingin menghapus?</h6>
                    <br />
                    @include('pembeliandetail.formdelete')   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger delete" data-dismiss="modal">Delete</button>
                    <button type="button" class="btn btn-sm btn-warning" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<div class="form-group row">
    <div class="col-md-8 col-md-offset-4">
        <button type="submit" class="btn btn-sm btn-info" name="submitbutton" value="save">SAVE</button>
        <button type="submit" class="btn btn-sm btn-info" name="submitbutton" value="savelock">SAVE & Lock</button>
        @if($fs == 'new')
            <a href="{{ asset('pembelian') }}" class="btn btn-sm btn-info" id='btnCancel'>Cancel</a>
        @else
            <a href="{{ asset('pembelian') }}" class="btn btn-sm btn-info">Close</a>
        @endif
    </div>
</div>

@section ('scripts')
<script src="{{ asset('js/webprint.js') }}"></script>
<script src="{{ asset('terbilang/jTerbilang.js') }}"></script>
<script>
    var detail = '';
    $('#dot').change(function () {
        var date = new Date($('#dot').val());
          month = date.getMonth() + 1;
          year = date.getFullYear();
          bank = $('#bank').val();
        $.ajax({
            type: 'GET',
            url: '/getInvPemb',
            data: {
                'mon': month,
                'year': year,
            },
            cache: false,
            success: function(data) {
                    var t = data.invno;
                    if(parseInt(month) < 10) month = "0" + month;
                    year = year.toString().substring(2,4);
                    if(t < 10) {t = '00' + t + "/" + month + "-" + year;}
                    else if (t < 100) {t = '0' + t;}
                    $('#invoices_no').val(t);
                }
        })
    });

    if( $('input#msg').length )
    {toastr.error($('input#msg').val(),'Error Message', {timeOut: 2000});}

    $("input#kuantiti1,input#kuantiti2,input#nominal,input#diskon,input#kuantiti1_edit,input#kuantiti2_edit,input#nominal_edit,input#diskon_edit").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            $("#errmsg").html("Hanya boleh angka").show().fadeOut(3000);
            return false;
        }
    });

    $('input#invoices_no').change(function() {
        $('a#btnCancel').attr('href','/invoicecancel/'+$('input#invoices_no').val());
    });

    function myTerbilang() {
        $('#totalnominal').terbilang({
            style : 3,
            input_type : "text",
            output_div : "terbilang-output",
            akhiran : " "
          });
        var word = ["Nol","Satu","Dua","Tiga","Empat","Lima","Enam","Tujuh","Delapan","Sembilan"];
        var sen = $('#totalnominal').html().split('.')[1];
        sen = parseInt(sen); 
        var senstring = "dan " + word[Math.floor(sen/10)] + " ";
        senstring += word[sen%10] + " Rupiah";
        $('#terbilang-output').html($('#terbilang-output').html()+senstring);
        $('#aiw').val($('#terbilang-output').html());
    }
    /*function myTerbilang() {
        $('#totalnominal').terbilang({
            style : 3,
            input_type : "text",
            output_div : "terbilang-output",
            akhiran : "Rupiah"
          });
        $('#aiw').val($('#terbilang-output').html());
    }*/
    $(document).ready(function () {
        $("#totalnominal").val($('#totalnominal').html());
        if($('#nominals').val() != 0) myTerbilang();
    });
    
    $( "#q_add" ).autocomplete({
        source: "http://"+location.hostname+ "/search/barang",
        minLength: 3,
        select: function(event, ui) {
            $('#q_add').val(ui.item.value);
            $('#kode_brg_add').val(ui.item.id);
            $('#description_add').val(ui.item.value.slice(0, ui.item.value.length-13));
        }
    });

    $( "#q_edit" ).autocomplete({
        source: "http://"+location.hostname+ "/search/barang",
        minLength: 3,
        select: function(event, ui) {
            $('#q_edit').val(ui.item.value);
            $('#kode_brg_edit').val(ui.item.id);
            $('#description_edit').val(ui.item.value.slice(0, ui.item.value.length-13));
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
        				$('#kosong').html('');
                        $('#description_add').val('');
                        $('#kuantiti1').val('1');
                        $('#kuantiti2').val('1');
                        $('#nominal').val('1');
                        $('#diskon').val('0');
                        $('#q_add').val('');
                        $('#kode_brg_add').val('');
                        $('#addModal').modal('show');
                        $('#hrg_sat').val('');
        /*if($('input#invoices_no').val().length >= 4 )
        {
            $.ajax({
                type: 'GET',
                url: '/checkInvNo/'+$('input#invoices_no').val(),
                cache: false,
                success: function(data) {
                    if(data.result == 'eligible') {
                        $('.modal-title').text('Add Description');
                        $('#description_add').val('');
                        $('#nominal').val('');
                        $('#q_add').val('');
                        $('#kode_brg_add').val('');
                        $('#addModal').modal('show');
                    }
                    else {
                        toastr.error('Invoice number already exist.','Error',{timeOut: 2000});
                    }
                }
            });
        }*/
    });

    $("#kuantiti1,#kuantiti2,#nominal,#diskon,#kuantiti1_edit,#kuantiti2_edit,#nominal_edit,#diskon_edit").blur(function () {
        var qty1 = $("#kuantiti1").val();
        var qty2 = $("#kuantiti2").val();
        var harga = $("#nominal").val();
        var diskon = $("#diskon").val();
        var hrg_sat = (parseFloat(harga).toFixed(2) - parseFloat(diskon).toFixed(2)) / (parseInt(qty1) * parseInt(qty2));
        $("#hrg_sat").val(parseFloat(hrg_sat).toFixed(2));
    });

    $("#kuantiti1_edit,#kuantiti2_edit,#nominal_edit,#diskon_edit").blur(function () {
        var qty1 = $("#kuantiti1_edit").val();
        var qty2 = $("#kuantiti2_edit").val();
        var harga = $("#nominal_edit").val();
        var diskon = $("#diskon_edit").val();
        var hrg_sat = (parseFloat(harga).toFixed(2) - parseFloat(diskon).toFixed(2)) / (parseInt(qty1) * parseInt(qty2));
        $("#hrg_sat_edit").val(parseFloat(hrg_sat).toFixed(2));
    });

    $('.modal-footer').on('click', '.add', function() {
        if ($('#description_add').val() == "") {
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
            url: '/pembeliandetail',
            data: {
                '_token': $('input[name=_token]').val(),
                'id_barang': $('#kode_brg_add').val(),
                'qty1': $('#kuantiti1').val(),
                'qty2': $('#kuantiti2').val(),
                'hrg_tot': $('#nominal').val(),
                'diskon': $('#diskon').val(),
                'hrg_sat': $('#hrg_sat').val(),
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
                    var hrgtot = parseFloat(data.hrg_tot).toFixed(2);var diskon = parseFloat(data.diskon).toFixed(2);var nett = hrgtot - diskon;
                    var description = $('#q_add').val();
                    toastr.success('Detail pembelian berhasil disimpan!', 'Success Alert', {timeOut: 2500});
                    $('#postTable').append("<tr class='item" + data.id + "'><td>" + description + "</td><td>" + data.qty1 + "</td><td>" + data.qty2 + "</td><td>" + hrgtot +"</td><td>" + diskon + "</td><td>" + nett + "</td><td><button type='button' class='edit-modal btn btn-info btn-sm fa fa-pencil-square-o' data-id='" + data.id + "' data-title='" + description + "' data-content1='" + data.qty1 + "' data-content2='" + data.qty2 + "' data-content3='" + hrgtot + "' data-content4='" + diskon + "' data-content5='" + nett + "' data-content6='" + data.id_barang + "'></button> <button type='button' class='delete-modal btn btn-danger btn-sm fa fa-trash' data-id='" + data.id + "' data-title='" + description + "' data-content='" + nett + "'></button></td></tr>");
                    $('#totalnominal').html(parseFloat($('#totalnominal').html()) + parseFloat(nett));
                    $('#totalnominal').html(Number.parseFloat($('#totalnominal').html()).toFixed(2));
                    $('#nominals').val($('#totalnominal').html());
                    $("#totalnominal").val($('#totalnominal').html());
                    myTerbilang();
                    detail = detail + data.id + "|";
                    $('#iddet').val(detail);
                }
            },
        });
    }
    });
    //End add description modal

    //Edit description and nominal invoice
    $(document).on('click', '.edit-modal', function() {
            $('.modal-title').text('Edit Barang Pembelian');
            $('#q_edit').val($(this).data('title'));
            $('#kuantiti1_edit').val($(this).data('content1'));
            $('#kuantiti2_edit').val($(this).data('content2'));
            $('#nominal_edit').val($(this).data('content3'));
            $('#kode_brg_edit').val($(this).data('content6'));
            $('#diskon_edit').val($(this).data('content4'));
            id = $(this).data('id');
            
            var hrgtot = parseFloat($(this).data('content3')).toFixed(2);var diskon = parseFloat($(this).data('content4')).toFixed(2);var nett = hrgtot - diskon;
            var qty1 = parseInt($(this).data('content1'));var qty2 = parseInt($(this).data('content2')); var qty = qty1 * qty2;
            nom_edit = nett;
            $('#hrg_sat_edit').val(nett / qty);
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
            url: "/pembeliandetail/"+id,
            data: {
                '_token': $('input[name=_token]').val(),
                'id_barang': $('#kode_brg_edit').val(),
                'qty1': $('#kuantiti1_edit').val(),
                'qty2': $('#kuantiti2_edit').val(),
                'hrg_tot': $('#nominal_edit').val(),
                'diskon': $('#diskon_edit').val(),
                'hrg_sat': $('#hrg_sat_edit').val(),
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
                    var hrgtot = parseFloat(data.hrg_tot).toFixed(2);var diskon = parseFloat(data.diskon).toFixed(2);var nett = hrgtot - diskon;
                    var description = $('#q_edit').val();
                    toastr.success('Detail pembelian berhasil di-update!', 'Success Alert', {timeOut: 2500});
                    $('.item' + data.id).replaceWith("<tr class='item" + data.id + "'><td>" + description + "</td><td>" + data.qty1 + "</td><td>" + data.qty2 + "</td><td>" + hrgtot +"</td><td>" + diskon + "</td><td>" + nett + "</td><td><button type='button' class='edit-modal btn btn-info btn-sm fa fa-pencil-square-o' data-id='" + data.id + "' data-title='" + description + "' data-content1='" + data.qty1 + "' data-content2='" + data.qty2 + "' data-content3='" + hrgtot + "' data-content4='" + diskon + "' data-content5='" + nett + "' data-content6='" + data.id_barang + "'></button> <button type='button' class='delete-modal btn btn-danger btn-sm fa fa-trash' data-id='" + data.id + "' data-title='" + description + "' data-content='" + nett + "'></button></td></tr>");
                    $('#totalnominal').html(parseFloat($('#totalnominal').html()) + parseFloat(nett) - nett);
                    $('#totalnominal').html(Number.parseFloat($('#totalnominal').html()).toFixed(2));
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
            url: "/pembeliandetail/"+id,
            data: {
                '_token': $('input[name=_token]').val(),
            },
            success: function(data) {
                detail.replace(id+"|","");
                var hrgtot = parseFloat(data.hrg_tot).toFixed(2);var diskon = parseFloat(data.diskon).toFixed(2);var nett = hrgtot - diskon;
                toastr.success('Detail pembelian berhasil dihapus!', 'Success Alert', {timeOut: 2500});
                $('#totalnominal').html(parseFloat($('#totalnominal').html()) + parseFloat(nett));
                $('#totalnominal').html(Number.parseFloat($('#totalnominal').html()).toFixed(2));
                $('#nominals').val($('#totalnominal').html());
                $("#totalnominal").val($('#totalnominal').html());
                myTerbilang();
                $('.item' + data['id']).remove();
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