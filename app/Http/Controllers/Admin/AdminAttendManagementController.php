<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attendance;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminAttendManagementController
{

    function index(Request $request)
    {
        $users = User::where('left_date', "=", null)->get();
        $data = Attendance::where("attendances.deleted_at", "=", null)->leftJoin('users', 'attendances.user_id', 'users.id')->select("attendances.*", "users.name as name")->orderByDesc('attendances.updated_at')->paginate(20);
        $title = "勤怠ログ";
        $searchStr = "";
        return view('admin.attend-manage.index', compact('data', 'users', 'title', 'searchStr'));
    }

    function search(Request $request)
    {
        $users = User::where('left_date', "=", null)->get();
        $data = Attendance::where("attendances.deleted_at", "=", null)->leftJoin('users', 'attendances.user_id', 'users.id')->select("attendances.*", "users.name as name", "users.id as uid");
        $searchArray = [];
        if (isset($request->user)) {
            $data = $data->where("users.id", $request->user);
            $user = User::find($request->user);
            if ($user != null) {
                $searchArray[] = "<strong>ユーザー: </strong>" . $user->name;
            }
        }
        if (isset($request->date)) {
            $data = $data->where("date", $request->date);
            $searchArray[] = "<strong>日付: </strong>" . $request->date;
        }
        if (isset($request->status)) {
            $data = $data->where("mode", $request->status);
            $searchArray[] = "<strong>状態: </strong>" . $request->status;
        }
        $title = "勤怠ログ | 検索";
        $data = $data->orderByDesc('attendances.updated_at')->paginate(20);
        $searchStr = join(" / ", $searchArray);
        return view('admin.attend-manage.index', compact('data', 'users', 'title', 'searchStr'));
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
        $selectedDate = new DateTime($request->date);
        $startDate = $selectedDate->format("Y-m-d " . $request->start . ":00");
        $endDate = null;
        if (isset($request->end) && $request->end != "") {
            $endDate = $selectedDate->format("Y-m-d " . $request->end . ":00");
        }
        $param = [
            'user_id' => $request->user,
            'date' => $selectedDate->format("Y-m-d"),
            'mode' => $request->status,
            'created_at' => $startDate,
            'left_at' => $endDate,
            'time' => $request->work,
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
        try {
            $date = $request->date ?? $data->date;
            $status = $request->status ?? $data->mode;
            $endTime = $request->end;
            $workTime = $request->work;
            $startDate = $data->created_at;
            if ($workTime != "" && $workTime != "00:00") {
                $workDate = new DateTime($data->created_at->format("Y-m-d ${workTime}:00"));
                $workTime = $workDate->format("G:i");
            }
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
                $endDate = $data->left_at;
            }
            $param = [
                'date' => $date,
                'mode' => $status,
                'created_at' => $startDate,
                'left_at' => $endDate,
                'time' => $workTime,
            ];
            $data->update($param);
            return response()->json(["error" => false, "code" => 0, "message" => "勤怠情報(" . $id . ")を更新しました。"]);
        } catch (\Exception $e) {
            return response()->json(["error" => true, "code" => 21, "message" => "データの処理中に問題が発生しました。\n" . $e->getMessage() . "\n" . $e->getTraceAsString() . ""]);
        }
        //return view('admin.attend-manage.edit', compact('data', 'id'));
    }

}
