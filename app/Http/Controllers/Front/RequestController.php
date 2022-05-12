<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\RequestTypes;
use App\Models\VariousRequest;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use function view;

class RequestController extends Controller
{

    function index(Request $request)
    {
        $mode = $request->mode;
        if ($mode) {
            $results = VariousRequest::leftJoin('request_types', 'various_requests.type', '=', 'request_types.id')->select("various_requests.*", "request_types.name as name")->where('user_id', Auth::id())->where('related_id', '=', NULL)->orderByDesc('id')->paginate(10);
        } else {
            $results = VariousRequest::leftJoin('request_types', 'various_requests.type', '=', 'request_types.id')->select("various_requests.*", "request_types.name as name")->where('user_id', Auth::id())->where('related_id', '=', NULL)->where('status', '!=', 3)->orderByDesc('id')->paginate(10);
        }
        $related = [];
        foreach ($results as $result) {
            $relatedData = VariousRequest::leftJoin('request_types', 'various_requests.type', '=', 'request_types.id')->select("various_requests.*", "request_types.name as name")->where('user_id', Auth::id())->where('related_id', '=', $result->uuid)->get();
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
        return view('front.request.index', compact('results', 'related', 'mode'));
    }

    function show(Request $request)
    {
        $result = VariousRequest::leftJoin('request_types', 'various_requests.type', '=', 'request_types.id')->select("various_requests.*", "request_types.name as name")->where('user_id', Auth::id())->where('related_id', '=', NULL)->find($request->id);
        if ($result == null || $result->uuid == null) {
            return redirect("/request");
        }
        $relatedData = VariousRequest::leftJoin('request_types', 'various_requests.type', '=', 'request_types.id')->select("various_requests.*", "request_types.name as name")->where('user_id', Auth::id())->where('related_id', '=', $result->uuid)->get();
        $dates = [];
        $tempDate = new DateTime($result->date);
        $dates[] = $tempDate->format('Y年n月j日');
        foreach ($relatedData as $relatedDat) {
            $tempDate2 = new DateTime($relatedDat->date);
            $dates[] = $tempDate2->format('Y年n月j日');
        }
        $related = [
            'data' => $relatedData,
            'date' => $dates
        ];
        $holidays = 0;
        if ($result->type == 2) {
            $holidays = count($dates);
        }
        return view('front.request.show', compact('result', 'related', 'holidays'));
    }

    function cancelRequest(Request $request)
    {
        $result = VariousRequest::leftJoin('request_types', 'various_requests.type', '=', 'request_types.id')->select("various_requests.*", "request_types.name as name", "request_types.type as mode")->where('user_id', Auth::id())->where('related_id', '=', NULL)->find($request->id);
        if ($result == null || $result->uuid == null) {
            return redirect("/request");
        }
        if ($result->status != 0) {
            return redirect("/request/" . $result->id);
        }
        $c1 = VariousRequest::where('related_id', '=', $result->uuid)->get();
        VariousRequest::find($result->id)->update(["status" => 3]);
        if ($result->mode == 2) {
            $holidays = 1 + count($c1);
            if ($holidays > 0) {
                $user = Auth::user();
                $user->paid_holiday = $user->paid_holiday + $holidays;
                $user->save();
            }
        }
        return redirect("/request")->with('result', '申請を取り消しました。');
    }

    function createRequest(Request $request)
    {
        $types = RequestTypes::all();
        $reqDate = $request->date;
        return view('front.request.create', compact('types', 'reqDate'));
    }

    function checkRequestBack(Request $request)
    {
        return back()->withInput();
    }

    function checkRequest(Request $request)
    {
        $tempDate = preg_split("/,/", $request->dates);
        $dates = [];
        foreach ($tempDate as $item) {
            $temp = new DateTime($item);
            $dates[] = $temp->format('Y-n-j');
        }
        if (count($dates) == 0) {
            return redirect("/request")->with('error', '日付が指定されていません');
        }
        $type = RequestTypes::find($request->type);
        if ($type == null) {
            return redirect("/request")->with('error', '種別が指定されていません');
        }
        $holidays = 0;
        $time = NULL;
        if (isset($request->time) && $type->type == 1) {
            $time = $request->time;
            if ($time == null || $time == "00:00") {
                return redirect("/request")->with('error', '労働時間が指定されていません');
            }
        }
        if ($request->type == 2) {
            $holidays = count($dates);
        }
        if ($holidays > Auth::user()->paid_holiday) {
            return redirect("/request")->with('error', '有給消費が残日数を超えています');
        }
        $reason = $request->reason;
        $uuid = Str::uuid();
        foreach ($tempDate as $index => $item) {
            //echo $request->time;
            $timeStr = "";
            if ($request->time != null || $request->time != "") {
                $time = preg_split("/:/", $request->time);
                $timeStr = intval($time[0]) . ":" . sprintf("%02d", intval($time[1]));
            }
            if ($index == 0) {
                VariousRequest::create([
                    'uuid' => $uuid,
                    'user_id' => Auth::id(),
                    'type' => $type->id,
                    'date' => $item,
                    'status' => 0,
                    'time' => $timeStr,
                    'reason' => $request->reason ?? "",
                ]);
            } else {
                VariousRequest::create([
                    'uuid' => Str::uuid(),
                    'user_id' => Auth::id(),
                    'type' => $type->id,
                    'date' => $item,
                    'status' => 0,
                    'time' => $timeStr,
                    'reason' => $request->reason ?? "",
                    'related_id' => $uuid,
                ]);
            }
        }
        if ($holidays > 0) {
            $user = Auth::user();
            $user->paid_holiday = $user->paid_holiday - $holidays;
            $user->save();
        }
        return redirect("/admin/request")->with('result', '申請を行いました');
        //return view('front.request.check', compact('dates', 'type', 'holidays', 'reason', 'time'));
    }

}
