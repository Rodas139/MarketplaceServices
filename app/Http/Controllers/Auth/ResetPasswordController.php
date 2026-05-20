<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Config;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | without sending emails. User resets password directly after email verification.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/area-cliente';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Config::set("auth.defaults.passwords", "customers");
        $this->middleware('guest');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $token = null)
    {
        $data = ['token' => $token, 'email' => $request->get('email')];

        return $this->setContent('auth.passwords.reset', $data);
    }

    /**
     * Reset the given user's password without using Password Broker
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $email = $request->input('email');
        $token = $request->input('token');
        $password = $request->input('password');

        // Check if email exists
        $customer = Customer::where('email', $email)->first();

        if (!$customer) {
            return back()->withErrors(['email' => 'E-mail não encontrado.']);
        }

        // Verify token exists in password_resets table
        $resetRecord = DB::table('password_resets')
                        ->where('email', $email)
                        ->first();

        if (!$resetRecord) {
            return back()->withErrors(['token' => 'Token inválido ou expirado.']);
        }

        // Check token expiry (60 minutes)
        $tokenExpiry = $resetRecord->created_at;
        if (now()->diffInMinutes($tokenExpiry) > 60) {
            return back()->withErrors(['token' => 'Token expirado. Por favor, peça um novo.']);
        }

        // Update password
        $customer->update(['password' => Hash::make($password)]);

        // Delete the token after successful reset
        DB::table('password_resets')->where('email', $email)->delete();

        return Redirect::route('account.login')
                       ->with('status', 'Palavra-passe alterada com sucesso! Por favor, inicie sessão.');
    }
}
