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
        if ($data != null && (($data->user_id == 0 && Auth::user()->group_id == 1) || $data->user_id == Auth::id())) {
            $url = preg_replace("/%%THIS%%/", $data->id, $data->url);
            $data->update(['status' => 1]);
            if (preg_match("/http[ |s]:\/\//", $data->url)) {
                return view('front.jump', compact('data'));
            }
            return redirect($url);
        }
        return redirect("/home");
    }

}
