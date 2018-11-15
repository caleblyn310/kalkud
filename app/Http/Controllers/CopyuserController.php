<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\User;
use App\Customuser;

class CopyuserController extends Controller
{
    /**
     * Update the password for the user.
     *
     * @param  Request  $request
     * @return Response
     */
    /*public function update(Request $request)
    {
        // Validate the new password length...

        $request->user()->fill([
            'password' => Hash::make($request->newPassword)
        ])->save();
    }*/
    protected function create()
    {
        $td = Customuser::all();
        $i = 0;
        foreach ($td as $t)
        {
            User::create([
                'name' => $t->nama,
                'email' => 'abcde'.$i++.'@gmail.com',
                'password' => bcrypt($t->password),
                'kode_unit' => $t->kode_cabang,
            ]);
        }

        redirect('kaskecil');
    }
}