<?php

namespace App\Http\Controllers\Admin;

use App\Models\FileRepository;
use App\Models\Requirements\Requirement;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;
use Response, File, Setting, ZipArchive;

class FileRepositoryController extends \App\Http\Controllers\Admin\Controller
{

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'files_repository';

    /**
     * Guarded folders
     * @var array
     */
    private $guardedFolders = [
        FileRepository::FOLDER_CUSTOMERS,
        FileRepository::FOLDER_USERS,
        FileRepository::FOLDER_PROJECTS,
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {

        $extensions = FileRepository::filterSource()
            ->whereNotNull('extension')
            ->groupBy('extension')
            ->pluck('extension', 'extension')
            ->toArray();

        $curFolder  = null;
        $breadcrumb = null;

        if ($request->has('folder')) {
            $curFolder = FileRepository::filterSource()
                ->where('is_folder', 1)
                ->whereId($request->get('folder'))
                ->first();

            if ($curFolder) {
                $breadcrumb = FileRepository::getFolderBreadcrumb($curFolder);
            }
        }

        $serverSize = Setting::get('server_size_gb') * 1073741824;
        $totalSize = FileRepository::filterSource()
            ->where('is_folder', 0)
            ->sum('filesize');

        $ocupiedSizePercent = ($totalSize * 100) / $serverSize;

        if ($ocupiedSizePercent >= 90) {
            $ocupiedSizeColor = '#ff0000';
        } elseif ($ocupiedSizePercent >= 75) {
            $ocupiedSizeColor = '#f46a00';
        } elseif ($ocupiedSizePercent >= 50) {
            $ocupiedSizeColor = '#ffcc01';
        } else {
            $ocupiedSizeColor = '#00A623';
        }

        $guardedFolders = $this->guardedFolders;

        $data = compact(
            'extensions',
            'curFolder',
            'breadcrumb',
            'guardedFolders',
            'totalSize',
            'serverSize',
            'ocupiedSizePercent',
            'ocupiedSizeColor'
        );

        return $this->setContent('admin.repository.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, string $target, int $id)
    {
        $action = 'Adicionar ficheiro';
        $formOptions = ['route' => ['admin.attachments.store', $target, $id], 'method' => 'POST',  'files' => true,'data-replace-with' => '.attachments-content'];
        $file = new FileRepository();

        $data = compact(
            'file',
            'action',
            'formOptions',
        );

        return view('admin.files.modals.create', $data)->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, string $target, int $id)
    {
        FileRepository::flushCache(FileRepository::CACHE_TAG);
        $input = $request->all();

        if (!empty($input['parent_id'])) {

            $parentFolder = FileRepository::filterSource()
                ->where('is_folder', 1)
                ->where('id', $input['parent_id'])
                ->first();

            $input['parent_id'] = @$parentFolder->id;
        }

        $attachment = FileRepository::findOrNew($id);
        if (!$attachment->validate($input)) {
            if ($request->ajax()) {
                return Response::json([
                    'result'    => false,
                    'type'      => 'error',
                    'feedback'  => $attachment->errors()->first()
                ]);
            } else {
                return Redirect::back()->withInput()->with('error', $attachment->errors()->first());
            }
        }

        if ($request->hasFile('file')) {
            if (!$attachment->upload($request->file('file'), true, 40)) {
                return Redirect::back()->withInput()->with('error', 'Não foi possível carregar o ficheiro.');
            }

            $attachment->is_folder = 0;
            $attachment->filesize  = File::size(storage_path($attachment->filepath));
        }

        $attachment->fill($input);
        $attachment->user_id = Auth::user()->id;
        $attachment->source_class = $target;
        $attachment->source_id = $id;

        $attachment->save();

        if ($request->ajax()) {
            return Response::json([
                'result'    => true,
                'type'      => 'success',
                'feedback'  => 'Ficheiro carregado com sucesso.'
            ]);
        } else {
            return Redirect::back()->with('success', 'Ficheiro carregado com sucesso.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, FileRepository $file)
    {
        $action = $file->is_folder ? 'Editar pasta' : 'Editar ficheiro';
        $formOptions = ['route' => ['admin.attachments.update', $file->id], 'method' => 'PUT'];

        $data = compact(
            'file', 
            'action', 
            'formOptions'
        );

        return view('admin.files.modals.edit', $data)->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, FileRepository $file)
    {
        FileRepository::flushCache(FileRepository::CACHE_TAG);
        $input = $request->all();

        if (!$file->validate($input)) {
            if ($request->ajax()) {
                return Response::json([
                    'result'    => false,
                    'type'      => 'error',
                    'feedback'  => $file->errors()->first()
                ]);
            } else {
                return Redirect::back()->withInput()->with('error', $file->errors()->first());
            }
        }

        $file->fill($input);
        $file->save();

        if ($request->ajax()) {
            return Response::json([
                'result'    => true,
                'type'      => 'success',
                'feedback'  => 'Ficheiro atualizado com sucesso.'
            ]);
        } else {
            return Redirect::back()->with('success', 'Ficheiro atualizado com sucesso.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, FileRepository $file)
    {
        $source_id = $file->source_id;

        //file copy delete
        FileRepository::where('filepath', $file->filepath)->whereNot('id', $file->id)->delete();
        if (!FileRepository::destroyRecursive($file)) {
            if ($request->ajax()) {
                return Response::json([
                    'result'    => false,
                    'type'      => 'error',
                    'feedback'  => 'Ocorreu um erro ao tentar remover o ficheiro.'
                ]);
            } else {
                return Redirect::back()->withInput()->with('error', 'Ocorreu um erro ao tentar remover o ficheiro.');
            }
        }

        switch($file->source_class){
            case (FileRepository::PROJECT_SERVICE_SOURCE_CLASS):
            case (FileRepository::PROJECT_SOURCE_CLASS):
                    $returnView = 'admin.projects.partials.attachments_content';
                    $projectAttachments = FileRepository::filterSource()
                        ->where('source_class', FileRepository::PROJECT_SOURCE_CLASS)
                        ->where('source_id', $source_id)
                        ->orderBy('name', 'asc')
                        ->get();

                    $data = compact(
                        'projectAttachments', 
                    );
                break;
            case (FileRepository::REQUIREMENTS_SOURCE_CLASS):
                    $returnView = 'admin.requirements.requirements.partials.fileTable';
                    $files = FileRepository::where('source_class', FileRepository::REQUIREMENTS_SOURCE_CLASS)
                            ->where('source_id', $source_id)->get();
                    $requirement = Requirement::findOrFail($source_id);
                    $data = compact(
                        'files',
                    ) + ['source' => $requirement] ;
                break;                
        }

        if ($request->ajax()) {
            return Response::json([
                'result'    => true,
                'type'      => 'success',
                'feedback'  => 'Anexo apagado com sucesso.',
                'target'    => '#file-list',
                'html'      => view($returnView, $data)->render(),
            ]);
        } else {
            return Redirect::back()->with('success', 'Anexo removido com sucesso.');
        }
    }



    /**
     * Remove all selected resources from storage.
     * GET /admin/features/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function massDestroy(Request $request)
    {

        $files = FileRepository::filterSource()
            ->whereIn('id',$request->get('id'))
            ->where('is_static', 0)
            ->get();

        $result = true;
        foreach ($files as $file) {
            //delete files copy
            FileRepository::where('filepath', $file->filepath)->whereNot('id', $file->id)->delete();
            $result = FileRepository::destroyRecursive($file);
        }

        if (!$result) {
            return Redirect::back()->with('error', 'Não foi possível remover os registos selecionados');
        }

        return Redirect::back()->with('success', 'Registos selecionados removidos com sucesso.');
    }

    /**
     * Download file
     *
     * @return type
     */
    public function download(Request $request, FileRepository $file)
    {
        $filepath = storage_path() . '/' . $file->filepath;
        return response()->download($filepath, $file->filename);
    }

    /**
    * Download multiple files at once. The files are zipped together and downloaded
    * The route is /admin/files/download?ids
    *
    * @return \Symfony\Component\HttpFoundation\BinaryFileResponse | \Illuminate\Http\RedirectResponse zipped file or redirect back if an error ocured
    */
    public function massDownload(Request $request){
        $files = FileRepository::whereIn('id', $request['id'])->get();
        $zipFile = new ZipArchive;
        $zipFileName = "ficheiros.zip";

        if ($zipFile->open(storage_path($zipFileName), ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zipFile->addFile(storage_path($file->filepath), basename($file->name));
            }
            
            $zipFile->close();
            return response()->download(storage_path($zipFileName))->deleteFileAfterSend(true);
        }
        return Redirect::back()->with('error', 'Erro a fazer de download dos ficheiros');
    }

    /**
     * Loading table data
     *
     * @return Datatables
     */
    public function datatable(Request $request)
    {
        $data = FileRepository::filterSource()->with('user')
            ->select();

        //filter extension
        $value = $request->get('extension');
        if ($request->has('extension')) {
            $data = $data->whereIn('extension', $value);
        }

        //filter target
        $value = $request->get('target');
        if($request->has('target')){
            $data = $data->where('source_class', $value);
        }

        $value = $request->get('target_id');
        if($request->has('target_id')){
            $data = $data->where('source_id', $value);
        }

        $nameLink = $request->get('name_open_link', false);
        $actionsMode = $request->get('actions_mode', 'dropdown');


        return Datatables::of($data)
            ->editColumn('name', function ($row) use ($nameLink) {
                return view('admin.repository.datatables.name', compact('row','nameLink'))->render();
            })
            ->editColumn('extension', function ($row) {
                return view('admin.repository.datatables.extension', compact('row'))->render();
            })
            ->editColumn('customer_visible', function ($row) {
                return view('admin.repository.datatables.customer_visible', compact('row'))->render();
            })
            ->editColumn('filesize', function ($row) {
                return human_filesize($row->filesize);
            })
            ->addColumn('select', function ($row) {
                return view('admin.partials.datatables.select', compact('row'))->render();
            })
            ->editColumn('created_at', function ($row) {
                return view('admin.partials.datatables.created_at', compact('row'))->render();
            })
            ->addColumn('actions', function ($row) use ($actionsMode) {
                return view('admin.repository.datatables.actions', compact('row','actionsMode'))->render();
            })
            ->make(true);
    }

    /**
    * Upload files from drag and drop
    *
    * @param Request $request input with file
    * @param string $target entity's class, to associate file
    * @param int $id target's id
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function upload(Request $request, string $target, int $id){
        $input = $request->all();
        $error = null;
        $source_class = $target;

        switch($target){
            case(FileRepository::REQUIREMENTS_SOURCE_CLASS):
                    $parentFolderId = FileRepository::FOLDER_REQUIREMENTS;
                break;
            case(FileRepository::PROJECT_SOURCE_CLASS):
                    $parentFolderId = FileRepository::FOLDER_PROJECTS;
                break;
            case(FileRepository::PROJECT_SERVICE_SOURCE_CLASS):
                    $parentFolderId = FileRepository::FOLDER_PROJECTS ;
                break;
            case(FileRepository::QUOTE_SOURCE_CLASS):
                    $parentFolderId = FileRepository::FOLDER_QUOTES ;
                break;
            default:
                abort(404);
        }

        foreach($input as $file){
            $attachment = new FileRepository();
            
            if (!$attachment->validate($input)) {
                $error = $attachment->errors()->first();
                break;
            }

            if (!$attachment->upload($file, true, 40)) {
                $error = 'Não foi possível carregar o documento '. $file->originalName;
                break;
            }

            $attachment->fill($input);
            $attachment->parent_id    = $parentFolderId;
            $attachment->source_class = $source_class;
            $attachment->source_id    = $id;
            $attachment->user_id      = Auth::user()->id;

            $attachment->save();
        }

        if(isset($error)){
            return Response::json([
                'result'    => false,
                'type'      => 'error',
                'feedback'  => $error
            ]);
        }else{
            return Response::json([
                'result'    => true,
                'type'      => 'success',
            ]);
        }
    }

    /**
    * Show file content on browser
    *
    * @param FileRepository file object
    *
    * @return \Illuminate\Http\Response
    */
    public function open(FileRepository $file){
        $path = storage_path($file->filepath);

        if (!File::exists($path)) {
            abort(404);
        }

        $fileContent = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($fileContent, 200);
        $response->header("Content-Type", $type);
        $response->header("Content-Disposition", 'inline; filename="' . $file->filename . '"');

        return $response;
    }
}
