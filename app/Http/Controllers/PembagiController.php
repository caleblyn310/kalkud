<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class PembagiController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //dd('maincontroller');
        if (Auth::user()->id == 19) {
            return redirect('invoices');
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
