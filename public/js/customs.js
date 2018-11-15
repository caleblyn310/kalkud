$(document).ready(function(){

    $('#reader').html5_qrcode(function(data){
        
        $('#message').html('<span class="text-success send-true">Scanning now....</span>');
        if (data!='') {
                 $.ajax({
                    type: "GET",
                    cache: false,
                    url : "http://"+window.location.hostname+"/qrRead",
                    data: {data:data},
                        success: function(data) {
                          console.log(data);
                          if (data==1) {
                            $('#read').html(data);
                            return confirm('There is user with this qr code'); 
                          }else{
                           return confirm('There is no user with this qr code'); 
                          }
                          // 
                        }
                    })
        }else{return confirm('There is no  data');}
    },
    function(error){
       $('#message').html('Scaning now ....'  );
    }, function(videoError){
       $('#message').html('<span class="text-danger camera_problem"> there was a problem with your camera </span>');
    }
);

	/*var cats = $("input[name='cat']:checked").val();

	if(cats == "S")
	{
		$("select[name='id_unit']").hide();
		$("input[name='tahun']").hide();
		$("label[name='id_unit']").hide();
		$("label[name='tahun']").hide();
	}

	$("input[type='radio']").click(function(){
            var radioValue = $("input[name='cat']:checked").val();
            if(radioValue == "A"){
                $("select[name='id_unit']").show();
				$("input[name='tahun']").show();
				$("label[name='id_unit']").show();
				$("label[name='tahun']").show();
            }
            else
            {
            	$("select[name='id_unit']").hide();
				$("input[name='tahun']").hide();
				$("label[name='id_unit']").hide();
				$("label[name='tahun']").hide();
            }
        });*/


	$("#telepon").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            $("#errmsg").html("Hanya boleh angka").show().fadeOut(5000);
            return false;
        }
    });

    $("#tahun").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            $("#errmsg2").html("Hanya boleh angka").show().fadeOut("slow");
            return false;
        }
    });

    $('#indexresend').click(function(){
    	document.getElementById("mode").value = "resend";
    });

    $('#indexedit').click(function(){
    	document.getElementById("mode").value = "edit";
    });

    $('#add').click(function(){       
    	var slc = '<div class="control-group input-group" style="padding-bottom:15px">';
    	slc = slc + '<select class="custom-select" name="jenjang[]" id="jenjang[]" form="formAlumni">';
        slc = slc + '<option value="TKK Ciateul">TKK Ciateul (Pasundan)</option>';
		slc = slc + '<option value="TKK Kopo Permai">TKK Kopo Permai</option>';
		slc = slc + '<option value="TKK TKI 2">TKK Taman Kopo Indah 2</option>';
		slc = slc + '<option value="SDK Ciateul">SDK Ciateul</option>';
		slc = slc + '<option value="SDK Kopo Permai">SDK Kopo Permai</option>';
		slc = slc + '<option value="SDK TKI 2">SDK TKI 2</option>';
		slc = slc + '<option value="SMPK Mekar Wangi">SMPK Mekar Wangi (Ciateul)</option>';
		slc = slc + '<option value="SMPK Kopo Permai">SMPK Kopo Permai</option>';
		slc = slc + '<option value="SMAK Mekar Wangi">SMAK Mekar Wangi (Ciateul)</option></select>';
		slc = slc + '<select class="custom-select" name="tahun[]" id="tahun[]" form="formAlumni">';
		var d = new Date(); d = d.getFullYear();
		for (thn = 1970; thn < d; thn++) { slc = slc + '<option value="'+thn+'">'+thn+'</option>';} 
		slc = slc + '</select>';
		slc = slc + '<div class="input-group-btn"><a class="btn btn-danger" id="remove"><i class="glyphicon glyphicon-remove"></i>x</a></div></div>';

        $(slc).appendTo('#box');   
    });
    
    
    
    $('body').on('click','#remove',function(){
        
        $(this).parents('.control-group').remove();

        
    });

    $('div.alert').not('.alert-important').delay(4000).slideUp(500);
})