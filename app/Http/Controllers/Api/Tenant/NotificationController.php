<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest();

        return NotificationResource::collection($notifications);
    }

    public function show(Notification $notification)
    {
        $user = Auth::user();

        abort_unless($user->notifications()->where('notifications.id', $notification->id)->exists(), 403);

        return new NotificationResource($notification);
    }

    public function markAsRead(Notification $notification)
    {
        $user = Auth::user();

        $user->notifications()->updateExistingPivot(
            $notification->id,
            [
                'read_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Notificação marcada como lida.'
        ]);
    }

    public function markAllAsRead()
    {
        $user = Auth::user();

        $user->notifications()
            ->wherePivotNull('read_at')
            ->syncWithPivotValues(
                $user->notifications->pluck('id'),
                [
                    'read_at' => now(),
                ],
                false
            );

        return response()->json([
            'message' => 'Todas notificações foram marcadas como lidas.'
        ]);
    }
}
