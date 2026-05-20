<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Admin\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'admin.layouts.auth';

    public function __construct()
    {
        $this->middleware('guest.admin');
    }

    public function index()
    {
        return $this->setContent('admin.auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ], [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail é inválido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'password.required' => 'A palavra-passe é obrigatória.',
            'password.min' => 'A palavra-passe deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'A confirmação da palavra-passe não coincide.'
        ]);

        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'status' => 'pending'
            ]);

            $role = Role::firstOrCreate([
                'name' => 'prestador',
                'guard_name' => config('auth.defaults.guard', 'web')
            ]);

            $user->assignRole($role);

            return Redirect::route('admin.login')
                ->with('success', 'Conta de prestador criada. Aguarde aprovação.');
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
    }
}
