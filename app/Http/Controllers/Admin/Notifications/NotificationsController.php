<?php

namespace App\Http\Controllers\Admin\Notifications;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class NotificationsController extends Controller
{
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\NotificationService  $service
     */
    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.notifications.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $data = [
            'action' => 'Nova Notificação',
            'notification' => new Notification(),
            'formOptions' => [
                'route' => ['admin.notifications.store']
            ]
        ];
        return view('admin.notifications.create', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,success,error',
        ]);

        $this->service->create(
            $validated['title'],
            $validated['message'],
            $validated['type'],
            auth()->id()
        );

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notificação enviada com sucesso!');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Notification $notification)
    {
        return redirect()->route('admin.notifications.edit', $notification);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Notification $notification)
    {
        $data = [
            'action' => 'Editar Notificação',
            'notification' => $notification,
            'formOptions' => [
                'route' => ['admin.notifications.update', $notification->id],
                'method' => 'PUT'
            ]
        ];
        return view('admin.notifications.edit', $data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Notification $notification)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,success,error',
        ]);

        $notification->update($validated);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notificação atualizada com sucesso!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Notification $notification)
    {
        $this->service->delete($notification->id);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notificação eliminada com sucesso!');
    }


    /**
     * Remove multiple resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function massDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (count($ids) > 0) {
            $this->service->deleteBatch($ids);
        }

        return response()->json(['success' => true]);
    }


    /**
     * Get recent notifications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecent()
    {
        $userId = auth()->id();
        $sevenDaysAgo = Carbon::now()->subDays(7);
        
  
        $notifications = Notification::recent()
            ->where('alert_at', '>=', $sevenDaysAgo)
            ->limit(10)
            ->get();
        

        $notifications = $notifications->map(function($n) use ($userId) {
            $readBy = $n->read_by_admin ?? [];
            $n->is_read_by_user = in_array($userId, $readBy);
            $n->alert_at_formatted = $n->alert_at->format('d/m/Y H:i');
            return $n;
        });
        
        return response()->json($notifications);
    }

 
    /**
     * Get notifications by type.
     *
     * @param  string  $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByType($type)
    {
        $notifications = $this->service->getByType($type, 10);
        return response()->json($notifications);
    }


    /**
     * Mark notifications as read for the current user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead()
    {
        $userId = auth()->id();
        

                    $notifications = Notification::where(function($query) use ($userId) {
                                        $query->whereNull('read_by_admin')
                                            ->orWhereJsonDoesntContain('read_by_admin', $userId);
                })
                ->get();
        

        foreach ($notifications as $notification) {
            $readBy = $notification->read_by_admin ?? [];
            

            if (!in_array($userId, $readBy)) {
                $readBy[] = $userId;
                $notification->update(['read_by_admin' => $readBy]);
            }
        }
        
        return response()->json(['success' => true]);
    }

    /**
     * datatable data for notifications list
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable(Request $request)
    {
        $data = Notification::query()->orderBy('alert_at', 'desc');


        if ($request->has('type') && $request->get('type') != '') {
            $data->where('type', $request->get('type'));
        }

        return DataTables::of($data)
            ->addColumn('select', function($row) {
                return view('admin.partials.datatables.select', compact('row'))->render();
            })
            ->addColumn('title', function($row) {
                return '<a href="' . route('admin.notifications.show', $row->id) . '">' . htmlspecialchars($row->title) . '</a>';
            })
            ->addColumn('type', function($row) {
                $colors = [
                    'info' => 'primary',
                    'success' => 'success',
                    'warning' => 'warning',
                    'error' => 'danger'
                ];
                $color = $colors[$row->type] ?? 'default';
                return '<span class="label label-' . $color . '">' . ucfirst($row->type) . '</span>';
            })
            ->addColumn('sender', function($row) {
                return $row->sender?->name ?? 'Sistema';
            })
            ->addColumn('alert_at', function($row) {
                return $row->alert_at->format('d/m/Y H:i');
            })
            ->addColumn('actions', function($row) {
                return view('admin.notifications.datatables.actions', compact('row'))->render();
            })
            ->rawColumns(['select', 'title', 'type', 'actions'])
            ->make(true);
    }
}
