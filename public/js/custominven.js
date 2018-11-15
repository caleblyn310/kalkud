/**
 * Created by caleb on 11/09/2017.
 */

$(document).ready(function () {
    var loc = window.location.pathname;

    //called when key is pressed in textbox
    $("#quantity").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            $("#errmsgqty").html("Hanya boleh angka").show().fadeOut(2500);
            return false;
        }
    });

    $("#harga").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            $("#errmsghrg").html("Hanya boleh angka").show().fadeOut(2500);
            return false;
        }
    });

    $("#maks").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            $("#errmsgmaks").html("Hanya boleh angka").show().fadeOut(2500);
            return false;
        }
    });

    $("#quantity,#harga,#maks").blur(function () {
        var qty = $("#quantity").val();
        var harga = $("#harga").val();
        var maks = $("#maks").val();
        var total = parseInt(qty) * parseInt(harga);
        $("#total").val(total);
        if(maks != 0) {
            var penyusutan = parseInt(total) / (parseInt(maks) * 12);
            $("#penyusutan").val(parseInt(penyusutan));}
    });

    $("#kode_d_ger").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
            //display error message
            $("#errmsg").html("Hanya boleh angka").show().fadeOut("slow");
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
        source: "http://"+window.location.hostname+"/search/autocomplete",
        minLength: 3,
        select: function(event, ui) {
            $('#q').val(ui.item.value);
            $('#kode_d_ger').val(ui.item.id);
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

    $('div.alert').not('.alert-important').delay(3000).slideUp(300);
});