<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\CalenderUtil;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\MonthlyReport;
use App\Models\Notification;
use App\Models\RequestType;
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
        $year = min(9999, max(0, $requestData->year ?? intval($tempDate->format('Y'))));
        $month = min(12, max(1, $requestData->month ?? intval($tempDate->format('m'))));
        $cYear = intval($tempDate->format('Y'));
        $cMonth = intval($tempDate->format('m'));
        $day = intval($tempDate->format('d'));
        $cDay = $day;

        $joinDate = new DateTime(Auth::user()->joined_date);
        $joinYear = intval($joinDate->format('Y'));
        $joinMonth = intval($joinDate->format('m'));
        $joinDay = intval($joinDate->format('d'));

        $likeMonth = $year . "-" . sprintf('%02d', $month) . "-";
        //echo $likeMonth." / ";
        $dataList = Attendance::where('user_id', '=', Auth::id())->where("attendances.deleted_at", "=", null)->where('date', 'LIKE', "%$likeMonth%")->orderByDesc("date")->get();

        $tempDate = new DateTime();
        $todayData = Attendance::where("user_id", "=", Auth::id())->where("attendances.deleted_at", "=", null)->where("date", "=", $tempDate->format($year . '-' . $month . '-j'))->orderByDesc("date")->first();
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
                $datArray = preg_split("/:/", $interval->format('%h:%i:%s'));
                $restData = preg_split("/:/", $todayData->rest);
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
                //$hours += intval($interval->format('%h'));
                //$minutes += intval($interval->format('%i'));
            }
        }

        $hours += intval($minutes / 60);
        $minutes = $minutes % 60;
        $requests = VariousRequest::where('user_id', '=', Auth::id())->where('status', '=', 1)->where('date', 'LIKE', "%$likeMonth%")->leftJoin('request_types', 'various_requests.type', '=', 'request_types.id')->select("various_requests.*", "request_types.name as name", "request_types.color as color")->get();

        //$allRequests = VariousRequest::where("user_id", "=", Auth::id())->get(); //->where("type", "=", 1)

        foreach ($requests as $dat) {
            if ($dat->time == null) {
                continue;
            }
            $datType = RequestType::find($dat->type);
            if ($datType != null && $datType->type == -1) {
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
                    $reqHtml .= '<span style="color: ' . $request->color . ';" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $request->name . '">???</span>';
                } else {
                    $time = $request->time;
                    $formatTime = new DateTime($time);
                    $formatTime = $formatTime->format("G:i");
                    $reqHtml .= '<span style="color: ' . $request->color . ';" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $request->name . ' (' . $formatTime . ')">???</span>';
                }
            } else {
                if ($request->time == null) {
                    $reqHtml .= '<div><span style="color: ' . $request->color . ';">???</span> <strong>' . $request->name . '</strong></div>';
                } else {
                    $time = $request->time;
                    $formatTime = new DateTime($time);
                    $formatTime = $formatTime->format("G:i");
                    $reqHtml .= '<div><span style="color: ' . $request->color . ';">???</span> <strong>' . $formatTime . '</strong></div>';
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
        $dt = Carbon::parse($year . '-' . $month . '-1 0:00:00');
        CalenderUtil::renderCalendar($dt);
        $cats = RequestType::all();
        $data = MonthlyReport::where("user_id", "=", Auth::id())->where("date", "=", $year . "-" . sprintf("%02d", $month))->first();
        $confirmStatus = 0;
        if ($data != null) {
            $confirmStatus = $data->status;
        }
        $joinDate = new DateTime(Auth::user()->joined_date);
        $joinYear = intval($joinDate->format('Y'));
        $joinMonth = intval($joinDate->format('m'));

        if ($year < $joinYear || $year > $cYear || ($year <= $joinYear && $month < $joinMonth) || ($year >= $cYear && $month > $cMonth)) {
            $confirmStatus = -1;
        }
        $user = Auth::user();
        if ($mode == 1 && env('ENABLE_LIST_VIEW', true)) {
            return view('front.attend-manage.list', compact('requestData', 'dt', 'attendData', 'reqData', 'year', 'month', 'mode', 'cats', 'day', 'cYear', 'cMonth', 'cDay', 'confirmStatus', 'hours', 'minutes', 'hoursReq', 'minutesReq', 'holidays', 'user'));
        } else {
            return view('front.attend-manage.index', compact('requestData', 'dt', 'attendData', 'reqData', 'year', 'month', 'mode', 'cats', 'day', 'cYear', 'cMonth', 'cDay', 'confirmStatus', 'hours', 'minutes', 'hoursReq', 'minutesReq', 'holidays', 'user'));
        }
    }

    public function confirmReport(Request $request): Redirector|Application|RedirectResponse
    {

        $tempDate = new DateTime();
        if (isset($request->year) && isset($request->month)) {
            $tempDate = new DateTime($request->year . "-" . $request->month . "-01");
        }
        $year = $request->year ?? intval($tempDate->format('Y'));
        $month = $request->month ?? intval($tempDate->format('m'));
        $cYear = intval($tempDate->format('Y'));
        $cMonth = intval($tempDate->format('m'));


        $data = MonthlyReport::where("user_id", "=", Auth::id())->where("date", "=", $tempDate->format('Y-m'))->first();
        if ($data != null) {
            if ($data->status == 1) {
                return redirect("/attend-manage?year={$year}&month={$month}")->with('error', '??????????????????????????????');
            } else {
                MonthlyReport::find($data->id)->update(['status' => 1]);
                Notification::publish(['user_id' => 0, 'title' => '??????????????????????????????', 'data' => Auth::user()->name . '???' . $month . '????????????????????????????????????', 'url' => '/admin/attend-manage/calender/' . Auth::id() . '?year=' . $year . '&month=' . $month . '&mode=0', 'status' => 0]);
                return redirect("/attend-manage?year={$year}&month={$month}")->with('result', '??????????????????????????????');
            }
        }
        MonthlyReport::create([
            'user_id' => Auth::id(),
            'date' => $tempDate->format('Y-m'),
            'status' => 1,
        ]);
        Notification::publish(['user_id' => 0, 'title' => '??????????????????????????????', 'data' => Auth::user()->name . '???' . $month . '????????????????????????????????????', 'url' => '/admin/attend-manage/calender/' . Auth::id() . '?year=' . $year . '&month=' . $month . '&mode=0', 'status' => 0]);
        return redirect("/attend-manage?year={$year}&month={$month}")->with('result', '??????????????????????????????');
    }

    public function unconfirmReport(Request $request): Redirector|Application|RedirectResponse
    {

        $tempDate = new DateTime();
        if (isset($request->year) && isset($request->month)) {
            $tempDate = new DateTime($request->year . "-" . $request->month . "-01");
        }
        $year = $request->year ?? intval($tempDate->format('Y'));
        $month = $request->month ?? intval($tempDate->format('m'));
        $cYear = intval($tempDate->format('Y'));
        $cMonth = intval($tempDate->format('m'));


        $data = MonthlyReport::where("user_id", "=", Auth::id())->where("date", "=", $tempDate->format('Y-m'))->first();
        if ($data != null) {
            if ($data->status == 1) {
                MonthlyReport::find($data->id)->update(['status' => 0]);
                return redirect("/attend-manage?year={$year}&month={$month}")->with('result', '???????????????????????????????????????');
            } else {
                return redirect("/attend-manage?year={$year}&month={$month}")->with('error', '?????????????????????????????????');
            }
        }
        return redirect("/attend-manage?year={$year}&month={$month}")->with('error', '?????????????????????????????????????????????');
    }

}
