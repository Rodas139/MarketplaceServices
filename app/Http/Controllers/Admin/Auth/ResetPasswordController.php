<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ResetPasswordController extends \App\Http\Controllers\Admin\Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'admin.layouts.auth';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest.admin');
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

        return $this->setContent('admin.auth.passwords.reset', $data);
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

        // Check if email exists and belongs to admin or provider
        $user = User::where('email', $email)
                    ->whereHas('roles', function ($q) {
                        $q->whereIn('name', [
                            config('permission.role.admin'),
                            'prestador'
                        ]);
                    })
                    ->first();

        if (!$user) {
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
        $user->update(['password' => Hash::make($password)]);

        // Delete the token after successful reset
        DB::table('password_resets')->where('email', $email)->delete();

        return Redirect::route('admin.login')
                       ->with('status', 'Palavra-passe alterada com sucesso! Por favor, inicie sessão.');
    }
}
