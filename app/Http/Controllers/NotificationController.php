<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notification;
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request The HTTP request instance.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View|string The HTTP response instance.
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user(); // Fetch current User

            $perPage = $request->input('per_page', 10);
            $query = $user->notifications()->orderBy('created_at', 'desc');

            if ($request->has('search')) {
                $query->where('data', 'like', '%' . $request->input('search') . '%');
            }

            $notifications = $query->paginate($perPage);

            // Cast read/unread as attributes
            $notifications->getCollection()->transform(function ($notification) {
                $notification->is_read = $notification->read_at ? true : false;
                return $notification;
            });

            if ($request->ajax()) {
                return view('components.notification.notification-list', compact('notifications'))->render();
            }

            return view('notifications', compact('notifications', 'user'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mark a specific notification as read.
     *
     * @param int $id The ID of the notification to mark as read.
     * @return \Illuminate\Http\RedirectResponse The HTTP response instance.
     */
    public function markAsRead($id)
    {
        try {
            $notification = auth()->user()->notifications()->find($id);
            if ($notification) {
                $notification->markAsRead();
            }
            return redirect()->back()->with('message', 'Notification marked as read successfully');
        } catch (Exception $e) {
            return redirect()->route('admin.notifications')->with('error', $e->getMessage());
        }
    }

    /**
     * Mark all notifications as read.
     *
     * This method updates all unread notifications for the authenticated user
     * and marks them as read.
     *
     * @return \Illuminate\Http\RedirectResponse The HTTP response instance.
     */
    public function markAllAsRead()
    {
        try {
            $user = auth()->user();
            $user->unreadNotifications->markAsRead();
            return redirect()->back()->with('message', 'All notifications marked as read successfully');
        } catch (Exception $e) {
            return redirect()->route('admin.notifications')->with('error', $e->getMessage());
        }
    }

    /**
     * Mark a notification as unread.
     *
     * @param int $id The ID of the notification to mark as unread.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsUnread($id)
    {
        try {
            $notification = auth()->user()->notifications()->find($id);
            if ($notification) {
                $notification->update(['read_at' => null]);
            }
            return redirect()->back()->with('message', 'Notification marked as unread successfully');
        } catch (Exception $e) {
            return redirect()->route('admin.notifications')->with('error', $e->getMessage());
        }
    }

    /**
     * Handles the notification action for the given notification ID.
     *
     * @param int $notificationId The ID of the notification to handle.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleNotificationAction($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
            $actionUrl = $notification->data['action_url'];

            return redirect($actionUrl);
        }

        return back()->with('error', 'Notification not found.');
    }
}
