<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use function view;

class AdminSettingsController extends Controller
{

    function index(Request $request)
    {
        return view('admin.settings.index');
    }

    function general(Request $request)
    {
        $data = Configuration::find(1);

        $configArray = [];

        if (Storage::disk('local')->exists('config/paid_holidays.csv')) {
            $config = Storage::disk('local')->get('config/paid_holidays.csv');
            $config = str_replace(array("\r\n", "\r"), "\n", $config);
            $configArray = collect(explode("\n", $config));
        }
        return view('admin.settings.general.index', compact('data', 'configArray'));
    }

    function editGeneral(Request $request)
    {
        $data = Configuration::find(1);

        return view('admin.settings.general.edit', compact('data'));
    }

    function updateGeneral(Request $request)
    {
        $rules = [
            'start' => 'date_format:H:i|required',
            'end' => 'date_format:H:i|required',
            'rest' => 'date_format:H:i|required',
            'rest_over' => 'date_format:H:i|required',
        ];
        $messages = [
            'start.required' => '始業時刻を記入してください',
            'end.required' => '終業時刻を記入してください',
            'rest.required' => '休憩時間(標準)を記入してください',
            'rest_over.required' => '休憩時間(残業)を記入してください',
            'start.date_format' => '始業時刻を時刻の形式で記入してください',
            'end.date_format' => '終業時刻を時刻の形式で記入してください',
            'rest.date_format' => '休憩時間(標準)を時刻の形式で記入してください',
            'rest_over.date_format' => '休憩時間(残業)を時刻の形式で記入してください',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(["error" => true, "code" => 1, "message" => "必須項目が記入されていません ", "errors" => $validator->errors()]);
        }
        $id = $request->id;
        $data = Configuration::find(1);
        $startTimeInt = intval(preg_replace("/:/", "", $request->start));
        $endTimeInt = intval(preg_replace("/:/", "", $request->end));
        $restTimeArray = preg_split("/:/", $request->rest);
        $restSecs = ($restTimeArray[0] * 60 * 60) + ($restTimeArray[1] * 60);
        $startTime = strtotime("2000-01-01 " . $request->start . ":00");
        $endTime = strtotime("2000-01-01 " . $request->end . ":00");
        $diff = $this->getTimeDiff($startTime, $endTime, $restSecs);
        $param = [
            'start' => $request->start,
            'end' => $request->end,
            'time' => $diff,
            'rest' => $request->rest,
            'rest_over' => $request->rest_over,
        ];
        if ($startTimeInt >= $endTimeInt) {
            return response()->json(["error" => true, "code" => 20, "message" => "始業時間が終業時間よりも遅く設定されています。"]);
        }
        if (!$this->updateHolidayConfig($request)) {
            return response()->json(["error" => true, "code" => 22, "message" => "有給設定CSVの形式が異なります。"]);
        }
        if ($data == null) {
            Configuration::create($param);
            return response()->json(["error" => false, "code" => 0, "message" => "設定を更新しました。勤務時間は" . $diff . "です。"]);
        }
        try {
            $data->update($param);
            return response()->json(["error" => false, "code" => 0, "message" => "設定を更新しました。勤務時間は" . $diff . "です。"]);
        } catch (\Exception $e) {
            return response()->json(["error" => true, "code" => 21, "message" => "データの処理中に問題が発生しました。\n" . $e->getMessage() . "\n" . $e->getTraceAsString() . ""]);
        }
    }

    function updateHolidayConfig(Request $request)
    {
        if (empty($request->paid_holiday)) {
            return true;
        }
        $data = Storage::disk('local')->putFileAs('config', $request->paid_holiday, 'paid_holidays.csv');

        if (Storage::disk('local')->exists('config/paid_holidays.csv')) {
            $config = Storage::disk('local')->get('config/paid_holidays.csv');
            $config = str_replace(array("\r\n", "\r"), "\n", $config);
            $configArray = collect(explode("\n", $config));
            foreach ($configArray as $index => $item) {
                $dat = preg_split("/,/", $item);
                if ($index == 0 && ($dat[0] != "months" || $dat[1] != "days")) {
                    $config = Storage::disk('local')->delete('config/paid_holidays.csv');
                    return false;
                }
            }
        }
        return true;
    }

    function getTimeDiff($startTime, $endTime, $restSecs): string
    {
        $diffTime = $endTime - $startTime - $restSecs;
        return date("H:i", $diffTime + 54000);
    }

