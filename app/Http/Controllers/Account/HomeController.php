<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Services\OrderService;
use Illuminate\Support\Facades\Redirect;

class HomeController extends \App\Http\Controllers\Account\Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private OrderService $orderService)
    {
    }

    public function index(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $orders = $this->orderService->getCustomerOrders($customer->id, $request);
        $data = compact('orders');

        return $this->setContent('account.index', $data);
    }

    public function changePassword(Request $request)
    {
        return view('account.partials.change_password')->render();
    }

    public function updatePassword(Request $request)
    {
        try {
            $input = $request->all();
            

            if (empty($input['password']) || empty($input['password_confirmation'])) {
                return Redirect::back()->with('error', 'A palavra-passe e a confirmação são obrigatórias.');
            }

            if ($input['password'] != $input['password_confirmation']) {
                return Redirect::back()->with('error', 'A nova palavra-passe e a confirmação da mesma não coincidem.');
            }

            if (strlen($input['password']) < 6) {
                return Redirect::back()->with('error', 'A palavra-passe deve ter pelo menos 6 caracteres.');
            }

            $customer = Customer::find(Auth::guard('customer')->user()->id);
            
            if (!$customer) {
                return Redirect::back()->with('error', 'Cliente não encontrado.');
            }

            $customer->password = bcrypt($input['password']);
            $customer->save();

            return Redirect::back()->with('success', 'Palavra-passe alterada com sucesso. Já pode utilizar a nova palavra-passe para fazer login.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'Erro ao alterar a palavra-passe: ' . $e->getMessage());
        }
    }

    public function updateLanguage($lang)
    {
        $customer = Customer::find(Auth::guard('customer')->user()->id);
        $customer->current_lang = $lang;
        $customer->save();

        return redirect()->back();
    }

    public function profile()
    {
        $customer = Auth::guard('customer')->user();

        return $this->setContent('account.details.index', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|digits:9'
        ], [
            'name.required' => 'O nome é obrigatório.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'address.max' => 'A morada não pode ter mais de 255 caracteres.',
            'phone.digits' => 'O telefone deve ter 9 dígitos.'
        ]);

        $customer = Customer::find(Auth::guard('customer')->user()->id);

        if (!$customer) {
            return Redirect::back()->with('error', 'Cliente não encontrado.');
        }

        $customer->name = $request->input('name');
        $customer->address = $request->input('address');
        $customer->phone = $request->input('phone');
        $customer->save();

        return Redirect::back()->with('success', 'Perfil atualizado com sucesso.');
    }
}
