<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CalenderUtil;
use App\Http\Controllers\DownloadUtil;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\MonthlyReport;
use App\Models\Notification;
use App\Models\RequestType;
use App\Models\User;
use App\Models\VariousRequest;
use DateTime;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class AdminAttendManagementController
{

    function index(Request $request)
    {
        $users = User::where('left_date', "=", null)->get();
        $data = Attendance::where("attendances.deleted_at", "=", null)->leftJoin('users', 'attendances.user_id', 'users.id')->select("attendances.*", "users.name as name", "users.id as uid");
        $title = "勤怠ログ";
        $parameters = [];
        if (isset($request->user)) {
            $parameters['user'] = $request->user;
            $data = $data->where("users.id", $request->user);
            $user = User::find($request->user);
            if ($user != null) {
                $searchArray[] = "<strong>ユーザー: </strong>" . $user->name;
            }
        }
        if (isset($request->date)) {
            $parameters['user'] = $request->date;
            $data = $data->where("date", $request->date);
            $searchArray[] = "<strong>日付: </strong>" . $request->date;
        }
        if (isset($request->status)) {
            $parameters['user'] = $request->status;
            $data = $data->where("mode", $request->status);
            $searchArray[] = "<strong>状態: </strong>" . $request->status;
        }
        $data = $data->orderByDesc('attendances.created_at')->paginate(15);
        $searchStr = "<span class='text-primary'>項目をクリックして確認・編集・削除画面に移動できます</span>";
        return view('admin.attend-manage.index', compact('data', 'users', 'title', 'searchStr', 'parameters'));
    }

    function search(Request $request)
    {
        $users = User::where('left_date', "=", null)->get();
        $data = Attendance::where("attendances.deleted_at", "=", null)->leftJoin('users', 'attendances.user_id', 'users.id')->select("attendances.*", "users.name as name", "users.id as uid");
        $searchArray = [];
        $parameters = [];
        if (isset($request->user)) {
            $parameters['user'] = $request->user;
            $data = $data->where("users.id", $request->user);
            $user = User::find($request->user);
            if ($user != null) {
                $searchArray[] = "<strong>ユーザー: </strong>" . $user->name;
            }
        }
        if (isset($request->date)) {
            $parameters['user'] = $request->date;
            $data = $data->where("date", $request->date);
            $searchArray[] = "<strong>日付: </strong>" . $request->date;
        }
        if (isset($request->status)) {
            $parameters['user'] = $request->status;
            $data = $data->where("mode", $request->status);
            $searchArray[] = "<strong>状態: </strong>" . $request->status;
        }

        $title = "勤怠ログ | 検索";
        $data = $data->orderByDesc('attendances.created_at')->paginate(15);
        $searchStr = join(" / ", $searchArray);
        return view('admin.attend-manage.index', compact('data', 'users', 'title', 'searchStr', 'parameters'));
    }

    function view(Request $request)
    {
        $id = $request->id;
        $data = Attendance::where("attendances.id", $id)->where("attendances.deleted_at", "=", null)->leftJoin('users', 'users.id', 'attendances.user_id')->select("attendances.*", "users.name as name", "users.id as uid")->first();
        if ($data == null) {
            return redirect("/admin/attend-manage")->with('error', '指定された勤怠情報が見つかりません。');
        }
        return view('admin.attend-manage.view', compact('data', 'id'));
    }

    function edit(Request $request)
    {
        $id = $request->id;
        $data = Attendance::where("attendances.id", $id)->where("attendances.deleted_at", "=", null)->leftJoin('users', 'users.id', 'attendances.user_id')->select("attendances.*", "users.name as name", "users.id as uid")->first();
        if ($data == null) {
            return redirect("/admin/attend-manage")->with('error', '指定された勤怠情報が見つかりません。');
        }
        return view('admin.attend-manage.edit', compact('data', 'id'));
    }

    function new(Request $request)
    {
        $users = User::where('left_date', "=", null)->get();
        return view('admin.attend-manage.new', compact('users'));
    }

    function deleteData(Request $request)
    {
        if (empty($request->id)) {
            return redirect("/admin/attend-manage")->with('error', '指定された勤怠情報が見つかりません。(E20)');
        }
        $id = $request->id;
        $data = Attendance::find($id);
        if ($data == null || $data->deleted_at != null) {
            return redirect("/admin/attend-manage")->with('error', '指定された勤怠情報が見つかりません。(E21)');
        }
        try {
            $data->update(['deleted_at' => new DateTime()]);
            return redirect("/admin/attend-manage")->with('result', '勤怠情報を削除しました。');
        } catch (\Exception $e) {
            return redirect("/admin/attend-manage")->with('error', '勤怠情報の削除に失敗しました。(E30)');
        }
    }

    function createData(Request $request)
    {
        $rules = [
            'date' => 'required',
            'status' => 'required|numeric',
            'start' => 'required',
            'rest' => 'required',
        ];
        $messages = [
            'date.required' => '日付が選択されていません',
            'status.required' => '状態が選択されていません',
            'status.numeric' => '状態を選択してください',
            'rest.required' => '休憩時間が記入されていません',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(["error" => true, "code" => 1, "message" => "以下の必須項目が記入されていません", "errors" => $validator->errors()]);
        }
        $selectedDate = new DateTime($request->date);
        $startDate = $selectedDate->format("Y-m-d " . $request->start . ":00");
        $endDate = null;
        if (isset($request->end) && $request->end != "") {
            $endDate = $selectedDate->format("Y-m-d " . $request->end . ":00");
        }
        $workTime = "00:00";

        if ($endDate != null) {
            $current = strtotime($endDate);
            $before = strtotime($startDate);
            $diff = $current - $before;
            $hours = intval($diff / 60 / 60);
            $minutes = intval($diff / 60) % 60;
            $workTime = sprintf("%02d", $hours) . ":" . sprintf("%02d", $minutes);
        }
        $param = [
            'user_id' => $request->user,
            'date' => $selectedDate->format("Y-m-d"),
            'mode' => $request->status,
            'created_at' => $startDate,
            'left_at' => $endDate,
            'time' => $workTime,
            'rest' => $request->rest,
            'comment' => $request->comment ?? "",
        ];
        $id = Attendance::create($param)->id;
        return response()->json(["error" => false, "code" => 0, "message" => "勤怠情報を作成しました。", "id" => $id]);
    }

    function editData(Request $request)
    {
        $id = $request->id;
        $data = Attendance::find($id);
        if ($data == null || $data->deleted_at != null) {
            return response()->json(["error" => true, "code" => 20, "message" => "指定された勤怠情報が見つかりません。"]);
        }
        $rules = [
            'date' => 'required',
            'status' => 'required|numeric',
            'start' => 'required',
        ];
        $messages = [
            'date.required' => '日付が選択されていません',
            'status.required' => '状態が選択されていません',
            'status.numeric' => '状態を選択してください',
            'start.required' => '出勤時刻が記入されていません',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(["error" => true, "code" => 1, "message" => "以下の必須項目が記入されていません", "errors" => $validator->errors()]);
        }
        try {
            $date = $request->date ?? $data->date;
            $status = $request->status ?? $data->mode;
            $endTime = $request->end;
            if ($status == 1 && $request->end == null) {
                return response()->json(["error" => true, "code" => 23, "message" => "退勤済みに設定する場合は退勤時刻を記入してください。"]);
            }
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
            } else {
                $endDate = new DateTime($data->left_at);
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
                'date' => $date,
                'mode' => $status,
                'created_at' => $startDate,
                'left_at' => $endDate,
                'time' => $workTime,
                'rest' => $restTime,
            ];
            $data->update($param);
            return response()->json(["error" => false, "code" => 0, "message" => "勤怠情報(" . $id . ")を更新しました。"]);
        } catch (\Exception $e) {
            return response()->json(["error" => true, "code" => 21, "message" => "データの処理中に問題が発生しました。\n" . $e->getMessage() . "\n" . $e->getTraceAsString() . ""]);
        }
        //return view('admin.attend-manage.edit', compact('data', 'id'));
    }

    public function showUserCalender(Request $requestData): Factory|View|Application
    {
        $mode = $requestData->mode ?? 0;
        $user_id = $requestData->id;
        $user = User::find($user_id);
        if ($user == null) {
            return redirect("/admin/attend-manage")->with('error', '指定されたユーザーが見つかりません。(E20)');
        }

        $tempDate = new DateTime();
        $year = min(9999, max(0, $requestData->year ?? intval($tempDate->format('Y'))));
        $month = min(12, max(1, $requestData->month ?? intval($tempDate->format('m'))));
        $cYear = intval($tempDate->format('Y'));
        $cMonth = intval($tempDate->format('m'));
        $day = intval($tempDate->format('d'));
        $cDay = $day;

        $joinDate = new DateTime($user->joined_date);
        $joinYear = intval($joinDate->format('Y'));
        $joinMonth = intval($joinDate->format('m'));
        $joinDay = intval($joinDate->format('d'));

        $likeMonth = $year . "-" . sprintf('%02d', $month) . "-";
        //echo $likeMonth." / ";
        $dataList = Attendance::where('user_id', '=', $user->id)->where("attendances.deleted_at", "=", null)->where('date', 'LIKE', "%$likeMonth%")->get();

        $tempDate = new DateTime();
        $todayData = Attendance::where("user_id", "=", $user->id)->where("attendances.deleted_at", "=", null)->where("date", "=", $tempDate->format('Y-n-j'))->orderByDesc("date")->first();
        $hours = 0;
        $minutes = 0;
        $hoursReq = 0;
        $minutesReq = 0;
        foreach ($dataList as $dat) {
            if ($dat->time == null || $dat->mode != 1) {
                continue;
            }
            $datArray = preg_split("/:/", $dat->time);
            $restData = preg_split("/:/", $dat->rest);
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
            $hours += $xhours;
            $minutes += $xminutes;
        }
        $date_now = new DateTime();
        if ($todayData != null) {
            if ($todayData->mode == 1) {
                $date_now = $todayData->updated_at;
            }
            $interval = $todayData->created_at->diff($date_now);
            $createDate = new DateTime($todayData->created_at);
            if ($todayData->mode == 0) {
                $hours += intval($interval->format('%h'));
                $minutes += intval($interval->format('%i'));
            }
        }

        $hours += intval($minutes / 60);
        $minutes = $minutes % 60;
        $requests = VariousRequest::where('user_id', '=', $user->id)->where('status', '=', 1)->where('date', 'LIKE', "%$likeMonth%")->leftJoin('request_types', 'various_requests.type', '=', 'request_types.id')->select("various_requests.*", "request_types.name as name", "request_types.color as color")->get();

        //$allRequests = VariousRequest::where("user_id", "=", $user->id)->get(); //->where("type", "=", 1)

        foreach ($requests as $dat) {
            if ($dat->time == null) {
                continue;
            }
            $datArray = preg_split("/:/", $dat->time);
            $hoursReq += intval($datArray[0]);
            $minutesReq += intval($datArray[1]);
        }

        $hoursReq += intval($minutesReq / 60);
        $minutesReq = $minutesReq % 60;

        $attendData = [];
        $reqData = [];
        foreach ($dataList as $data) {
            $attendData[$data->date] = $data;
        }
        $holidaysData = null;
        if ($year > $joinYear || ($year == $joinYear && $month >= $joinMonth)) {
            $holidaysData = Holiday::where('deleted_at', null)->where(function ($query) use ($year) {
                $query->where('year', '=', null)
                    ->orWhere('year', '=', $year);
            })->where(function ($query) use ($month) {
                $query->where('month', '=', null)
                    ->orWhere('month', '=', $month);
            });
            if ($year == $joinYear && $month == $joinMonth) {
                $holidaysData->where('day', '>=', $joinDay);
            }
        }


        //$holidaysData = Holiday::where('year', null)->orWhere('year', intval($year))->where('month', null)->orWhere('month', intval($month))->get();

        $holidays = [];

        if ($holidaysData != null) {
            foreach ($holidaysData->get() as $holiday) {
                $holidays[$holiday->day][] = $holiday;
            }
        }

        $reqHtml = "";
        foreach ($requests as $request) {
            $type = $request->type;
            if ($mode == 1) {
                if ($request->time == null) {
                    $reqHtml .= '<span style="color: ' . $request->color . ';" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $request->name . '">●</span>';
                } else {
                    $time = $request->time;
                    $formatTime = new DateTime($time);
                    $formatTime = $formatTime->format("G:i");
                    $reqHtml .= '<span style="color: ' . $request->color . ';" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $request->name . ' (' . $formatTime . ')">●</span>';
                }
            } else {
                if ($request->time == null) {
                    $reqHtml .= '<div><span style="color: ' . $request->color . ';">●</span> <strong>' . $request->name . '</strong></div>';
                } else {
                    $time = $request->time;
                    $formatTime = new DateTime($time);
                    $formatTime = $formatTime->format("G:i");
                    $reqHtml .= '<div><span style="color: ' . $request->color . ';">●</span> <strong>' . $formatTime . '</strong></div>';
                }
            }
            if (array_key_exists($request->date, $reqData)) {
                $currentReq = $reqData[$request->date];
                $currentReq[0][] = $request;
                $currentReq[1] .= $reqHtml;
                $reqData[$request->date] = $currentReq;
            } else {
                $reqData[$request->date] = [[$request], $reqHtml];
            }
            $reqHtml = "";
        }
        $dt = Carbon::createFromDate($year, $month);
        CalenderUtil::renderCalendar($dt);
        $cats = RequestType::all();
        $data = MonthlyReport::where("user_id", "=", $user->id)->where("date", "=", $year . "-" . sprintf("%02d", $month))->first();
        $confirmStatus = 0;
        if ($data != null) {
            $confirmStatus = $data->status;
        }
        $joinDate = new DateTime($user->joined_date);
        $joinYear = intval($joinDate->format('Y'));
        $joinMonth = intval($joinDate->format('m'));
        if ($year > $cYear || $month > $cMonth || $year < $joinYear || $month < $joinMonth) {
            $confirmStatus = -1;
        }
        return view('admin.attend-manage.calender', compact('requestData', 'dt', 'attendData', 'reqData', 'year', 'month', 'mode', 'cats', 'day', 'cYear', 'cMonth', 'cDay', 'confirmStatus', 'hours', 'minutes', 'hoursReq', 'minutesReq', 'holidays', 'user'));
    }

    public function approveReport(Request $request): Redirector|Application|RedirectResponse
    {

        $user_id = $request->id;
        $tempDate = new DateTime();
        if (isset($request->year) && isset($request->month)) {
            $tempDate = new DateTime($request->year . "-" . $request->month . "-01");
        }
        $year = $request->year ?? intval($tempDate->format('Y'));
        $month = $request->month ?? intval($tempDate->format('m'));
        $cYear = intval($tempDate->format('Y'));
        $cMonth = intval($tempDate->format('m'));


        $data = MonthlyReport::where("user_id", "=", $user_id)->where("date", "=", $tempDate->format('Y-m'))->first();
        if ($data != null) {
            if ($data->status == 2) {
                return redirect("/admin/attend-manage/calender/" . $user_id . "?year=$year&month=$month")->with('error', '既に承認しています。');
            } else if ($data->status != 1) {
                return redirect("/admin/attend-manage/calender/" . $user_id . "?year=$year&month=$month")->with('error', 'この月の月報は確定されていないため承認出来ません。');
            } else {
                MonthlyReport::find($data->id)->update(['status' => 2]);
                Notification::create(['user_id' => $data->user_id, 'title' => '月報が承認されました', 'data' => '月報(' . $year . '年' . $month . '月度)が承認されました。承認の解除については管理部までご連絡ください。', 'url' => 'attend-manage?year=' . $year . '&month=' . $month . '&mode=0', 'status' => 0]);
                return redirect("/admin/attend-manage/calender/" . $user_id . "?year=$year&month=$month")->with('result', '月報を承認しました。');
            }
        }
        return redirect("/admin/attend-manage/calender/" . $user_id . "?year=$year&month=$month")->with('error', 'この月の月報は確定されていないため承認出来ません。');
    }

    public function unapproveReport(Request $request): Redirector|Application|RedirectResponse
    {

        $user_id = $request->id;
        $tempDate = new DateTime();
        if (isset($request->year) && isset($request->month)) {
            $tempDate = new DateTime($request->year . "-" . $request->month . "-01");
        }
        $year = $request->year ?? intval($tempDate->format('Y'));
        $month = $request->month ?? intval($tempDate->format('m'));
        $cYear = intval($tempDate->format('Y'));
        $cMonth = intval($tempDate->format('m'));


        $data = MonthlyReport::where("user_id", "=", $user_id)->where("date", "=", $tempDate->format('Y-m'))->first();
        if ($data != null) {
            if ($data->status == 2) {
                MonthlyReport::find($data->id)->update(['status' => 1]);
                return redirect("/admin/attend-manage/calender/" . $user_id . "?year=$year&month=$month")->with('result', '月報の承認を取り消しました。');
            } else {
                return redirect("/admin/attend-manage/calender/" . $user_id . "?year=$year&month=$month")->with('error', 'まだ月報が承認されていません。');
            }
        }
        return redirect("/admin/attend-manage/calender/" . $user_id . "?year=$year&month=$month")->with('error', 'まだ月報が承認されていません。');
    }

    function exportDataCsv(Request $request, int $user_id, int $year, int $month = -1)
    {
        $user = User::find($user_id);
        if ($user == null) {
            return;
        }
        $likeMonth = "";
        if ($month == -1) {
            $likeMonth = $year . "-";
        } else {
            $likeMonth = $year . "-" . sprintf('%02d', $month) . "-";
        }
        $dataList = Attendance::where('user_id', '=', $user_id)->where("attendances.deleted_at", "=", null)->where('date', 'LIKE', "%$likeMonth%")->get();
        if ($dataList == null) {
            return;
        }
        $data = "date,status,comment,workTime,restTime,joined,left";
        foreach ($dataList as $dat) {
            $status = $dat->mode == 1 ? "退勤" : "出勤";
            $rest = $dat->rest ?? "00:00:00";
            $comment = $dat->comment ?? "";
            $data .= "\n{$dat->date},{$status},{$dat->comment},{$dat->time},{$rest},{$dat->created_at},{$dat->left_at}";
        }
        $data = mb_convert_encoding($data, "SJIS", "UTF-8");
        $fileName = "";
        if ($month == -1) {
            $fileName = "{$user->name} 勤務データ({$year}年度).csv";
        } else {
            $fileName = "{$user->name} 勤務データ({$year}年{$month}月).csv";
        }
        DownloadUtil::downloadData($data, $fileName, 'text/csv');
    }

    function exportRequestDataCsv(Request $request, int $user_id, int $year, int $month = -1)
    {
        $user = User::find($user_id);
        if ($user == null) {
            return;
        }
        $likeMonth = "";
        if ($month == -1) {
            $likeMonth = $year . "-";
        } else {
            $likeMonth = $year . "-" . sprintf('%02d', $month) . "-";
        }
        $dataList = VariousRequest::where('user_id', '=', $user_id)->where('various_requests.date', 'LIKE', "%$likeMonth%")->leftJoin('request_types', 'request_types.id', 'various_requests.type')->select('various_requests.*', 'request_types.name as rname')->get();
        if ($dataList == null) {
            return;
        }
        $data = "date,uuid,type,typeRaw,status,reason,relatedId,time,created,updated";
        foreach ($dataList as $dat) {
            $status = $dat->mode == 1 ? "退勤" : "出勤";
            $rest = $dat->rest ?? "00:00:00";
            $comment = $dat->comment ?? "";
            $data .= "\n{$dat->date},{$dat->uuid},{$dat->rname},{$dat->type},{$dat->status},{$dat->reason},{$dat->related_id},{$dat->time},{$dat->created_at},{$dat->updated_at}";
        }
        $data = mb_convert_encoding($data, "SJIS", "UTF-8");
        $fileName = "";
        if ($month == -1) {
            $fileName = "{$user->name} 申請データ({$year}年度).csv";
        } else {
            $fileName = "{$user->name} 申請データ({$year}年{$month}月).csv";
        }
        DownloadUtil::downloadData($data, $fileName, 'text/csv');
    }

}
