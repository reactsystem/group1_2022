<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Configuration;
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
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{

    public function index(Request $request): Factory|View|Application
    {
        $config = Configuration::find(1);
        $timeRaw = $config->time;
        $timeArray = preg_split("/:/", $timeRaw);
        $timeHours = intval($timeArray[0]);
        $timeMinutes = intval($timeArray[1]);
        $baseTime = ($timeHours * 60 + $timeMinutes) * 60;


        $tempDate = new DateTime();
        $data = Attendance::where("user_id", "=", Auth::id())->where("attendances.deleted_at", "=", null)->where("date", "=", $tempDate->format('Y-n-j'))->orderByDesc("date")->first();
        $date_now = new DateTime($tempDate->format("H:i:50"));
        $date_now->s = 0;
        $interval = "";
        $restTime = (7 * 60 + 45) * 60;
        if ($data == null) {
            return view('front.attend.index', compact('request', 'data', 'baseTime', 'config'));
        } else {

            $created = new DateTime($data->created_at->format("H:i:50"));
            $created->s = 0;
            $intervalTime = $created->diff($date_now);
            if ($data->mode == 1 && $data->left_at != null) {
                $tempLeftDat = preg_split("/:/", $data->left_at);
                $left = new DateTime($tempLeftDat[0] . ":" . $tempLeftDat[1] . ":50");
                $left->s = 0;
                $intervalTime = $created->diff($left);
                //$intervalTime->set
            }
            $leftTime = new DateTime($data->left_at ?? $data->update_at);

            $datArray = preg_split("/:/", $intervalTime->format("%h:%I"));
            $restData = preg_split("/:/", $data->rest ?? "00:00");
            $wHours = intval($datArray[0]);
            $wMinutes = intval($datArray[1]);
            $rHours = intval($restData[0]);
            $rMinutes = intval($restData[1]);
            $xhours = max(0, $wHours - $rHours);
            $xminutes = $wMinutes - $rMinutes;
            if ($xminutes < 0 && $xhours != 0) {
                $xminutes = 60 - abs($xminutes);
                $xhours -= 1;
            } else if ($xminutes < 0) {
                $xminutes = 0;
            }
            $restTime = ($rHours * 60 + $rMinutes) * 60;
            $interval = $xhours . ":" . sprintf("%02d", $xminutes);
            if ($xhours == 0 && $xminutes == 0) {
                $interval = "<span class='text-danger'>" . ($xhours . ":" . sprintf("%02d", $xminutes)) . "</span>";
            }
            $origin = $wHours . ":" . sprintf("%02d", $wMinutes);
        }
        if ($data->mode == 1) {
            $date_now = $data->updated_at;
        }
        $createDate = new DateTime($data->created_at);
        return view('front.attend.index', compact('request', 'data', 'createDate', 'interval', 'baseTime', 'restTime', 'config', 'origin', 'leftTime'));
    }

    public function attend(Request $request): Redirector|Application|RedirectResponse
    {
        $config = Configuration::find(1);
        $tempDate = new DateTime();
        $data = MonthlyReport::where("user_id", "=", Auth::id())->where("date", "=", $tempDate->format('Y-m'))->first();
        if ($data != null) {
            if ($data->status >= 1) {
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
            'time' => "00:00",
            'rest' => ($config->rest ?? "00:45:00"),
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

        $created = new DateTime($data->created_at->format("H:i:50"));
        $created->s = 0;
        $tempLeftDat = preg_split("/:/", $tempDate->format('H:i:50'));
        $left = new DateTime($tempLeftDat[0] . ":" . $tempLeftDat[1] . ":50");
        $left->s = 0;
        $intervalTime = $created->diff($left);

        $restData = preg_split("/:/", $data->rest ?? "00:00");
        $wHours = $hours;
        $wMinutes = $minutes;
        $rHours = intval($restData[0]);
        $rMinutes = intval($restData[1]);
        $xhours = max(0, $wHours - $rHours);
        $xminutes = $wMinutes - $rMinutes;
        if ($xminutes < 0 && $xhours != 0) {
            $xminutes = 60 - abs($xminutes);
            $xhours -= 1;
        } else if ($xminutes < 0) {
            $xminutes = 0;
        }


        $timeRaw = Configuration::find(1)->time;
        $timeArray = preg_split("/:/", $timeRaw);
        $timeHours = intval($timeArray[0]);
        $timeMinutes = intval($timeArray[1]);

        $timeInt = $timeHours * 100 + $timeMinutes;
        $workTimeOver = intval($xhours . $xminutes) > $timeInt;
        if ($workTimeOver) {
            //$hours = $timeHours;
            //$minutes = "$timeMinutes";
        }

        Attendance::find($data->id)->update(['rest' => $data->rest ?? "00:00", 'mode' => 1, 'left_at' => $tempDate, 'time' => $intervalTime->format('%h:%I')]);
        if ($workTimeOver) {
            return redirect("/attends")->with('result', '退勤しました。 ');
        }
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
        Attendance::find($data->id)->update(['mode' => 0, 'left_at' => null, 'time' => null]);
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
        if (!isset($request->rest)) {
            return response()->json(["error" => true, "code" => 24, "message" => "休憩時間が設定されていません。"]);
        }
        $userId = Auth::id();
        if (isset($request->user) && Auth::user()->group_id == 1) {
            $userId = $request->user;
        }
        $rules = [
            'text' => 'required|max:2000|min:1',
            'rest' => 'required',
        ];
        $messages = [
            'rest.required' => '休憩時間を入力してください',
            'text.required' => '業務詳細を記入してください',
            'text.min' => '業務詳細は1文字以上で入力してください',
            'text.max' => '業務詳細は2,000文字以下で入力してください',
        ];
        if (env("ENABLE_EDIT_ATTENDANCE", false)) {
            $rules = [
                'text' => 'required|max:2000|min:1',
                'start' => 'required',
                'rest' => 'required',
            ];
            $messages = [
                'start.required' => '出勤時間を入力してください',
                'rest.required' => '休憩時間を入力してください',
                'text.required' => '業務詳細を記入してください',
                'text.min' => '業務詳細は1文字以上で入力してください',
                'text.max' => '業務詳細は2,000文字以下で入力してください',
            ];
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(["error" => true, "code" => 1, "message" => "以下の項目を確認してください:", "errors" => $validator->errors()]);
        }
        if (isset($request->date)) {
            $date = $request->date;
            try {
                $dateSplit = preg_split("/-/", $date);
                $year = intval($dateSplit[0]);
                $month = intval($dateSplit[1]);
                $day = intval($dateSplit[2]);
                $confirmData = MonthlyReport::where('user_id', '=', $userId)->where('date', '=', $dateSplit[0] . "-" . $dateSplit[1])->first();
                if ($confirmData != null) {
                    if ($confirmData->status == 1) {
                        return response()->json(["error" => true, "code" => 23, "message" => "指定された月は既に月報確定が行われています。"]);
                    }
                }
                $tempDate = new DateTime();
                $data = Attendance::where("user_id", "=", $userId)->where("attendances.deleted_at", "=", null)->where("date", "=", $date)->orderByDesc("created_at")->first();
                if ($data == null) {
                    return response()->json(["error" => true, "code" => 25, "message" => "勤務データが見つかりません。(${date})"]);
                }
                if (env("ENABLE_EDIT_ATTENDANCE", false)) {

                    $endTime = $request->end;
                    $restTime = $request->rest;
                    $startDate = $data->created_at;
                    if (isset($request->start)) {
                        $startTime = $request->start;
                        $startDate = new DateTime($data->created_at->format("Y-m-d ${startTime}:00"));
                    }
                    $endDate = null;
                    if ($endTime != "" && $endTime != "00:00") {
                        if ($data->left_at == null) {
                            $endDate = new DateTime($data->created_at->format("Y-m-d ${endTime}:00"));
                        } else {
                            $endDate = new DateTime($data->created_at->format("Y-m-d ${endTime}:00"));
                        }
                    }

                    $workTime = "00:00";

                    if ($endDate != null) {
                        $current = strtotime($endDate->format("Y-m-d ${endTime}:00"));
                        $before = strtotime($data->created_at->format("Y-m-d ${startTime}:00"));
                        $diff = $current - $before;
                        $hours = intval($diff / 60 / 60);
                        $minutes = intval($diff / 60) % 60;
                        $workTime = sprintf("%02d", $hours) . ":" . sprintf("%02d", $minutes);
                    }

                    $param = [
                        'created_at' => $startDate,
                        'left_at' => $endDate,
                        'time' => $workTime,
                        'rest' => $restTime,
                        'text' => $request->text,
                    ];

                    Attendance::find($data->id)->update($param);
                } else {
                    Attendance::find($data->id)->update(['comment' => $request->text, 'rest' => $request->rest]);
                }
                return response()->json(["code" => 0, "message" => "${year}年${month}月${day}日の業務メモを更新しました。"]);
            } catch (\Exception $e) {
                return response()->json(["error" => true, "code" => 22, "message" => "日付の形式が不正です。(${date}) <br>" . $e->getTraceAsString()]);
            }
        }
        $tempDate = new DateTime();
        $data = Attendance::where("user_id", "=", $userId)->where("attendances.deleted_at", "=", null)->where("date", "=", $tempDate->format('Y-n-j'))->orderByDesc("date")->first();
        if ($data == null) {
            return response()->json(["error" => true, "code" => 21, "message" => "勤務データが見つかりません。"]);
        }

        if (env("ENABLE_EDIT_ATTENDANCE", false)) {

            $endTime = $request->end;
            $restTime = $request->rest;
            $startDate = $data->created_at;
            if (isset($request->start)) {
                $startTime = $request->start;
                $startDate = new DateTime($data->created_at->format("Y-m-d ${startTime}:00"));
            }
            $endDate = null;
            if ($endTime != "" && $endTime != "00:00") {
                if ($data->left_at == null) {
                    $endDate = new DateTime($data->created_at->format("Y-m-d ${endTime}:00"));
                } else {
                    $endDate = new DateTime($data->created_at->format("Y-m-d ${endTime}:00"));
                }
            }

            $workTime = "00:00";

            if ($endDate != null) {
                $current = strtotime($endDate->format("Y-m-d ${endTime}:00"));
                $before = strtotime($data->created_at->format("Y-m-d ${startTime}:00"));
                $diff = $current - $before;
                $hours = intval($diff / 60 / 60);
                $minutes = intval($diff / 60) % 60;
                $workTime = sprintf("%02d", $hours) . ":" . sprintf("%02d", $minutes);
            }

            $param = [
                'created_at' => $startDate,
                'left_at' => $endDate,
                'time' => $workTime,
                'rest' => $restTime,
                'text' => $request->text,
            ];

            Attendance::find($data->id)->update($param);
        } else {
            Attendance::find($data->id)->update(['comment' => $request->text, 'rest' => $request->rest]);
        }
        Attendance::find($data->id)->update(['comment' => $request->text, 'rest' => $request->rest]);
        return response()->json(["code" => 0, "message" => "勤務詳細を更新しました。しばらくお待ちください..."]);
    }

}
