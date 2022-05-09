<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
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
        $data = Attendance::where("user_id", "=", Auth::id())->where("date", "=", $tempDate->format('Y-n-j'))->orderByDesc("date")->first();
        $date_now = new DateTime();
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
        $data = Attendance::where("user_id", "=", Auth::id())->where("date", "=", $tempDate->format('Y-n-j'))->orderByDesc("date")->first();
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
        $data = Attendance::where("user_id", "=", Auth::id())->where("date", "=", $tempDate->format('Y-n-j'))->orderByDesc("date")->first();
        if ($data == null) {
            return redirect("/attends")->with('error', 'まだ出勤していません。');
        }
        if ($data->mode != 0) {
            return redirect("/attends")->with('error', '既に退勤しています。');
        }
        Attendance::find($data->id)->update(['mode' => 1]);
        return redirect("/attends")->with('result', '退勤しました。');
    }

    public function cancelLeft(Request $request): Redirector|Application|RedirectResponse
    {
        $tempDate = new DateTime();
        $data = Attendance::where("user_id", "=", Auth::id())->where("date", "=", $tempDate->format('Y-n-j'))->orderByDesc("date")->first();
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
        $tempDate = new DateTime();
        $data = Attendance::where("user_id", "=", Auth::id())->where("date", "=", $tempDate->format('Y-n-j'))->orderByDesc("date")->first();
        if ($data == null) {
            return response()->json(["error" => true, "code" => 21, "message" => "勤務データが見つかりません。"]);
        }
        Attendance::find($data->id)->update(['comment' => $request->text]);
        return response()->json(["code" => 0, "message" => "業務メモを更新しました。"]);
    }

}
