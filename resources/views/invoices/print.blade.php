<H2>Printing . . . . . . .</H2>
<input type="hidden" value="{{ $invid }}" name="invid" id="invid">
<script src="{{ asset('jqui/external/jquery/jquery.js') }}"></script>
<script src="{{ asset('jqui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('bootstrap4/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/webprint.js') }}"></script>
<script>
    

    $(window).load(function() {
            webprint = new WebPrint(true, {
            relayHost: "127.0.0.1",
            relayPort: "8080",
            readyCallback: function(){
                
            }
        });

        $.ajax({
            type: 'GET',
            url: '/invoices/' + $('#invid').val() + '/print',
            async: false,
            cache: false,
            dataType: "text",
            success: function(data, textStatus, jq) {
                var i = 0;
                for(i = 0; i < 3; i++){
                    webprint.printRaw(data, 'l310');
                    toastr.success('Successfully printed!', 'Success Alert', {timeOut: 2500});
                    setTimeout(function () {
                       window.location.href = "http://"+location.hostname ;
                    }, 3000);
                }
                //window.location.href = "http://"+location.hostname+"/invoices";
            }
        });
    });
</script>