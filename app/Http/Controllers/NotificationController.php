<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifications(){
    
    $user = auth()->user();
    $notifications = $user->notifications;

    return response()->json([
        'notifications' => $notifications
    ]);
    }

    public function markNotificationsAsRead ($id){
        $user = auth()->user()->notifications()->findOrFail($id)->markAsRead();
    }
    
    public function deleteNotification ($id){
        $user = auth()->user()->notifications()->findOrFail($id)->delete();
        return response()->json([
            'notifications' => 'done'
        ]);
    }
}
