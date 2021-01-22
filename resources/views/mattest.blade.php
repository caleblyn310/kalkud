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
    <link href="{{ asset('materialize/css/materialize.min.css') }}" rel="stylesheet">
    <link href="{{ asset('jqui/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fa47/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @yield('css')
    <script>var buff;</script>
</head>
<body>
<nav>
    Membuat Sidenav pada Materialize
  </nav>
  <ul id="slide-out" class="sidenav sidenav-fixed">
    <li class="logo" style="margin-top: 20px; height: 65px;">
        <a href="/" class="brand-logo center" id="logo-container" style="height: 100%;"><img src="logo-skkkb.png" width="65"></a>
    </li>
    <!--<li>
      <div class="user-view">
        <div class="background">
          
        </div>
        <a href="#user"><img class="brand-logo" src="logo-skkkb.png"></a>
        <a href="#name"><span class="orange-text name">John Doe</span></a>
        <a href="#email"><span class="white-text email">jdandturk@gmail.com</span></a>
      </div>
    </li>-->
    <li><a href="#!"><i class="material-icons">cloud</i>Profil</a></li>
    <li><a href="#!">About</a></li>
    <li>
      <div class="divider"></div>
    </li>
    <li><a class="subheader">Ini Sub header</a></li>
    <li><a class="waves-effect" href="#!">Home</a></li>
  </ul>
  <a href="#" data-target="slide-out" class="sidenav-trigger show-on-large"><i class="material-icons">menu</i></a>
  <div class='row'>
              <div class='input-field col s12'>
                <input class='validate' type='email' name='email' id='email' />
                <label for='email'>Enter your email</label>
              </div>
            </div>
<div id="loader"></div>

<!-- Scripts -->
<script src="{{ asset('jqui/external/jquery/jquery.js') }}"></script>
<script src="{{ asset('jqui/jquery-ui.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/materialize/js/materialize.min.js') }}"></script>
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/custominven.js') }}"></script>
@yield('scripts')

<script>
var spinner = $('#loader');

$(document).ready(function () {
    $('.sidenav').sidenav();
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
