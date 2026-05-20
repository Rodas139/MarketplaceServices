<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class ForgotPasswordController extends \App\Http\Controllers\Admin\Controller
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
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->setContent('admin.auth.passwords.forgot');
    }

    /**
     * Validate email exists and redirect to reset form WITHOUT sending email
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $email = $request->input('email');

        // Check if email exists in users table and user has admin or provider role
        $user = User::where('email', $email)
                    ->whereHas('roles', function ($q) {
                        $q->whereIn('name', [
                            config('permission.role.admin'),
                            'prestador'
                        ]);
                    })
                    ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Este e-mail não está registado como administrador ou prestador.']);
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
        return Redirect::route('admin.password.reset', ['token' => $token, 'email' => $email])
                       ->with('status', 'E-mail confirmado! Por favor, defina uma nova palavra-passe.');
    }
}
