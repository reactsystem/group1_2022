<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Notification;
use App\Models\VariousRequest;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function redirect;
use function view;

class AdminTopPageController extends Controller
{

    function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        $requests = VariousRequest::where('status', 0)->where('related_id', null)->orderByDesc('id')->paginate(3);
        $data = Attendance::where("attendances.deleted_at", "=", null)->leftJoin('users', 'attendances.user_id', 'users.id')->select("attendances.*", "users.name as name")->orderByDesc('attendances.updated_at')->limit(15)->get();
        $related = [];
        foreach ($requests as $result) {
            $relatedData = VariousRequest::leftJoin('request_types', 'various_requests.type', '=', 'request_types.id')->select("various_requests.*", "request_types.name as name")->where('user_id', Auth::id())->where('related_id', '=', $result->uuid)->orderByDesc('id')->get();
            $dates = [];
            $tempDate = new DateTime($result->date);
            $dates[] = $tempDate->format('Y年n月j日');
            foreach ($relatedData as $relatedDat) {
                $tempDate2 = new DateTime($relatedDat->date);
                $dates[] = $tempDate2->format('Y年n月j日');
            }
            $related[$result->id] = [
                'data' => $relatedData,
                'date' => $dates
            ];
        }
        $notifications = Notification::where('user_id', 0)->where('status', 0)->orderByDesc('id')->paginate(10);
        return view('admin.index', compact('notifications', 'requests', 'related', 'data'));
    }

}
