<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session, Auth;
use Carbon\Carbon;



class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $prazo = Carbon::now()->subDay();
        $usersLast24hours = User::ignoreAdmins()->where('created_at', '>' , $prazo)->count();

        $data = compact(
            'usersLast24hours'
        );

        return $this->setContent('admin.dashboard.index', $data);
    }

    /**
     * Logout a remote login
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remoteLogout(Request $request, User $user)
    {

        if (Session::has('source_user_id')) {
            $userSource = User::findOrFail(Session::get('source_user_id'));

            Session::forget('source_user_id');

            Auth::login($userSource);

            return Redirect::route('admin.dashboard')->with('success', 'Sessão iniciada com sucesso.');
        }

        return Redirect::back()->with('error', 'Nenhuma sessão remota iniciada.');
    }
}
