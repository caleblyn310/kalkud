<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sekolah Kristen Kalam Kudus Bandung</title>

    <!-- Styles -->
    <link href="{{ asset('bootstrap4/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('jqui/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/customs.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style2.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    @yield('content')
</div>
<div id="footer">
    @include('alumni.footer')
</div>

<!-- Scripts -->
<script src="{{ asset('jqui/external/jquery/jquery.js') }}"></script>
<script src="{{ asset('jqui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('bootstrap4/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jsqrcode-combined.min.js') }}"></script>
<script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
        <script type="text/javascript" src="js/filereader.js"></script>
        <!-- Using jquery version: -->
        <!--
            <script type="text/javascript" src="js/jquery.js"></script>
            <script type="text/javascript" src="js/qrcodelib.js"></script>
            <script type="text/javascript" src="js/webcodecamjquery.js"></script>
            <script type="text/javascript" src="js/mainjquery.js"></script>
        -->
        <script type="text/javascript" src="js/qrcodelib.js"></script>
        <script type="text/javascript" src="js/webcodecamjs.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript">
   
</script>

<script src="{{ asset('js/customs.js') }}"></script>
<script src="{{ asset('js/customqr.js') }}"></script>
<!-- Start of StatCounter Code for Default Guide -->
<script type="text/javascript">
var sc_project=11565859; 
var sc_invisible=1; 
var sc_security="a161a554"; 
var scJsHost = (("https:" == document.location.protocol) ?
"https://secure." : "http://www.");
document.write("<sc"+"ript type='text/javascript' src='" +
scJsHost+
"statcounter.com/counter/counter.js'></"+"script>");
</script>
<noscript><div class="statcounter"><a title="free hit
counter" href="http://statcounter.com/" target="_blank"><img
class="statcounter"
src="//c.statcounter.com/11565859/0/a161a554/1/" alt="free
hit counter"></a></div></noscript>
<!-- End of StatCounter Code for Default Guide -->
</body>
</html>
