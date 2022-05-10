<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\CalenderUtil;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\MonthlyReport;
use App\Models\RequestTypes;
use App\Models\VariousRequest;
use DateTime;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceManagementController extends Controller
{

    public function index(Request $requestData): Factory|View|Application
    {
        $mode = $requestData->mode ?? 0;

        $tempDate = new DateTime();
        $year = $requestData->year ?? intval($tempDate->format('Y'));
        $month = $requestData->month ?? intval($tempDate->format('m'));
        $cYear = intval($tempDate->format('Y'));
        $cMonth = intval($tempDate->format('m'));
        $day = intval($tempDate->format('d'));
        $likeMonth = $year . "-" . sprintf('%02d', $month) . "-";
        //echo $likeMonth." / ";
        $dataList = Attendance::where('user_id', '=', Auth::id())->where('date', 'LIKE', "%$likeMonth%")->get();
        $attendData = [];
        $reqData = [];
        foreach ($dataList as $data) {
            $attendData[$data->date] = $data;
        }

        $requests = VariousRequest::where('user_id', '=', Auth::id())->where('status', '=', 1)->where('date', 'LIKE', "%$likeMonth%")->leftJoin('request_types', 'various_requests.type', '=', 'request_types.id')->select("various_requests.*", "request_types.name as name", "request_types.color as color")->get();
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
        $cats = RequestTypes::all();
        $data = MonthlyReport::where("user_id", "=", Auth::id())->where("date", "=", $year . "-" . sprintf("%02d", $month))->first();
        $confirmStatus = 0;
        if ($data != null) {
            $confirmStatus = $data->status;
        }
        $joinDate = new DateTime(Auth::user()->joined_date);
        $joinYear = intval($joinDate->format('Y'));
        $joinMonth = intval($joinDate->format('m'));
        if ($year > $cYear || $month > $cMonth || $year < $joinYear || $month < $joinMonth) {
            $confirmStatus = -1;
        }
        if ($mode == 1) {
            return view('front.attend-manage.list', compact('requestData', 'dt', 'attendData', 'reqData', 'year', 'month', 'mode', 'cats', 'day', 'cYear', 'cMonth', 'confirmStatus'));
        } else {
            return view('front.attend-manage.index', compact('requestData', 'dt', 'attendData', 'reqData', 'year', 'month', 'mode', 'cats', 'day', 'cYear', 'cMonth', 'confirmStatus'));
        }
    }

    public function confirmReport(Request $request): Redirector|Application|RedirectResponse
    {

        $tempDate = new DateTime();
        $year = $requestData->year ?? intval($tempDate->format('Y'));
        $month = $requestData->month ?? intval($tempDate->format('m'));
        $cYear = intval($tempDate->format('Y'));
        $cMonth = intval($tempDate->format('m'));


        $data = MonthlyReport::where("user_id", "=", Auth::id())->where("date", "=", $tempDate->format('Y-m'))->first();
        if ($data != null) {
            if ($data->status == 1) {
                return redirect("/attend-manage")->with('error', '既に確定しています。');
            } else {
                MonthlyReport::find($data->id)->update(['status' => 1]);
                return redirect("/attend-manage")->with('result', '月報を確定しました。');
            }
        }
        MonthlyReport::create([
            'user_id' => Auth::id(),
            'date' => $tempDate->format('Y-m'),
            'status' => 1,
        ]);
        return redirect("/attend-manage")->with('result', '月報を確定しました。');
    }

    public function unconfirmReport(Request $request): Redirector|Application|RedirectResponse
    {

        $tempDate = new DateTime();
        $year = $requestData->year ?? intval($tempDate->format('Y'));
        $month = $requestData->month ?? intval($tempDate->format('m'));
        $cYear = intval($tempDate->format('Y'));
        $cMonth = intval($tempDate->format('m'));


        $data = MonthlyReport::where("user_id", "=", Auth::id())->where("date", "=", $tempDate->format('Y-m'))->first();
        if ($data != null) {
            if ($data->status == 1) {
                MonthlyReport::find($data->id)->update(['status' => 0]);
                return redirect("/attend-manage")->with('result', '月報の確定を解除しました。');
            } else {
                return redirect("/attend-manage")->with('error', '既に承認されています。');
            }
        }
        return redirect("/attend-manage")->with('error', 'まだ月報が確定されていません。');
    }

}
