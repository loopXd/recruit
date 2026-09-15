<?php

namespace App\Http\Controllers\App\Notification;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function view()
    {
        return view('all-notifications.index');
    }
}
