<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(Notification::where('user_id', $request->user()->id)->orderBy('created_at', 'desc')->get());
    }

    public function markAsRead($id, Request $request)
    {
        $notification = Notification::where('user_id', $request->user()->id)->findOrFail($id);
        $notification->is_read = true;
        $notification->save();
        return response()->json($notification);
    }
}
