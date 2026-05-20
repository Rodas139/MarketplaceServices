<?php

namespace App\Http\Controllers\Account\Notifications;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;

class NotificationsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private NotificationService $notificationService)
    {
    }

    /**
     * Display the notifications page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('account.notifications.index');
    }

    /**
     * Get recent notifications for the authenticated customer.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecent()
    {
        $customerId = auth('customer')->id();
        $notifications = $this->notificationService->getRecentForCustomer($customerId);
        
        return response()->json($notifications);
    }


    /**
     * Mark recent notifications as read for the authenticated customer.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead()
    {
        $customerId = auth('customer')->id();
        $this->notificationService->markRecentAsReadForCustomer($customerId);
        
        return response()->json(['success' => true]);
    }
}
