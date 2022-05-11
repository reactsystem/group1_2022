<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\MonthlyReport;
use DateTime;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{

    public function index(Request $request): Factory|View|Application
    {
        $tempDate = new DateTime();
        $data = Attendance::where("user_id", "=", Auth::id())->where("attendances.deleted_at", "=", null)->where("date", "=", $tempDate->format('Y-n-j'))->orderByDesc("date")->first();
        $date_now = new DateTime();
        if ($data == null) {
            return view('front.attend.index', compact('request', 'data'));
        }
        if ($data->mode == 1) {
            $date_now = $data->updated_at;
        }
        $interval = $data->created_at->diff($date_now);
        $createDate = new DateTime($data->created_at);
        return view('front.attend.index', compact('request', 'data', 'createDate', 'interval'));
    }

    public function attend(Request $request): Redirector|Application|RedirectResponse
    {
        $tempDate = new DateTime();
        $data = MonthlyReport::where("user_id", "=", Auth::id())->where("date", "=", $tempDate->format('Y-m'))->first();
        if ($data != null) {
            if ($data->status == 1) {
                return redirect("/attends")->with('error', '今月の月報が確定されているため出勤できません。');
            }
        }
        $data = Attendance::where("user_id", "=", Auth::id())->where("attendances.deleted_at", "=", null)->where("date", "=", $tempDate->format('Y-n-j'))->orderByDesc("date")->first();
        if ($data != null) {
            if ($data->mode == 0) {
                return redirect("/attends")->with('error', '既に出勤しています。');
            } else {
                return redirect("/attends")->with('error', '既に退勤しています。');
            }
        }
        Attendance::create([
            'date' => $tempDate,
            'user_id' => Auth::id(),
            'mode' => 0,
            'comment' => '',
        ]);
        return redirect("/attends")->with('result', '出勤しました。');
    }

    public function leave(Request $request): Redirector|Application|RedirectResponse
    {
        $tempDate = new DateTime();
        $data = Attendance::where("user_id", "=", Auth::id())->where("attendances.deleted_at", "=", null)->where("date", "=", $tempDate->format('Y-n-j'))->orderByDesc("date")->first();
        if ($data == null) {
            return redirect("/attends")->with('error', 'まだ出勤していません。');
        }
        if ($data->mode != 0) {
            return redirect("/attends")->with('error', '既に退勤しています。');
        }
        $current = $tempDate->getTimestamp();
        $before = strtotime($data->created_at);
        $diff = $current - $before;
        $hours = intval($diff / 60 / 60);
        $minutes = sprintf('%02d', intval($diff / 60) % 60);
        if (intval($hours . $minutes) > 745) {
            $hours = 7;
            $minutes = "45";
        }
        Attendance::find($data->id)->update(['mode' => 1, 'left_at' => $tempDate, 'time' => "$hours:$minutes"]);
        return redirect("/attends")->with('result', '退勤しました。');
    }

    public function cancelLeft(Request $request): Redirector|Application|RedirectResponse
    {
        $tempDate = new DateTime();
        $data = Attendance::where("user_id", "=", Auth::id())->where("attendances.deleted_at", "=", null)->where("date", "=", $tempDate->format('Y-n-j'))->orderByDesc("date")->first();
        if ($data == null) {
            return redirect("/attends")->with('error', 'まだ出勤していません。');
        }
        if ($data->mode == 0) {
            return redirect("/attends")->with('error', 'まだ退勤していません。');
        }
        Attendance::find($data->id)->update(['mode' => 0]);
        return redirect("/attends")->with('result', '退勤を取り消しました。');
    }

    public function saveWorkMemo(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(["error" => true, "code" => 10, "message" => "ログインしてください。"]);
        }
        if (!isset($request->text)) {
            return response()->json(["error" => true, "code" => 20, "message" => "業務メモがありません。"]);
        }
        if (isset($request->date)) {
            $date = $request->date;
            try {
                $dateSplit = preg_split("/-/", $date);
                $year = intval($dateSplit[0]);
                $month = intval($dateSplit[1]);
                $day = intval($dateSplit[2]);
                $confirmData = MonthlyReport::where('user_id', '=', Auth::id())->where('date', '=', $dateSplit[0] . "-" . $dateSplit[1])->first();
                if ($confirmData != null) {
                    if ($confirmData->status == 1) {
                        return response()->json(["error" => true, "code" => 23, "message" => "指定された月は既に月報確定が行われています。"]);
                    }
                }
                $tempDate = new DateTime();
                $data = Attendance::where("user_id", "=", Auth::id())->where("attendances.deleted_at", "=", null)->where("date", "=", $date)->orderByDesc("created_at")->first();
                if ($data == null) {
                    return response()->json(["error" => true, "code" => 21, "message" => "勤務データが見つかりません。(${date})"]);
                }
                Attendance::find($data->id)->update(['comment' => $request->text]);
                return response()->json(["code" => 0, "message" => "${year}年${month}月${day}日の業務メモを更新しました。"]);
            } catch (\Exception $e) {
                return response()->json(["error" => true, "code" => 22, "message" => "日付の形式が不正です。(${date})"]);
            }
        }
        $tempDate = new DateTime();
        $data = Attendance::where("user_id", "=", Auth::id())->where("attendances.deleted_at", "=", null)->where("date", "=", $tempDate->format('Y-n-j'))->orderByDesc("date")->first();
        if ($data == null) {
            return response()->json(["error" => true, "code" => 21, "message" => "勤務データが見つかりません。"]);
        }
        Attendance::find($data->id)->update(['comment' => $request->text]);
        return response()->json(["code" => 0, "message" => "業務メモを更新しました。"]);
    }

}
