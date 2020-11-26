<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    // 消息通知列表
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate();
        return NotificationResource::collection($notifications);
    }

    // 未读消息统计
    public function stats(Request $request)
    {
        return response()->json([
            'unread_count' => $request->user()->notification_count
        ]);
    }

    // 标记消息为已读
    public function read(Request $request)
    {
        $request->user()->markAsRead();

        return response(null, 204);
    }
}
