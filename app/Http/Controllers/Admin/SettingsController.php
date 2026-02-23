<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Setting, File, Auth;

class SettingsController extends \App\Http\Controllers\Admin\Controller
{

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'admin_settings';

    /**
     * List of stoage directories
     *
     * @var array
     */
    protected $storageDirectories = [
        '/framework/cache',
        '/framework/views',
        '/framework/sessions',
        '/logs',
        '/debugbar',
        '/importer',
        '/invoices',
        '/keyinvoice-logs'
    ];

    /**
     * List of stoage directories
     *
     * @var array
     */
    public $settingFields = [
        'debug_mode',
        'api_debug_mode',
        'error_log_email_active',
        'error_log_email',
        'debug_ignore_ip',
        'maintenance_mode',
        'maintenance_time',
        'maintenance_ignore_ip'
    ];

    /**
     * List of stoage directories
     *
     * @var array
     */
    protected $directoryModels = [
        '/uploads/agencies' => 'Agency',
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['can:admin_settings']);
        // $this->middleware(['ability:' . config('permissions.role.admin') . ',admin_settings']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {

        $billingProducts = collect();

        $uploadDirectories  = [];

        $storageDirectories = [];
        foreach ($this->storageDirectories as $directory) {

            $folderName = explode('/', $directory);
            $folderName = end($folderName);

            $storageDirectories[$folderName] = ['filepath' => storage_path() . $directory];
        }

        $notificationSounds = [];
        $soundsDir = public_path('assets/sounds');
        $soundsFiles = collect(File::allFiles($soundsDir));
        $soundsFiles = $soundsFiles->sortBy(function ($file) {
            return $file->getFilename();
        });

        $soundId = 1;
        foreach ($soundsFiles as $soundFile) {

            $filename = $soundFile->getFilename();
            $filename = str_replace('.mp3', '', $filename);
            if (str_contains($filename, 'notification')) {
                $notificationSounds[$filename] = 'Notificação ' . $soundId;
                $soundId++;
            }
        }

        $pdfBgVertical = null;
        if (File::exists(public_path() . '/uploads/pdf/bg_v.png')) {
            $pdfBgVertical = '/uploads/pdf/bg_v.png';
        }

        $data = compact(
            'billingProducts',
            'storageDirectories',
            'uploadDirectories',
            'pdfBgVertical',
            'notificationSounds'
        );

        return $this->setContent('admin.settings.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        $input = $request->except('_token');

        $excludeAdminFields = $this->settingFields;

        $allSettings = Setting::all();
        foreach ($allSettings as $setting => $value) {
            if (!in_array($setting, $excludeAdminFields)) {
                Setting::forget($setting);
            }
        }
        Setting::save();

        if ($request->has('delete_pdf_bg')) {
            File::delete(public_path('uploads/pdf/bg_v.png'));
        }

        if ($request->hasFile('pdf_bg')) {
            $file = $request->file('pdf_bg');
            $file->move(public_path('uploads/pdf'), 'bg_v.png');
        }

        $customizationFields = [];
        foreach ($input as $attribute => $value) {

            if (!empty($value)) {
                Setting::set($attribute, $value);
            }
        }

        Setting::save();

        if (!empty($customizationFields)) {
            Auth::user()->setSettingArray($customizationFields);
        }

        return Redirect::back()->with('success', 'Alterações gravadas com sucesso');
    }
}
