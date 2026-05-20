<?php

namespace App\Http\Controllers\Admin\Comments;

use App\Http\Controllers\Admin\Controller;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class CommentsController extends Controller
{
    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'comments';

    protected $commentService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
        $this->middleware(['can:admin_comments']);
    }

    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data = [];
        return $this->setContent('admin.comments.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $data = $this->commentService->getCreateData();
        return $this->setContent('admin.comments.create', $data);
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
            $this->commentService->createComment($request);
            return Redirect::route('admin.comments.index')->with('success', 'Dados gravados com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Comment $comment)
    {
        $data = $this->commentService->getEditData($comment->id);
        return $this->setContent('admin.comments.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\RedirectResponse
      */
    public function update(Request $request, Comment $comment)
    {
        try {
            $this->commentService->updateComment($comment->id, $request);
            return Redirect::route('admin.comments.index')->with('success', 'Dados gravados com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Comment $comment)
    {
        try {
            $this->commentService->deleteComment($comment->id);
            return Redirect::route('admin.comments.index')->with('success', 'Comentário removido com sucesso.');
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
            $this->commentService->deleteMultipleComments($ids);
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
        $data = Comment::query();

        if ($request->has('id_customer') && $request->get('id_customer')) {
            $data->where('id_customer', $request->get('id_customer'));
        }

        if ($request->has('id_order') && $request->get('id_order')) {
            $data->where('id_order', $request->get('id_order'));
        }

        if ($request->has('rating') && $request->get('rating')) {
            $data->where('rating', $request->get('rating'));
        }

        return DataTables::of($data)
            ->addColumn('select', function ($row) {
                return '<input type="checkbox" name="select" value="' . $row->id . '" class="row-select">';
            })
            ->addColumn('customer', function ($row) {
                return $row->customer ? $row->customer->name : '-';
            })
            ->addColumn('order', function ($row) {
                return $row->order ? $row->order->id : '-';
            })
            ->addColumn('comment', function ($row) {
                return '<div style="max-width: 300px; word-wrap: break-word;">' . substr($row->comment, 0, 100) . (strlen($row->comment) > 100 ? '...' : '') . '</div>';
            })
            ->addColumn('rating', function ($row) {
                if ($row->rating) {
                    return '<span title="Avaliação: ' . $row->rating . '/5">' . $row->rating . '/5</span>';
                }
                return '-';
            })
            ->addColumn('image', function ($row) {
                if ($row->image) {
                    return '<a href="' . asset($row->image) . '" target="_blank"><i class="fa fa-image"></i> Ver</a>';
                }
                return '-';
            })
            ->addColumn('actions', function ($row) {
                return view('admin.comments.datatables.actions', compact('row'))->render();
            })
            ->rawColumns(['select', 'comment', 'rating', 'image', 'actions'])
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    $search = $request->get('search')['value'];
                    $query->where('comment', 'LIKE', "%$search%");
                }
            })
            ->make(true);
    }
}
