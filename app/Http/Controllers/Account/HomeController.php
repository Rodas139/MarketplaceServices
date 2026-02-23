<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Support\Facades\Redirect;

class HomeController extends \App\Http\Controllers\Account\Controller
{
    public function index(Request $request)
    {
        return $this->setContent('account.index');
    }

    public function changePassword(Request $request)
    {
        return view('account.partials.change_password')->render();
    }

    public function updatePassword(Request $request)
    {
        $input = $request->all();
        $customer = Customer::find(Auth::guard('customer')->user()->id);

        if ($input['password'] != $input['password_confirmation']) {
            return Redirect::back()->with('error', 'A nova palavra-passe e a confirmação da mesma não são iguais e é obrigatório o seu preenchimento.');
        }

        $customer->fill($input);
        $customer->save();

        return Redirect::back()->with('success', 'Palavra-passe alterada com sucesso. Já pode utilizar a nova palavra-passe para iníciar sessão.');
    }

    public function updateLanguage($lang)
    {
        $customer = Customer::find(Auth::guard('customer')->user()->id);
        $customer->current_lang = $lang;
        $customer->save();

        return redirect()->back();
    }
}
