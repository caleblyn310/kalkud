/**
 * Created by caleb on 11/09/2017.
 */

$(document).ready(function () {
    var loc = window.location.pathname;
    var searchref = "/search/autocomplete";

    //if($('#flashmsg').hasClass("alert")) { toastr.success('Successfully added Blabla!', 'Success Alert', {timeOut: 2500}); }

    if(loc.search("search/transaction") > 0) {
        $('a#btnCancel').attr('href','http://'+window.location.hostname+'/jurnaladmin/edit/transaction');
    }

    if(loc.endsWith("datareim")) {
        var eNone = $('a#printPdf').attr('href');
        var eNtwo = eNone.replace("storage", '');
        var selectedData = $("#namafile option:selected").val();

        $('a#printPdf,a#editKK').attr('href', '#');
        $('a#printPdf,a#editKK').attr('target', '');
    }
    else if(loc.includes("cheque")) {
        if(loc.endsWith("create")) $("a#chequeCancel").attr('href',loc.replace("/create",""));
        else if(loc.endsWith("edit")) $("a#chequeCancel").attr('href',loc.replace("edit","cancel"));
    }

    //called when key is pressed in textbox
    $("#nominal,#debet").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
            //display error message
            $("#errmsg").html("Hanya boleh angka").show().fadeOut(3000);
            return false;
        }
    });

    $("#kredit").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            $("#errmsg2").html("Hanya boleh angka").show().fadeOut(3000);
            return false;
        }
    });

    $("#kode_d_ger,#no_account").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
            //display error message
            $("#errmsg").html("Hanya boleh angka").show().fadeOut(3000);
            return false;
        }
    });

    $("a#printPdf").click(function (e) {
        //if the letter is not digit then display error and don't type anything
        if ($("#namafile option:selected").val().endsWith('edit')) {
            //display error message
            $("#errmsg").html("Reimburse dalam mode EDIT dan belum di save").show().fadeOut(10000);
            return false;
        }
    });

    $("a#editKK").click(function (e) {
        //if the letter is not digit then display error and don't type anything
        if ($("#namafile option:selected").val().endsWith('print')) {
            //display error message
            return confirm("Apakah Bapak/Ibu yakin mau EDIT??");
        }
    });

    $( "#q" ).autocomplete({
        source: "http://"+location.hostname+searchref,
        minLength: 3,
        select: function(event, ui) {
            $('#q').val(ui.item.value);
            $('#kode_d_ger').val(ui.item.id);
        }
    });

    $( "#q2" ).autocomplete({
        source: "http://"+location.hostname+searchref,
        minLength: 3,
        select: function(event, ui) {
            $('#q2').val(ui.item.id);
            $('#Kontra_acc').val(ui.item.id);
        }
    });

    $( "#qq" ).autocomplete({
        source: "http://"+location.hostname+searchref,
        minLength: 3,
        select: function(event, ui) {
            $('#qq').val(ui.item.id);$('#No_account').val(ui.item.id);
        }
    });

    $("select#namafile").change(function(){
        //var mode = $("#namafile option:selected").text();
        var selectedData = $("#namafile option:selected").val();
        if(selectedData != '' && selectedData.endsWith("print"))
        {   $('a#printPdf').attr('href',eNone+'/'+selectedData.replace("|print",""));$('a#printPdf').attr('target','_blank');
            $('a#editKK').attr('href',eNtwo+'datareim/'+selectedData.replace(".pdf|print","")+'/edit');
            $('a#editKK').attr('target','_self');}
        else if (selectedData != '' && selectedData.endsWith('edit'))
        {   $('a#printPdf').attr('href','#');$('a#printPdf').attr('target','');
            $('a#editKK').attr('href',eNtwo+'datareim/'+selectedData.replace(".pdf|edit","")+'/edit');
            $('a#editKK').attr('target','_self');}
        else if(selectedData == '')
        {$('a#printPdf,a#editKK').attr('href','#');$('a#printPdf,a#editKK').attr('target','');}
    });

    $("select#data_reimburse").change(function(){
        var selectedDR = $("#data_reimburse option:selected").val();
        if(selectedDR != '')
        {
            var res = selectedDR.split("|");
        document.getElementById("nominal").value = res[1];
        }
        else
        {
            document.getElementById("nominal").value = 'silakan pilih salah satu data reimburse';
        }
    });

    $('div.alert').not('.alert-important').delay(3500).slideUp(200);

    //MenuCheque
    $('#mnCheque').click(function () {
        spinner.show();
    })
    //End

    //Untuk tombol Reimburse
    $('#printRM').click(function () {
        var md = ''; var fn = '';
        var host = window.location.hostname; var temp = '';
        if (!host.includes('localhost')) {temp = '/kaskecil';}
        $.ajax({
            type: 'GET',
            url: temp + '/getreimburse',
            cache: false,
            success: function (resp) {
                fn = resp[0].namafile;
                md = resp[0].mode;

                if (md == 'print') {
                if(host.includes('localhost'))
                    window.open('/storage/' + fn);
                else
                    window.open('/kaskecil/storage/' + fn);
                }
                else {
                    alert('Reimburse yang berjalan dalam mode EDIT.\nSilakan menyelesaikan proses EDIT terlebih dahulu.');
                }
            }
        });
        
        
    });

    $('#editRM').click(function () {
        var ans = true; var md = ''; var fn = '';
        var host = window.location.hostname; var temp = '';
        if (!host.includes('localhost')) {temp = '/kaskecil';}
        $.ajax({
            type: 'GET',
            url: temp + '/getreimburse',
            cache: false,
            success: function (data) {
                fn = data[0].namafile;
                md = data[0].mode;
                //alert(fn);
                if (md == 'print') {
                    ans = confirm('Apakah Anda yakin hendak melakukan EDIT?');
                }

                if (ans) {
                    if(host.includes('localhost')) 
                        window.open('/datareim/' + fn.replace(".pdf","") + '/edit','_self');
                    else
                        window.open('/kaskecil/datareim/' + fn.replace(".pdf","") + '/edit', '_self');
                }
            }
        });

        
    });
    //Berakhir disini

    //Dropdown Login
    $('#inputs input').click(function(e) {
        $('#login-content').css('display','block');
    });
    $('#inputs input').focusout(function(e) {
        $('#login-content').css('display','none');
    });
    //Berakhir disini

    //Check 

    /*//Add description and nominal invoice
    $(document).on('click', '.add-modal', function() {
        $('.modal-title').text('Add Description');
        $('#description').val('');
        $('#nominal').val('');
        $('#q').val('');
        $('#kode_d_ger').val('');
        $('#addModal').modal('show');
    });
    $('.modal-footer').on('click', '.add', function() {
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
                    $('#postTable').append("<tr class='item" + data.id + "'><td>" + data.description + " (" + data.kode_d_ger + ")</td><td>" + data.nominal + "</td><td><button type='button' class='edit-modal btn btn-info btn-sm fa fa-pencil-square-o' data-id='" + data.id + "' data-title='" + data.description + "' data-content1='" + data.nominal + "' data-content2='" + data.kode_d_ger + "'></button> <button type='button' class='delete-modal btn btn-danger btn-sm fa fa-trash' data-id='" + data.id + "' data-title='" + data.description + data.kode_d_ger + "' data-content='" + data.nominal + "'></button></td></tr>");
                    $('#totalnominal').html(parseInt($('#totalnominal').html()) + parseInt(data.nominal));
                    $('#nominals').val($('#totalnominal').html());
                }
            },
        });
    });

    //Edit description and nominal invoice
    $(document).on('click', '.edit-modal', function() {
            $('.modal-title').text('Edit Description');
            $('#description_edit').val($(this).data('title'));
            $('#nominal_edit').val($(this).data('content1'));
            $('#kode_d_ger_edit').val($(this).data('content2'));
            id = $(this).data('id');
            nom_edit = $(this).data('content');
            $('#editModal').modal('show');
        });
    $('.modal-footer').on('click', '.edit', function() {
        $.ajax({
            type: 'PUT',
            url: "/invoicesdetail/"+id,
            data: {
                '_token': $('input[name=_token]').val(),
                'id': id,
                'description': $('#description_edit').val(),
                'nominal': $('#nominal_edit').val()
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
                    $('.item' + data.id).replaceWith("<tr class='item" + data.id + "'><td>" + data.description + " (" + data.kode_d_ger + ")</td><td>" + data.nominal + "</td><td><button type='button' class='edit-modal btn btn-info btn-sm fa fa-pencil-square-o' data-id='" + data.id + "' data-title='" + data.description + "' data-content1='" + data.nominal + "' data-content2='" + data.kode_d_ger + "'></button> <button type='button' class='delete-modal btn btn-danger btn-sm fa fa-trash' data-id='" + data.id + "' data-title='" + data.description + data.kode_d_ger + "' data-content='" + data.nominal + "'></button></td></tr>");
                    $('#totalnominal').html(parseInt($('#totalnominal').html()) + parseInt(data.nominal) - parseInt(nom_edit));
                    $('#nominals').val($('#totalnominal').html());
                }
            }
        });
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
            }
        });
    });*/
});