<nav class="navbar sticky-top navbar-expand-sm">
    <div class="container">
        <!-- Collapsed Hamburger -->
        <button type="button" class="navbar-toggler collapsed" data-toggle="collapse"
                data-target="#app-navbar-collapse"> <span class="sr-only">Toggle Navigation</span>
            &#x2630;</button>
        <!-- Branding
        Image --> <a class="navbar-brand" href="{{ url('/') }}">
            @if(Auth::check() && Auth::user()->kode_unit == 100)
            Accounting
            @elseif(Auth::check() && Auth::user()->kode_unit == 25)
            Canteen
            @elseif(Auth::user()->kode_unit == 0 and Auth::user()->id == 5)
            Finance
            @else
            Kas Kecil
            @endif

        </a>
        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                @if(Auth::check() && Auth::user()->kode_unit < 20 && Auth::user()->id != 5) &#xA0;
                <li class="nav-item"><a href="{{ url('cheque') }}" class="nav-link" id="mnCheque">Cheque</a></li>
                <li class="dropdown nav-item" id="mnRM"><a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">Reimburse<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item">
                        <a href="#" id="printRM">Print</a></li>
                        <li class="dropdown-item">
                        <a href="#" id="editRM">Edit</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a href="{{ url('convertbca') }}" class="nav-link">Convert BCA</a></li>
                <li class="nav-item"><a href="{{ url('laporanka') }}" class="nav-link">Report</a></li>
                <li class="nav-item"><a href="{{ url('transKA') }}" class="nav-link">Transactions</a></li>
                @if(Auth::user()->kode_unit == 0 )
                <li class="nav-item"><a href="{{ url('searchtransaction') }}" class="nav-link">Search Transaction</a></li>@endif
                @endif
                @if(Auth::check() && Auth::user()->kode_unit == 25)
                <li class="nav-item"><a href="{{ url('pembelian') }}" class="nav-link">Pembelian</a></li>
                <li class="nav-item"><a href="{{ url('penjualan') }}" class="nav-link">Penjualan</a></li>
                <li class="dropdown nav-item" id="mnRM"><a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">Laporan<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item">
                        <a href="{{ url('lappembelian') }}">Pembelian</a></li>
                        <li class="dropdown-item">
                        <a href="{{ url('lappenjualan') }}">Penjualan</a></li>
                    </ul>
                </li>
                @endif
                @if(Auth::check() && Auth::user()->kode_unit >= 50 && Auth::user()->id != 28)
                <li class="nav-item"><a href="{{ url('inventory') }}" class="nav-link">Inventory</a></li>
                @endif
                @if(Auth::check() && Auth::user()->kode_unit == 100)
                <li class="nav-item"><a href="{{ url('invoices') }}" class="nav-link">Bank Out</a></li>
                <li class="nav-item"><a href="{{ url('bpenb') }}" class="nav-link">Bank In</a></li>
                @if(Auth::user()->id != 28)
                <li class="dropdown nav-item"><a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">Transaction<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item">
                        <a href="{{ url('jurnaladmin/create') }}">Add Transaction</a></li>
                        <li class="dropdown-item">
                        <a href="{{ url('ja/edit') }}">Search Transaction</a></li>
                        <li class="dropdown-item">
                        <a href="{{ url('mutasibank') }}">Bank Mutation</a></li>
                        <li class="dropdown-item">
                        <a href="{{ url('getAllTrans') }}">Get All Transactions</a></li>
                        <!--<li class="dropdown-item">
                        <a href="{{ url('uangmuka') }}">UM</a></li>-->
                    </ul>
                </li>
                <li class="dropdown nav-item"><a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">Kas Unit<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item">
                        <a href="{{ url('kaskecil') }}">Kaskecil</a></li>
                        <li class="dropdown-item">
                        <a href="#" id="ska">Upload KA</a></li>
                    </ul>
                </li>
                <li class="dropdown nav-item"><a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">Report<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                    <li class="dropdown-item"><a href="#" class="nav-link" id="recalculate">Recalculate</a></li>
                    <li class="dropdown-item"><a href="{{ url('getSAAlama') }}" class="nav-link" id="saa">SAA Lama</a></li>
                    <li class="dropdown-item"><a href="{{ url('getSAAbaru') }}" class="nav-link" id="saa">SAA BARU</a></li>
                    <li class="dropdown-item"><a href="{{ url('boa') }}" class="nav-link" id="boa">BoA</a></li>
                    <li class="dropdown-item"><a href="{{ url('convertbca') }}" class="nav-link" id="convertva">Convert VA BCA</a></li>
                </ul></li>@endif
                <!--<li class="dropdown nav-item"><a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">COA<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                    <li class="dropdown-item"><a href="{{ url('getSAA') }}" class="nav-link" id="saa">ADD</a></li>
                    <li class="dropdown-item"><a href="{{ url('boa') }}" class="nav-link" id="boa">EDIT</a></li>
                    <li class="dropdown-item"><a href="{{ url('convertbca') }}" class="nav-link" id="convertva">Convert VA BCA</a></li>
                </ul>
                </li>-->
                @endif
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav ml-auto">
                <!-- Authentication Links -->@if (Auth::guest())
                    <li class="nav-item"><a href="{{ route('login') }}" class="nav-link"><strong>Log In</strong></a>
                    </li>@else
                    <li class="dropdown nav-item"> <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                            Hallo, {{ Auth::user()->fullname }}<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item"> <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                Log Out</a>
                            <form id="logout-form" action="{{ route('logout') }}"
                                  method="POST" style="display: none;">{{ csrf_field() }}</form>
                        </li>
                    </ul>
                    </li>@endif
                </ul>
        </div>
    </div>
</nav>

@section('scripts')
<script type="text/javascript">
    $('#ska').click(function () {
        if(confirm("Are you sure want to upload 'kas admin' data??")) 
        {spinner.show();
                $.ajax({
                  type: 'GET',
                  url: '/sendKA',
                  success: function(data) {
                    spinner.hide();
                    toastr.success('Kas admin uploaded', 'Success Alert', {timeOut: 3000});
                  }
                });}
    });
</script>
@endsection