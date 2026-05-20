<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Config;

class ForgotPasswordController extends \App\Http\Controllers\Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | without sending emails. The user inputs their email, and if it exists,
    | they are redirected directly to the reset password form.
    |
    */

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
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.passwords.forgot');
    }

    /**
     * Validate email exists and redirect to reset form WITHOUT sending email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $email = $request->input('email');

        // Check if email exists in customers table
        $customer = Customer::where('email', $email)->first();

        if (!$customer) {
            return back()->withErrors(['email' => 'Este e-mail não está registado.']);
        }

        // Generate a random token
        $token = Str::random(64);

        // Store token in password_resets table (optional, for tracking)
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'token' => bcrypt($token),
                'created_at' => now()
            ]
        );

        // Redirect directly to reset form with token and email
        return Redirect::route('account.password.reset', ['token' => $token, 'email' => $email])
                       ->with('status', 'E-mail confirmado! Por favor, defina uma nova palavra-passe.');
    }
}