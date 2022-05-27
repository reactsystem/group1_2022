<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Department;
use App\Models\Holiday;
use App\Models\Notification;
use App\Models\RequestType;
use DateTime;
use finfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use JetBrains\PhpStorm\NoReturn;
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
        ];
        $messages = [
            'start.required' => '始業時刻を記入してください',
            'end.required' => '終業時刻を記入してください',
            'rest.required' => '休憩時間(標準)を記入してください',
            'start.date_format' => '始業時刻を時刻の形式で記入してください',
            'end.date_format' => '終業時刻を時刻の形式で記入してください',
            'rest.date_format' => '休憩時間(標準)を時刻の形式で記入してください',
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
        if (empty($request->paid_holiday) || !$request->hasFile('paid_holiday')) {
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

    function deleteHoliday(Request $request)
    {
        if (empty($request->id)) {
            return redirect("/admin/settings/holiday")->with('error', '指定された休日が見つかりません。(E20)');
        }
        $id = $request->id;
        $data = Holiday::find($id);
        if ($data == null || $data->deleted_at != null) {
            return redirect("/admin/settings/holiday")->with('error', '指定された休日が見つかりません。(E21)');
        }
        try {
            $data->update(['deleted_at' => new DateTime()]);
            return redirect("/admin/settings/holiday")->with('result', '休日を削除しました。');
        } catch (\Exception $e) {
            return redirect("/admin/settings/holiday")->with('error', '休日の削除に失敗しました。(E30)');
        }
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

    // 部署設定

    function department(Request $request)
    {
        $data = Department::where("deleted_at", "=", null)->paginate(20); // deleted_atに変更する
        return view('admin.settings.department.index', compact('data'));
    }

    function newDepartment(Request $request)
    {
        return view('admin.settings.department.new');
    }

    function createDepartment(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'name.required' => '名称を記入してください',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(["error" => true, "code" => 1, "message" => "必須項目が記入されていません", "errors" => $validator->errors()]);
        }
        try {
            $name = $request->name;
            $param = [
                'name' => $name,
            ];
            $id = Department::create($param)->id;
            return response()->json(["error" => false, "code" => 0, "message" => "休日を作成しました。", "id" => $id]);
        } catch (\Exception $e) {
            return response()->json(["error" => true, "code" => 21, "message" => "データの処理中に問題が発生しました。\n" . $e->getMessage() . "\n" . $e->getTraceAsString() . ""]);
        }
        //return view('admin.attend-manage.edit', compact('data', 'id'));
    }

    function viewDepartment(Request $request)
    {
        $id = $request->id;
        $data = Department::find($id);
        if ($data == null || $data->deleted_at != null) {
            return response()->json(["error" => true, "code" => 20, "message" => "指定された部署が見つかりません。"]);
        }
        return view('admin.settings.department.edit', compact('data', 'id'));
    }

    function deleteDepartment(Request $request)
    {
        if (empty($request->id)) {
            return redirect("/admin/settings/department")->with('error', '指定された部署が見つかりません。(E20)');
        }
        $id = $request->id;
        $data = Department::find($id);
        if ($data == null || $data->deleted_at != null) {
            return redirect("/admin/settings/department")->with('error', '指定された部署が見つかりません。(E21)');
        }
        try {
            $data->update(['deleted_at' => new DateTime()]);
            return redirect("/admin/settings/department")->with('result', '部署を削除しました。');
        } catch (\Exception $e) {
            return redirect("/admin/settings/department")->with('error', '部署の削除に失敗しました。(E30)');
        }
    }

    function editDepartment(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'name.required' => '名称を記入してください',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(["error" => true, "code" => 1, "message" => "必須項目が記入されていません", "errors" => $validator->errors()]);
        }
        $id = $request->id;
        $data = Department::find($id);
        if ($data == null || $data->deleted_at != null) {
            return response()->json(["error" => true, "code" => 20, "message" => "指定された部署が見つかりません。"]);
        }
        try {
            $name = $request->name;
            $param = [
                'name' => $name,
            ];
            $data->update($param);
            return response()->json(["error" => false, "code" => 0, "message" => "部署(" . $id . ")を更新しました。"]);
        } catch (\Exception $e) {
            return response()->json(["error" => true, "code" => 21, "message" => "データの処理中に問題が発生しました。\n" . $e->getMessage() . "\n" . $e->getTraceAsString() . ""]);
        }
        //return view('admin.attend-manage.edit', compact('data', 'id'));
    }

    // 申請種別設定

    function requestTypes(Request $request)
    {
        $data = RequestType::where("deleted_at", "=", null)->paginate(20); // deleted_atに変更する
        return view('admin.settings.request_types.index', compact('data'));
    }

    function newRequestType(Request $request)
    {
        return view('admin.settings.request_types.new');
    }

    function createRequestType(Request $request)
    {
        $rules = [
            'name' => 'required',
            'color' => 'required',
            'type' => 'required|numeric|min:-1|max:3',
        ];
        $messages = [
            'name.required' => '名称を記入してください',
            'color.required' => 'カラーを選択してください',
            'type.required' => '名称を記入してください',
            'type.numeric' => 'タイプを正しく選択してください',
            'type.min' => 'タイプを正しく選択してください',
            'type.max' => 'タイプを正しく選択してください',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(["error" => true, "code" => 1, "message" => "必須項目が記入されていません", "errors" => $validator->errors()]);
        }
        try {
            $name = $request->name;
            $type = $request->type;
            $color = $request->color;
            $param = [
                'name' => $name,
                'color' => $color,
                'type' => $type,
            ];
            $id = RequestType::create($param)->id;
            return response()->json(["error" => false, "code" => 0, "message" => "申請種別を作成しました。", "id" => $id]);
        } catch (\Exception $e) {
            return response()->json(["error" => true, "code" => 21, "message" => "データの処理中に問題が発生しました。\n" . $e->getMessage() . "\n" . $e->getTraceAsString() . ""]);
        }
        //return view('admin.attend-manage.edit', compact('data', 'id'));
    }

    function viewRequestType(Request $request)
    {
        $id = $request->id;
        $data = RequestType::find($id);
        if ($data == null || $data->deleted_at != null) {
            return redirect("/admin/settings/request-types")->with('error', '指定された申請種別が見つかりません。(E20)');
        }
        return view('admin.settings.request_types.edit', compact('data', 'id'));
    }

    function deleteRequestType(Request $request)
    {
        if (empty($request->id)) {
            return redirect("/admin/settings/request-types")->with('error', '指定された申請種別が見つかりません。(E22)');
        }
        $id = $request->id;
        $data = RequestType::find($id);
        if ($data == null || $data->deleted_at != null) {
            return redirect("/admin/settings/request-types")->with('error', '指定された申請種別が見つかりません。(E21)');
        }
        try {
            $data->update(['deleted_at' => new DateTime()]);
            return redirect("/admin/settings/request-types")->with('result', '申請種別を削除しました。');
        } catch (\Exception $e) {
            return redirect("/admin/settings/request-types")->with('error', '申請種別の削除に失敗しました。(E30)');
        }
    }

    function editRequestType(Request $request)
    {
        $rules = [
            'name' => 'required',
            'color' => 'required',
            'type' => 'required|numeric|min:0|max:3',
        ];
        $messages = [
            'name.required' => '名称を記入してください',
            'color.required' => '名称を記入してください',
            'type.required' => '名称を記入してください',
            'type.numeric' => 'タイプを正しく選択してください',
            'type.min' => 'タイプを正しく選択してください',
            'type.max' => 'タイプを正しく選択してください',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(["error" => true, "code" => 1, "message" => "必須項目が記入されていません", "errors" => $validator->errors()]);
        }
        $id = $request->id;
        $data = RequestType::find($id);
        if ($data == null || $data->deleted_at != null) {
            return response()->json(["error" => true, "code" => 20, "message" => "指定された申請種別が見つかりません。"]);
        }
        try {
            $name = $request->name;
            $type = $request->type;
            $color = $request->color;
            $param = [
                'name' => $name,
                'color' => $color,
                'type' => $type,
            ];
            $data->update($param);
            return response()->json(["error" => false, "code" => 0, "message" => "申請種別(" . $id . ")を更新しました。"]);
        } catch (\Exception $e) {
            return response()->json(["error" => true, "code" => 21, "message" => "データの処理中に問題が発生しました。\n" . $e->getMessage() . "\n" . $e->getTraceAsString() . ""]);
        }
        //return view('admin.attend-manage.edit', compact('data', 'id'));
    }

    function notifications(Request $request)
    {
        $data = Notification::where('user_id', 0)->orderByDesc('id')->paginate(20); // deleted_atに変更する
        return view('admin.settings.notifications.index', compact('data'));
    }

    function viewNotification(Request $request)
    {
        $id = $request->id;
        $data = Notification::find($id);
        if ($data == null) {
            return redirect("/admin/settings/notifications")->with('error', '指定された通知が見つかりません。(E20)');
        }
        if ($data->user_id != 0) {
            return redirect("/admin/settings/notifications")->with('error', '指定された通知が見つかりません。(E21)');
        }
        $data->update(['status' => 1]);
        return view('admin.settings.notifications.edit', compact('data', 'id'));
    }

    function deleteNotification(Request $request)
    {
        if (empty($request->id)) {
            return redirect("/admin/settings/notifications")->with('error', '指定された通知が見つかりません。(E22)');
        }
        $id = $request->id;
        $data = Notification::find($id);
        if ($data == null) {
            return redirect("/admin/settings/notifications")->with('error', '指定された通知が見つかりません。(E21)');
        }
        if ($data->user_id != 0) {
            return redirect("/admin/settings/notifications")->with('error', '指定された通知が見つかりません。(E21)');
        }
        try {
            $data->delete();
            return redirect("/admin/settings/notifications")->with('result', '通知を削除しました。');
        } catch (\Exception $e) {
            return redirect("/admin/settings/notifications")->with('error', '通知の削除に失敗しました。(E30)');
        }
    }

    function downloadDefaultCsv()
    {
        if (Storage::disk('local')->exists('config/paid_holidays_default.csv')) {
            $config = Storage::disk('local')->path('config/paid_holidays_default.csv');
            $this->download($config);
        } else {
            echo "ERROR";
        }
    }

    /**
     * 【PHP】正しいダウンロード処理の書き方
     *
     *  https://qiita.com/fallout/items/3682e529d189693109eb
     *
     * @param $pPath string ダウンロードするファイルのパス
     * @param $pMimeType string ファイルのMIMEタイプ 省略時は自動判定
     * @return void
     */

    #[NoReturn] function download($pPath, $pMimeType = null)
    {
        //-- ファイルが読めない時はエラー(もっときちんと書いた方が良いが今回は割愛)
        if (!is_readable($pPath)) {
            die('ERROR');
        }

        //-- Content-Typeとして送信するMIMEタイプ(第2引数を渡さない場合は自動判定) ※詳細は後述
        $mimeType = (isset($pMimeType)) ? $pMimeType
            : (new finfo(FILEINFO_MIME_TYPE))->file($pPath);

        //-- 適切なMIMEタイプが得られない時は、未知のファイルを示すapplication/octet-streamとする
        if (!preg_match('/\A\S+?\/\S+/', $mimeType)) {
            $mimeType = 'application/octet-stream';
        }

        //-- Content-Type
        header('Content-Type: ' . $mimeType);

        //-- ウェブブラウザが独自にMIMEタイプを判断する処理を抑止する
        header('X-Content-Type-Options: nosniff');

        //-- ダウンロードファイルのサイズ
        header('Content-Length: ' . filesize($pPath));

        //-- ダウンロード時のファイル名
        header('Content-Disposition: attachment; filename="' . basename($pPath) . '"');

        //-- keep-aliveを無効にする
        header('Connection: close');

        //-- readfile()の前に出力バッファリングを無効化する ※詳細は後述
        while (ob_get_level()) {
            ob_end_clean();
        }

        //-- 出力
        readfile($pPath);

        //-- 最後に終了させるのを忘れない
        exit;
    }

}
