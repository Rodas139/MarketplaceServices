<?php

namespace App\Http\Controllers\Admin;

use App\Models\BroadcastPusher;
use App\Models\Core\Source;
use App\Models\Notification;
use App\Models\Ticket\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Request, Html, Form, View, Setting;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'admin.layouts.master';

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'dashboard';

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $user = Auth::user();

            View::share('sidebarActiveOption', $this->sidebarActiveOption);

            $this->layout = view($this->layout);
        }
    }

    public function callAction($method, $parameters)
    {
        $this->setupLayout();
        $response = call_user_func_array(array($this, $method), $parameters);

        if (is_null($response) && !is_null($this->layout)) {
            $response = $this->layout;
        }

        return $response;
    }

    /**
     * Set content used by the controller.
     *
     * @param string $view
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory | \Illuminate\Contracts\View\View
     */
    public function setContent($view, $data = [])
    {
        if (!is_null($this->layout)) {
            view()->share('sidebarActiveOption', $this->sidebarActiveOption);
            return $this->layout->nest('child', $view, $data);
        }

        return view($view, $data);
    }

    /**
     * Set the layout used by the controller.
     *
     * @param $name
     * @return void
     */
    protected function setLayout($name)
    {
        $this->layout = $name;
    }

    /**
     * Set the layout used by the controller.
     *
     * @param $name
     * @return void
     */
    protected function cache()
    {
        return Setting::get('cache_duration');
    }
}
