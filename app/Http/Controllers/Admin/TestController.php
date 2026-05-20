<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use LynX39\LaraPdfMerger\PdfManage;
use Mpdf\Mpdf;
use Setting, DB, Date, File, Hash, Response;
use PDF; // at the top of the file
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Ftp;
use League\Flysystem\Config;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;


class TestController extends \App\Http\Controllers\Admin\Controller
{

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Permission::pluck('name');

        dd($data);
    }
}
