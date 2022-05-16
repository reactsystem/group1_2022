<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    function jump(int $id)
    {
        $data = Notification::find($id);
        if ($data == null || $data->user_id != Auth::id()) {
            return redirect("/home");
        }
        $url = preg_replace("/%%THIS%%/", $data->id, $data->url);
        $data->update(['status' => 1]);
        return redirect($url);
    }

}
