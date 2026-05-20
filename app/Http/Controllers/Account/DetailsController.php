<?php

namespace App\Http\Controllers\Account;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Services\CustomerService;

class DetailsController extends \App\Http\Controllers\Controller
{
    /**
     * The layout that should be used for responses
     * 
     * @var string 
     */
    protected $layout = 'layouts.account';

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'details';
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private CustomerService $customerService)
    {
    }
    
    /**
     * Customer details index controller
     * 
     * @return type
     */
    public function index() {

        return $this->setContent('account.details.index');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customerUpdate(Request $request) {
        
        $input = $request->all();
        
        $customer = Auth::guard('customer')->user();

        try {
            $this->customerService->updateAccountDetails($customer, $input);
            return Redirect::back()->with('success', 'Dados alterados com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
    }
    
    /**
     * Update customer login details
     * 
     * @param Request $request
     * @return type
     */
    public function loginUpdate(Request $request) {
        
        $input = $request->only('display_name', 'email', 'delete_photo');

        $customer = Auth::guard('customer')->user();

        $rules = [
            'email' => 'required|email|unique:customers,email,' . $customer->id
        ];
        
        $validator = Validator::make($input, $rules);
        
        if ($validator->passes()) {
            try {
                $this->customerService->updateAccountLogin($customer, $request, $input);
                return Redirect::back()->with('success', 'Dados de login atualizados com sucesso.');
            } catch (\Exception $e) {
                return Redirect::back()->withInput()->with('error', $this->mapLoginUpdateError($e));
            }
        }
        
        return Redirect::back()->with('error', $validator->errors()->first());
    }

    
    /**
     * Update account password
     * 
     * @param Request $request
     * @return type
     */
    public function passwordUpdate(Request $request) 
    {  
        $input = $request->only('current_password', 'password', 'password_confirmation');

        $customer = Auth::guard('customer')->user();
        
        $rules = [
            'current_password' => 'required',
            'password'         => 'required|confirmed'
        ];
        
        $validator = Validator::make($input, $rules);
        
        if ($validator->passes()) {
            $this->customerService->updateAccountPassword($customer, $input['password']);
        
            return Redirect::back()->with('success', 'Palavra-passe alterada com sucesso.');
        }
        
        return Redirect::back()->with('error', $validator->errors()->first());
    }

    /**
     * Translate service errors for login update.
     *
     * @param \Exception $exception
     * @return string
     */
    private function mapLoginUpdateError(\Exception $exception)
    {
        switch ($exception->getMessage()) {
            case 'image_upload_failed':
                return 'Não foi possível alterar a imagem do perfil.';
            default:
                return $exception->getMessage();
        }
    }
}