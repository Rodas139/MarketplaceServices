<?php

namespace App\Http\Controllers\Admin\Customers;

use App\Http\Controllers\Admin\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use Mail, DB;

class CustomersController extends Controller
{

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'customers';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['can:admin_customers']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $managers = User::isActive()
            ->pluck('name', 'id')
            ->toArray();

        $data = compact(
            'managers',
        );

        return $this->setContent('admin.customers.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        $action = 'Novo Cliente';

        $customer = new Customer;
        $customer->code = $customer->setCode(false);
        //$customer->bank_mandate = $customer->setBankMandateCode(false);
        $customer->payment_condition = '30d';

        $formOptions = array('route' => array('admin.customers.store'));

        $managers = User::isActive()
            ->pluck('name', 'id')
            ->toArray();

        $vatBlocked = false;

        if ($request->get('prospect')) {
            $action = 'Novo Prospecto';
            $customer->is_prospect = 1;
        }
        $data = compact(
            'action',
            'formOptions',
            'customer',
            'managers',
            'vatBlocked'
        );

        return $this->setContent('admin.customers.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $customer = new Customer();

        return $this->update($request, $customer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //    public function show($id) {
    //
    //    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Customer $customer
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Customer $customer)
    {
        $action = 'Editar Cliente';

        // $customer = Customer::findOrFail($id);

        $formOptions = array('route' => array('admin.customers.update', $customer->id), 'method' => 'PUT');

        $managers = User::isActive()
            ->pluck('name', 'id')
            ->toArray();

        $vatBlocked = false;

        $fileMain = $customer->filename;

        $data = compact(
            'customer',
            'action',
            'formOptions',
            'managers',
            'vatBlocked',
            'fileMain'
        );
        return $this->setContent('admin.customers.edit', $data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Customer $customer)
    {

        $input = $request->all();

        if ($customer->exists) {
            $exists =  true;
            $customer->rules['code'] = 'required|unique:customers,code,' . $customer->id;
        } else {
            $exists = false;
        }

        if ($customer->validate($input)) {

            $customer->fill($input);

            $customer->save();


            if ($exists) {
                return Redirect::back()->with('success', 'Dados gravados com sucesso.');
            } else {
                return Redirect::route('admin.customers.edit', $customer)->with('success', 'Dados gravados com sucesso.');
            }
        }


        return Redirect::back()->withInput()->with('error', $customer->errors()->first());
    }

    public function createDirectory($input)
    {
        $name = str_replace(' ', '', $input['name']);
        $path = storage_path('app/uploadFiles/' . $input['code'] . '_' . $name . '_' . date("m-d-y"));
        $toReturn = $input['code'] . '_' . $name . '_' . date("m-d-y");
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        return $toReturn;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLogin(Request $request, Customer $customer)
    {

        // $customer  = Customer::findOrNew($id);
        $input = $request->all();
        $input['is_active'] = !$request->get('is_active', false);

        $changePass = false;
        $feedback = 'Dados gravados com sucesso.';

        $rules = [];
        if ($customer->exists && empty($input['password'])) {
            $rules['display_name']  = 'required';
            $rules['email']         = 'required|email|unique:customers,email,' . $customer->id;
        } elseif ($customer->exists) {
            $changePass = true;
            $feedback = 'Palavra-passe alterada com sucesso.';
            if (!empty($customer->password)) {
                $rules['password'] = 'confirmed';
            }
        }

        $validator = Validator::make($input, $rules);

        if ($validator->passes()) {

            $password = $input['password'];
            if (empty($input['password'])) {
                unset($input['password']);
            }

            $customer->fill($input);
            //delete image
            if ($input['delete_photo'] && !empty($customer->filepath)) {
                Croppa::delete($customer->filepath);
                $customer->filepath = null;
                $customer->filename = null;
                $customer->filehost = null;
            }

            //upload image
            if ($request->hasFile('image')) {

                if ($customer->exists && !empty($customer->filepath)) {
                    Croppa::delete($customer->filepath);
                }

                if (!$customer->upload($request->file('image'), true, -1,[], true)) {
                    return Redirect::back()->withInput()->with('error', 'Não foi possível alterar a imagem do perfil.');
                }
            } else {
                try {
                    $customer->save();
                } catch (\Exception $e) {
                    return Redirect::back()->withInput()->with('error', 'Um e-mail igual já se encontra registado. Altere o email para poder gravar.');
                }
            }


            //send email with password
            if (@$input['send_email']) {
                Mail::send('emails.customers.accountPassword', compact('input', 'customer', 'password'), function ($message) use ($input, $customer) {
                    $message->to($customer->email)
                        ->from(env('MAIL_FROM'), env('MAIL_FROM_NAME'))
                        ->subject('Dados de acesso '. env('APP_SOURCE'));
                });
            }

            return Redirect::back()->with('success', $feedback);
        }

        return Redirect::back()->withInput()->with('error', $validator->errors()->first());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Customer $customer)
    {
        if (!$customer->delete()) {
            return Redirect::back()->with('error', 'Ocorreu um erro ao tentar remover o cliente.');
        }

        return Redirect::route('admin.customers.index')->with('success', 'Cliente removido com sucesso.');
    }

    /**
     * Remove all selected resources from storage.
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function massDestroy(Request $request)
    {

        $ids = explode(',', $request->get('ids'));

        $result = Customer::whereIn('id', $ids)->delete();

        if (!$result) {
            return Redirect::back()->with('error', 'Não foi possível remover os registos selecionados');
        }

        return Redirect::back()->with('success', 'Registos selecionados removidos com sucesso.');
    }

    /**
     * Loading table data
     *
     * @return Datatables
     */
    public function datatable(Request $request)
    {
        $isProspect = $request->get('prospect', 0);

        $data = Customer::with('manager')
            ->where('is_prospect', $isProspect)
            ->select([
                'customers.*'
            ]);

        if ($request->get('active') == 0 || $request->get('active') == 1) {
            $data = $data->where('is_active', $request->get('active'));
        }



        // filter login
        if ($value = $request->get('login')) {
            $data = $data->where('is_active', $value);
        }

        // filter manager
        if ($value = $request->get('manager')) {
            if ($value == -1) {
                $data = $data->whereNull('manager_id');
            } else {
                $data = $data->where('manager_id', $value);
            }
        }

        //filter category
        if ($value = $request->get('category')) {
            $data = $data->where('category_id', $value);
        }

        return DataTables::of($data)
            ->addColumn('photo', function ($row) {
                return view('admin.partials.datatables.photo', compact('row'))->render();
            })
            ->editColumn('name', function ($row) {
                return view('admin.customers.datatables.name', compact('row'))->render();
            })
            ->editColumn('phone', function ($row) {
                return view('admin.customers.datatables.contacts', compact('row'))->render();
            })
            ->editColumn('category_id', function ($row) {
                return view('admin.customers.datatables.category', compact('row'))->render();
            })
            ->editColumn('manager_id', function ($row) {
                return view('admin.customers.datatables.manager', compact('row'))->render();
            })
            ->addColumn('status', function ($row) {
                return view('admin.customers.datatables.status', compact('row'))->render();
            })
            ->addColumn('access_account', function ($row) {
                return view('admin.customers.datatables.access_account', compact('row'))->render();
            })
            ->addColumn('select', function ($row) {
                return view('admin.partials.datatables.select', compact('row'))->render();
            })
            ->editColumn('created_at', function ($row) {
                return view('admin.partials.datatables.created_at', compact('row'))->render();
            })
            ->addColumn('actions', function ($row) {
                return view('admin.customers.datatables.actions', compact('row'))->render();
            })
            ->make(true);
    }

    /**
     * Return customer details by given nif
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetailsByVat(Request $request)
    {

        $nif = $request->get('vat');
        $url = 'http://www.nif.pt/?json=1&q=' . $nif . '&key=ba06f3f33e3df1c9dacc4b82522f9415';
        $result = json_decode(file_get_contents($url));
        $result = (array) $result;

        if ($result['result'] == 'success') {
            $data = $result['records']->{$nif};
        }

        $result = [
            'result'      => @$result['result'],
            'feedback'    => @$result['message'],
            'name'        => @$data->title,
            'address'     => @$data->address,
            'zip_code'    => @$data->pc4 . '-' . @$data->pc3,
            'city'        => @$data->city,
            'description' => @$data->activity,
            'phone'       => @$data->contacts->phone,
            'email'       => @$data->contacts->email,
            'website'     => @$data->contacts->website,
        ];

        return Response::json($result);
    }

    /**
     * Inactivate customer and send it to prospects list
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function inactivate($id)
    {

        $prospect  = Customer::findOrNew($id);

        $prospect->is_active = 0;
        $prospect->save();

        return Redirect::back()->with('success', 'Ficha inativada com sucesso.');
    }

    public function activate($id)
    {

        $prospect  = Customer::findOrNew($id);

        $prospect->is_active = 1;
        $prospect->save();

        return Redirect::back()->with('success', 'Ficha activada com sucesso.');
    }

    /**
     * Return customer recipient details
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCounties(Request $request)
    {

        $counties = [];
        $recipientDistrict = $request->get('district');
        if ($request->has('district')) {
            $recipientCounties = trans('districts_codes.counties.pt.' . $recipientDistrict);

            $counties = [];
            $counties[] = [
                'id'   => '',
                'text' => 'Todos'
            ];

            foreach ($recipientCounties as $key => $name) {
                $counties[] = [
                    'id'   => $key,
                    'text' => $name
                ];
            }
        }

        return response()->json($counties);
    }

    /**
     * Create invoice
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function notifyPreview(Request $request, $id)
    {

        $customer = Customer::findOrFail($id);

        $html = $customer->notifySource(true);

        return $html;
    }

    /**
     * Create invoice
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notifyPlatform(Request $request, $id)
    {

        $customer = Customer::filterSource()->findOrFail($id);

        try {
            $customer->notifySource();

            return Redirect::back()->with('success', 'Notificação emitida com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        $search = '%' . str_replace(' ', '%', $search) . '%';

        $customers = Customer::where(function ($q) use ($search) {
            $q->where('code', 'LIKE', $search)
                ->orWhere('name', 'LIKE', $search);
        })
            ->get(['id', 'code', 'name']);

        if (!$customers) {
            return Response::json(['id' => '', 'text' => 'Nenhum cliente encontrado.']);
        }

        $results = [];
        foreach ($customers as $customer) {
            $results[] = ['id' => $customer->id, 'text' => $customer->code . ' - ' . \Str::limit($customer->name, 40)];
        }

        return Response::json($results);
    }
}
