<?php

namespace App\Http\Controllers;

use App\Models\OwnerNotification;
use App\Services\OwnerNotificationService;
use Illuminate\Http\Request;

class OwnerNotificationController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeOwner($request);

        $notifications = OwnerNotification::where('recipient_email', OwnerNotificationService::OWNER_EMAIL)
            ->latest()
            ->limit(12)
            ->get()
            ->map(function (OwnerNotification $notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'url' => $notification->url,
                    'read_at' => optional($notification->read_at)->toISOString(),
                    'created_at' => optional($notification->created_at)->toISOString(),
                    'created_human' => optional($notification->created_at)->diffForHumans(),
                ];
            });

        return response()->json([
            'unread_count' => OwnerNotification::where('recipient_email', OwnerNotificationService::OWNER_EMAIL)
                ->whereNull('read_at')
                ->count(),
            'notifications' => $notifications,
        ]);
    }

    public function markRead(Request $request)
    {
        $this->authorizeOwner($request);

        OwnerNotification::where('recipient_email', OwnerNotificationService::OWNER_EMAIL)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    private function authorizeOwner(Request $request): void
    {
        abort_unless(
            $request->user()
                && strtolower($request->user()->email) === OwnerNotificationService::OWNER_EMAIL,
            403
        );
    }
}
