<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AccountController extends \App\Http\Controllers\Admin\Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * Change current user password
     * 
     * @return type
     */
    public function password(Request $request)
    {

        $input = $request->only(['current_password', 'password', 'password_confirmation']);
        $rules = [
            'current_password' => 'required',
            'password'         => 'required|confirmed'
        ];


        if ($input['password'] != $input['password_confirmation'])
            return Redirect::back()->with('error', 'A nova palavra-passe e a confirmação da mesma não são iguais e é obrigatório o seu preenchimento.');

        if (Validator::make($input, $rules)) {

            $user = Auth::user();

            if (!Hash::check($input['current_password'], $user->password)) {
                return Redirect::back()->with('error', 'A palavra-passe actual está incorreta.');
            }

            $user->password = bcrypt($input['password']);
            $user->uncrypted_password = $input['password'];
            $user->save();

            return Redirect::back()->with('success', 'Palavra-passe alterada com sucesso. Já pode utilizar a nova palavra-passe para iníciar sessão.');
        } else {
            return Redirect::back()->with('error', 'A nova palavra-passe e a confirmação da mesma não são iguais e é obrigatório o seu preenchimento.');
        }
    }
}
