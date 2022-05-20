<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\VariousRequest;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function redirect;
use function view;

class TopPageController extends Controller
{

    function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        $notifications = Auth::user()->notifications()->where('status', 0)->orderByDesc('id')->paginate(5);
        $tempDate = new DateTime();
        $data = Attendance::where("user_id", "=", Auth::id())->where("attendances.deleted_at", "=", null)->where("date", "=", $tempDate->format('Y-n-j'))->orderByDesc("date")->first();
        $allData = Attendance::where("user_id", "=", Auth::id())->where("attendances.deleted_at", "=", null)->where("mode", "=", 1)->get();
        $hours = 0;
        $minutes = 0;
        $hoursReq = 0;
        $minutesReq = 0;
        foreach ($allData as $dat) {
            if ($dat->time == null || $dat->time == "00:00") {
                continue;
            }
            $datArray = preg_split("/:/", $dat->time);
            $restData = preg_split("/:/", $dat->rest ?? "00:00");
            $wHours = intval($datArray[0]);
            $wMinutes = intval($datArray[1]);
            $rHours = intval($restData[0]);
            $rMinutes = intval($restData[1]);
            $xHours = max(0, $wHours - $rHours);
            $xMinutes = $wMinutes - $rMinutes;
            if ($xMinutes < 0 && $xHours != 0) {
                $xMinutes = 60 - abs($xMinutes);
                $xHours -= 1;
            } else if ($xMinutes < 0) {
                $xMinutes = 0;
            }
            $hours += $xHours;
            $minutes += $xMinutes;
        }
        $date_now = new DateTime($tempDate->format("H:i:50"));
        if ($data == null) {
            return view('front.index', compact('notifications', 'data', 'hours', 'minutes', 'hoursReq', 'minutesReq'));
        }
        if ($data->mode == 1) {
            $date_now = $data->updated_at;
        }
        $created = new DateTime($data->created_at->format('H:i:50'));
        $interval = $created->diff($date_now);
        $createDate = new DateTime($data->created_at);


        if ($data->mode == 0) {
            $hours += intval($interval->format('%h'));
            $minutes += intval($interval->format('%i'));
        }

        $datTime = $data->time;
        if ($data->time == null || $data->time == "00:00") {
            $datTime = $interval->format("%h:%I");
        }

        $datArray = preg_split("/:/", $datTime);
        $restData = preg_split("/:/", $data->rest ?? "00:00");
        $wHours = intval($datArray[0]);
        $wMinutes = intval($datArray[1]);
        $rHours = intval($restData[0]);
        $rMinutes = intval($restData[1]);
        $xHours = max(0, $wHours - $rHours);
        $xMinutes = $wMinutes - $rMinutes;
        if ($xMinutes < 0 && $xHours != 0) {
            $xMinutes = 60 - abs($xMinutes);
            $xHours -= 1;
        } else if ($xMinutes < 0) {
            $xMinutes = 0;
        }

        $hours += intval($minutes / 60);
        $minutes = $minutes % 60;


        $cYear = intval($tempDate->format('Y'));
        $cMonth = intval($tempDate->format('m'));
        $likeMonth = $cYear . "-" . sprintf('%02d', $cMonth) . "-";

        $allRequests = VariousRequest::where('user_id', '=', Auth::id())->where('status', '=', 1)->where('date', 'LIKE', "%$likeMonth%")->leftJoin('request_types', 'various_requests.type', '=', 'request_types.id')->select("various_requests.*", "request_types.name as name", "request_types.color as color")->get();

        foreach ($allRequests as $dat) {
            if ($dat->time == null) {
                continue;
            }
            $datArray = preg_split("/:/", $dat->time);
            $hoursReq += intval($datArray[0]);
            $minutesReq += intval($datArray[1]);
        }

        $hoursReq += intval($minutesReq / 60);
        $minutesReq = $minutesReq % 60;

        return view('front.index', compact('notifications', 'interval', 'data', 'hours', 'minutes', 'hoursReq', 'minutesReq', 'xHours', 'xMinutes'));
    }




}
