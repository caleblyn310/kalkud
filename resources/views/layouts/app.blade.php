<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('APP_NAME', 'Kas Kecil') }}</title>

    <!-- Styles -->
    <link href="{{ asset('bootstrap4/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('jqui/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fa47/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @yield('css')
    <style type="text/css">
        #loader {
          display: none;
          position: fixed;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          width: 100%;
          background: rgba(0,0,0,0.75) url(../loading-bar_4.gif) no-repeat center center;
          z-index: 10000;
        }
    </style>
    <script>var buff;</script>
</head>
<body>
<div id="app" class="h-100">
    @include('layouts.navbar')
    @yield('content')
</div>
<div id="loader"></div>

<!-- Scripts -->
<script src="{{ asset('jqui/external/jquery/jquery.js') }}"></script>
<script src="{{ asset('jqui/jquery-ui.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('bootstrap4/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/custominven.js') }}"></script>
@yield('scripts')

<script>
var spinner = $('#loader');
$(function() {
  $('#recalculate').click(function(e) {
    e.preventDefault();
    spinner.show();
    $.ajax({
      type: 'GET',
      url: '/recalculate',
      success: function(data) {
        spinner.hide();
        toastr.success('Data recalculated', 'Success Alert', {timeOut: 3000});
      }
    });
  });
});
</script>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5a051b72198bd56b8c03a4f3/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
</body>
</html>
