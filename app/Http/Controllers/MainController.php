<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->id == 19) {
            return redirect('searchtransaction');
        }
        else if(Auth::user()->id == 27) {
            return redirect('elisa');
        }
        else if(Auth::user()->kode_unit == 0) {
            return redirect('invoices');
        }
        else if(Auth::user()->kode_unit == 25) {
            return redirect('kantin');
        }
        else if(Auth::user()->kode_unit < 50) {
            return redirect('kaskecil');
        }
        else if(Auth::user()->kode_unit == 100) {
            return redirect('cheque');
        }
        else if(Auth::user()->kode_unit == 50) {
            return redirect('inventory');
        }
    }
}
