<?php

namespace App\Http\Controllers\Admin\Categorys;

use App\Http\Controllers\Admin\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class CategorysController extends Controller
{
    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'categorys';

    /**
     * Category service instance
     *
     * @var CategoryService
     */
    protected $categoryService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->middleware(['can:admin_categorys']);
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data = $this->categoryService->getIndexData();
        return $this->setContent('admin.categorys.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $data = $this->categoryService->getCreateData();
        return $this->setContent('admin.categorys.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $this->categoryService->createCategory($request);
            return Redirect::route('admin.categorys.index')->with('success', 'Dados gravados com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Category $category)
    {
        $data = $this->categoryService->getEditData($category->id);
        return $this->setContent('admin.categorys.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Category $category)
    {
        try {
            $this->categoryService->updateCategory($category->id, $request);
            return Redirect::route('admin.categorys.index')->with('success', 'Dados gravados com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category)
    {
        try {
            $this->categoryService->deleteCategory($category->id);
            return Redirect::route('admin.categorys.index')->with('success', 'Categoria removida com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove all selected resources from storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function massDestroy(Request $request)
    {
        try {
            $ids = explode(',', $request->get('ids'));
            $this->categoryService->deleteMultipleCategories($ids); 
            return Redirect::back()->with('success', 'Registos selecionados removidos com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * Loading table data
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable(Request $request)
    {
        try {
            $data = $this->categoryService->getDatatableData();

            return DataTables::of($data)
                ->addColumn('select', function ($row) {
                    return view('admin.partials.datatables.select', compact('row'))->render();
                })
                ->editColumn('name', function ($row) {
                    return view('admin.categorys.datatables.name', compact('row'))->render();
                })
                ->editColumn('description', function ($row) {
                    return \Str::limit($row->description, 50);
                })
                ->addColumn('actions', function ($row) {
                    return view('admin.categorys.datatables.actions', compact('row'))->render();
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
