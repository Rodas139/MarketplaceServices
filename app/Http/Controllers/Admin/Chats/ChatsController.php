<?php

namespace App\Http\Controllers\Admin\Chats;

use App\Http\Controllers\Admin\Controller;
use App\Models\Chat;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class ChatsController extends Controller
{
    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'chats';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private ChatService $chatService)
    {
    }

    /**
     * Display a listing of chats
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data = [];
        return $this->setContent('admin.chats.index', $data);
    }

    /**
     * Remove the specified resource from storage (soft delete)
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Chat $chat)
    {
        try {
            $this->chatService->deleteAdminChat($chat);
            return Redirect::route('admin.chats.index')->with('success', 'Chat removido com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove all selected resources from storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function massDestroy(Request $request)
    {
        try {
            $ids = explode(',', $request->get('ids'));
            $this->chatService->deleteAdminChats($ids);
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
        $data = $this->chatService->getAdminDatatableQuery($request);

        return DataTables::of($data)
            ->addColumn('select', function ($row) {
                return '<input type="checkbox" name="select" value="' . $row->id . '" class="row-select">';
            })
            ->addColumn('order', function ($row) {
                return $row->order ? $row->order->id : '-';
            })
            ->addColumn('customer', function ($row) {
                return $row->customer ? $row->customer->name : '-';
            })
            ->addColumn('provider', function ($row) {
                return $row->provider ? $row->provider->name : '-';
            })
            ->addColumn('subject', function ($row) {
                return $row->subject ?: '-';
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-';
            })
            ->addColumn('actions', function ($row) {
                return view('admin.chats.datatables.actions', compact('row'))->render();
            })
            ->rawColumns(['select', 'actions'])
            ->make(true);
    }
}
