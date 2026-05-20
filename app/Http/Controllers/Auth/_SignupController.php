<?php

namespace App\Http\Controllers\Auth;

use App\Models\Customer;
use Illuminate\Http\Request;
use Cart, Response, Redirect, Auth;

class SignupController extends \App\Http\Controllers\Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){}

    /**
     * class of main menu to be activated
     * 
     * @var string 
     */
    protected $menuOption = 'cart';

    
    /**
     * Signup
     * 
     * @return type
     */
    public function signup(Request $request)
    {
        $input = $request->all();

        $customer = Customer::firstOrNew([
            'email' => $input['email']
        ]);

        if($customer->exists) {
            return Redirect::back()->with('error', 'Não é possível concluir o registo. O e-mail indicado já se encontra registado.');
        }

        if($customer->validate($input)){
            $customer->fill($input);
            $customer->save();
        }

        return Redirect::back()->with('success', 'Registo concluido com sucesso. Já pode iniciar sessão com a sua conta.');
    }

}