    function holiday(Request $request)
    {
        $data = Holiday::where("deleted_at", "=", null)->paginate(20);
        return view('admin.settings.holiday.index', compact('data'));
    }

    function newHoliday(Request $request)
    {
        return view('admin.settings.holiday.new');
    }

    function createHoliday(Request $request)
    {
        $rules = [
            'day' => 'required|numeric',
            'mode' => 'required|numeric',
            'name' => 'required',
        ];
        $messages = [
            'day.required' => '日を記入してください',
            'day.numeric' => '日を数値で記入してください',
            'mode.required' => '種別を選択してください',
            'mode.numeric' => '種別を選択してください',
            'name.required' => '名称を記入してください',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(["error" => true, "code" => 1, "message" => "必須項目が記入されていません", "errors" => $validator->errors()]);
        }
        try {
            $year = $request->year;
            $month = $request->month;
            $day = $request->day;
            $mode = $request->mode;
            $name = $request->name;
            if (($year != null && $year != "") && $year <= 2020) {
                return response()->json(["error" => true, "code" => 22, "message" => "2021年以降の休日のみ設定できます。"]);
            }
            if (($month != null && $month != "") && ($month > 12 || $month < 1)) {
                return response()->json(["error" => true, "code" => 23, "message" => "月を正しく指定してください。"]);
            }
            if ($day > 31 || $day < 1) {
                return response()->json(["error" => true, "code" => 24, "message" => "日を正しく指定してください。"]);
            }
            if ($mode > 1 || $mode < 0) {
                return response()->json(["error" => true, "code" => 25, "message" => "種別を正しく指定してください。"]);
            }
            $param = [
                'name' => $name,
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'mode' => $mode,
            ];
            $id = Holiday::create($param)->id;
            return response()->json(["error" => false, "code" => 0, "message" => "休日を作成しました。", "id" => $id]);
        } catch (\Exception $e) {
            return response()->json(["error" => true, "code" => 21, "message" => "データの処理中に問題が発生しました。\n" . $e->getMessage() . "\n" . $e->getTraceAsString() . ""]);
        }
        //return view('admin.attend-manage.edit', compact('data', 'id'));
    }

    function viewHoliday(Request $request)
    {
        $id = $request->id;
        $data = Holiday::find($id);
        if ($data == null || $data->deleted_at != null) {
            return response()->json(["error" => true, "code" => 20, "message" => "指定された休日が見つかりません。"]);
        }
        return view('admin.settings.holiday.edit', compact('data', 'id'));
    }

    function editHoliday(Request $request)
    {
        $rules = [
            'day' => 'required|numeric',
            'mode' => 'required|numeric',
            'name' => 'required',
        ];
        $messages = [
            'day.required' => '日を記入してください',
            'day.numeric' => '日を数値で記入してください',
            'mode.required' => '種別を選択してください',
            'mode.numeric' => '種別を選択してください',
            'name.required' => '名称を記入してください',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(["error" => true, "code" => 1, "message" => "必須項目が記入されていません", "errors" => $validator->errors()]);
        }
        $id = $request->id;
        $data = Holiday::find($id);
        if ($data == null || $data->deleted_at != null) {
            return response()->json(["error" => true, "code" => 20, "message" => "指定された休日が見つかりません。"]);
        }
        try {
            $year = $request->year;
            $month = $request->month;
            $day = $request->day;
            $mode = $request->mode;
            $name = $request->name;
            if (($year != null && $year != "") && $year <= 2020) {
                return response()->json(["error" => true, "code" => 22, "message" => "2021年以降の休日のみ設定できます。"]);
            }
            if (($month != null && $month != "") && ($month > 12 || $month < 1)) {
                return response()->json(["error" => true, "code" => 23, "message" => "月を正しく指定してください。"]);
            }
            if ($day > 31 || $day < 1) {
                return response()->json(["error" => true, "code" => 24, "message" => "日を正しく指定してください。"]);
            }
            if ($mode > 1 || $mode < 0) {
                return response()->json(["error" => true, "code" => 25, "message" => "種別を正しく指定してください。"]);
            }
            $param = [
                'name' => $name,
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'mode' => $mode,
            ];
            $data->update($param);
            return response()->json(["error" => false, "code" => 0, "message" => "休日(" . $id . ")を更新しました。"]);
        } catch (\Exception $e) {
            return response()->json(["error" => true, "code" => 21, "message" => "データの処理中に問題が発生しました。\n" . $e->getMessage() . "\n" . $e->getTraceAsString() . ""]);
        }
        //return view('admin.attend-manage.edit', compact('data', 'id'));
    }

}
