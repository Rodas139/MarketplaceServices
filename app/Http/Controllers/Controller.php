<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'account.layouts.master';

    /**
     * The main menu option that should be used for responses
     *
     * @var string
     */
    protected $menuOption = 'home';

    /**
     * The sidebar menu option that should be used for responses
     *
     * @var string
     */
    protected $sidebarActiveOption = '';

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = view($this->layout);
        }

        view()->share('auth', Auth::guard('customer')->user());
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function setContent($view, $data = [])
    {
        if (!is_null($this->layout)) {
            view()->share('menuOption', $this->menuOption);
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
}
