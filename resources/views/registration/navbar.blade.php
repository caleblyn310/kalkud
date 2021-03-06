<nav class="navbar sticky-top navbar-expand-sm">
    <div class="container-fluid">
        <!-- Collapsed Hamburger -->
        <button type="button" class="navbar-toggler collapsed" data-toggle="collapse"
                data-target="#app-navbar-collapse"> <span class="sr-only">Toggle Navigation</span>
            &#x2630;</button>
        <!-- Branding
        Image --> <a class="navbar-brand" href="{{ url('/reg') }}">HWCS</a>
        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                @if(Auth::check() && Auth::user()->kode_unit < 50) &#xA0;
                @if(Auth::user()->id != 19)
                <li class="nav-item"><a href="{{ url('cheque') }}" class="nav-link" id="mnCheque">Cheque</a></li>
                <li class="dropdown nav-item"><a id="mnLog" href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">Login<span class="caret"></span></a>
                    <div class="dropdown-menu" id="login-content">
                        <form>
                            <fieldset id="inputs">
                                <input class="form-control form-control-sm" type="email" placeholder="Type your email address" required id="userName">
                                <input class="form-control form-control-sm" type="password" placeholder="Type your password" required id="userPass">
                                <input type="submit" class="btn btn-sm btn-success" value="Log in">
                            </fieldset>
                        </form>
                    </div>
                </li>

                <li class="nav-item"><a href="{{ url('convertbca') }}" class="nav-link">Convert BCA</a></li>
                @endif
                @if(Auth::user()->kode_unit == 0 )
                <li class="nav-item"><a href="{{ url('invoices') }}" class="nav-link">Invoices</a></li>
                <li class="nav-item"><a href="{{ url('searchtransaction') }}" class="nav-link">Search Transaction</a></li>@endif
                @endif
                @if(Auth::check() && Auth::user()->kode_unit >= 50)
                <li class="nav-item"><a href="{{ url('inventory') }}" class="nav-link">Inventory</a></li>
                @endif
                @if(Auth::check() && Auth::user()->kode_unit == 100)
                <li class="dropdown nav-item"><a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">Transaction<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item">
                        <a href="{{ url('jurnaladmin/create') }}">Add Transaction</a></li>
                        <li class="dropdown-item">
                        <a href="{{ url('ja/edit') }}">Search Transaction</a></li>
                    </ul>
                </li>
                <li class="dropdown nav-item"><a href="#" class="nav-link" id="recalculate">Recalculate</a></li>
                <li class="dropdown nav-item"><a href="{{ url('getSAA') }}" class="nav-link" id="recalculate">SAA</a></li>
                <li class="dropdown nav-item"><a href="{{ url('boa') }}" class="nav-link" id="recalculate">BoA</a></li>
                <li class="dropdown nav-item"><a href="{{ url('downloadbpb') }}" class="nav-link" id="dlBPB">Download BPB</a></li>
                @endif
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav ml-auto">
                <!-- Authentication Links -->@if (Auth::guest())
                    <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Login</a>
                    </li>@else
                    <li class="dropdown nav-item"> <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                            Hallo, {{ Auth::user()->fullname }}<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item"> <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}"
                                  method="POST" style="display: none;">{{ csrf_field() }}</form>
                        </li>
                    </ul>
                    </li>@endif
                </ul>
        </div>
    </div>
</nav>