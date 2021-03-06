<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Notification;
use App\Models\PaidHoliday;
use App\Models\User;
use App\Models\UserMemo;
use DateTime;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;


class AdminAttendsController extends Controller
{
    // メイン画面
    function admin_attends()
    {
        $users = User::orderBy('id', 'asc')->paginate(10);
        $departments = Department::where('deleted_at', null)->get();
        $parameters = '';

        return view('admin/user/admin_attends', ['users' => $users, 'parameters' => $parameters, 'departments' => $departments]);
    }

    function admin_search(Request $request)
    {
        $users = User::orderBy('id', 'asc');

        if (isset($request->id)) {
            $users->where('employee_id', 'LIKE', '%' . $request->id . '%');
        }
        if (isset($request->name)) {
            $users->where('name', 'LIKE', '%' . $request->name . '%');
        }
        if (isset($request->department)) {
            $users->where('department', '=', $request->department);
        }

        $users = $users->paginate(10);
        $parameters = '';
        $departments = Department::where('deleted_at', null)->get();
        return view('admin/user/admin_attends', ['users' => $users, 'parameters' => $parameters, 'departments' => $departments]);
    }

    // ユーザー新規登録
    function admin_new()
    {
        $departments = Department::where('deleted_at', null)->get();
        return view('admin/user/admin_new', ['departments' => $departments]);
    }

    // ユーザー情報個別確認
    function admin_view(Request $request)
    {
        if (empty($request->id)) {
            return redirect('/admin/attends');
        }

        $user = User::find($request->id);
        $departments = Department::all();
        if (empty($user_memo)) $user_memo = '';
        return view('admin/user/admin_view', ['user' => $user, 'departments' => $departments, 'user_memo' => $user_memo]);
    }

    // ユーザー情報個別編集
    function admin_edit(Request $request)
    {
        if (empty($request->id)) {
            return redirect('/admin/attends');
        }

        $user = User::find($request->id);
        $departments = Department::where('deleted_at', null)->get();
        if (empty($user_memo)) $user_memo = '';
        return view('admin/user/admin_edit', ['user' => $user, 'departments' => $departments, 'user_memo' => $user_memo]);
    }

    // ユーザー追加
    function add_new_user(Request $request)
    {
        $department = Department::find($request->department ?? 0);
        if ($department == null || $department->deleted_at != null) {
            return redirect('/admin/attends/new')->with('error', '指定された部署が存在しません。');
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->department = $request->department;
        $user->employee_id = $request->employee_id;
        $user->group_id = $request->group_id;
        $user->joined_date = $request->joined_date;

        $user->save();
        $last_insert_id = $user->id;

        $user_memo = new UserMemo();
        $user_memo->user_id = $last_insert_id;
        if (isset($request->memo)) {
            $user_memo->memo = $request->memo;
        } else {
            $user->memo = '記入なし';
        }
        $user_memo->save();
        return redirect('/admin/attends');
    }

    // ユーザー更新
    function update_user(Request $request)
    {
        /*
         *  保留
         *
         $rules = [
            'name' => 'required',
            'email' => 'required',
            'department' => 'required|numeric',
            'employee_id' => 'required|numeric',
            'group_id' => 'required|numeric',
            'paid_holiday' => 'required|numeric',
        ];
        $messages = [
            'name.required' => '名前を入力してください。',
            'email.numeric' => 'メールアドレスを入力してください',
            'department.required' => '部署を選択してください',
            'employee_id.required' => '社員番号を入力してください',
            'group_id.required' => '権限を選択してください',
            'paid_holiday.required' => '有給残日数を記入してください',
            'department.numeric' => '部署を正しく選択してください',
            'employee_id.numeric' => '社員番号を数字で入力してください',
            'group_id.numeric' => '権限を数字で入力してください',
            'paid_holiday.numeric' => '有給残日数を数字で入力してください',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('/admin/attends/admin_edit')->with('error', '必須項目が記入されていません。');
            //return response()->json(["error" => true, "code" => 1, "message" => "必須項目が記入されていません", "errors" => $validator->errors()]);
        }
         */

        $department = Department::find($request->department ?? 0);
        if ($department == null || $department->deleted_at != null) {
            return redirect('/admin/attends/admin_edit')->with('error', '指定された部署が存在しません。');
        }

        $user = User::find($request->id);
        if ($user == null) {
            return redirect('/admin/attends/admin_edit')->with('error', '指定されたユーザーが存在しません。');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->department = $request->department;
        $user->employee_id = $request->employee_id;
        $user->group_id = $request->group_id;
        $user->joined_date = $request->joined_date;
        $user->left_date = $request->left_date;

        $user->save();

        $user_memo = UserMemo::where('user_id', '=', $request->id)->first();
        if ($user_memo != null) {
            if (isset($request->memo) && $request->memo != null) {
                $user_memo->memo = $request->memo;
            } else {
                $user_memo->memo = '記入なし';
            }
            $user_memo->save();
        } else {
            UserMemo::create(['user_id' => $request->id, 'memo' => $request->memo]);
        }
        return redirect('/admin/attends');
    }

    function message(Request $request): Factory|View|Application
    {
        $users = User::where('left_date', '=', null)->get();
        return view('admin.user.notification', compact('users'));
    }

    function createMessage(Request $request): Redirector|Application|RedirectResponse
    {
        $user = User::find($request->user_id);
        if ($user == null) {
            redirect('/admin/attends/notify')->with('error', '指定された社員が見つかりません。');
        }
        $rules = [
            'user_id' => 'required|numeric',
            'title' => 'required',
            'data' => 'required',
        ];
        $messages = [
            'user_id.required' => '社員を選択してください。',
            'user_id.numeric' => '社員を正しく選択してください',
            'title.required' => 'タイトルを記入してください',
            'data.required' => 'メッセージを記入してください',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('/admin/attends/notify')->with('error', '必須項目が記入されていません。');
            //return response()->json(["error" => true, "code" => 1, "message" => "必須項目が記入されていません", "errors" => $validator->errors()]);
        }
        Notification::publish(['user_id' => $user->id, 'title' => $request->title, 'data' => $request->data, 'url' => $request->url ?? '/account/notifications/%%THIS%%', 'status' => 0]);

        return redirect('/admin/attends')->with('result', 'メッセージを送信しました。');
    }

    function viewHolidaysGet(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::find($user_id);
        if ($user == null) {
            return redirect('/admin/attends')->with('error', '対象の社員は存在しません。');
        }
        $parameters = [];
        $parameters['user_id'] = $user_id;
        $days = PaidHoliday::getHolidays($user_id);
        $searchStr = "<strong>社員: </strong>{$user->name} <span class='text-muted'>/</span> <strong>残日数: </strong>{$days}日";
        $data = PaidHoliday::where('user_id', $user_id)->orderByDesc('created_at')->paginate(20);
        return view('admin.user.paid_holidays.index', compact('user_id', 'parameters', 'data', 'searchStr'));
    }

    function editHolidayGet(Request $request)
    {
        $user_id = $request->user_id;
        $id = $request->id;
        $data = PaidHoliday::find($id);
        if ($data == null) {
            return redirect('/admin/attends/holidays/' . $user_id)->with('error', '指定された有給は存在しません。');
        }
        $parameters = [];
        $parameters['user_id'] = $user_id;
        return view('admin.user.paid_holidays.edit', compact('user_id', 'parameters', 'data', 'id'));
    }

    function editHolidayPost(Request $request)
    {
        $rules = [
            'amount' => 'required|numeric',
            'created' => 'required',
        ];
        $messages = [
            'amount.required' => '日を記入してください',
            'amount.numeric' => '日を数値で記入してください',
            'created.required' => '付与日を選択してください',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(["error" => true, "code" => 1, "message" => "必須項目が記入されていません", "errors" => $validator->errors()]);
        }
        $id = $request->id;
        $data = PaidHoliday::find($id);
        if ($data == null || $data->deleted_at != null) {
            return response()->json(["error" => true, "code" => 20, "message" => "指定された有給データが見つかりません。"]);
        }
        try {
            $amount = $request->amount;
            $created = $request->created;
            $param = [
                'amount' => $amount,
                'created_at' => $created,
            ];
            $data->update($param);
            return response()->json(["error" => false, "code" => 0, "message" => "有給を更新しました。"]);
        } catch (\Exception $e) {
            return response()->json(["error" => true, "code" => 21, "message" => "データの処理中に問題が発生しました。\n" . $e->getMessage() . "\n" . $e->getTraceAsString() . ""]);
        }
    }

    function createHolidayGet(Request $request)
    {
        $user_id = $request->user_id;
        $parameters = [];
        $parameters['user_id'] = $user_id;
        return view('admin.user.paid_holidays.new', compact('user_id', 'parameters'));
    }

    function createHolidayPost(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::find($user_id);
        if ($user == null) {
            return response()->json(["error" => true, "code" => 27, "message" => "ユーザーが見つかりません。"]);
        }
        $rules = [
            'amount' => 'required|numeric',
            'created' => 'required',
        ];
        $messages = [
            'amount.required' => '日を記入してください',
            'amount.numeric' => '日を数値で記入してください',
            'created.required' => '付与日を選択してください',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(["error" => true, "code" => 1, "message" => "必須項目が記入されていません", "errors" => $validator->errors()]);
        }
        try {
            $amount = $request->amount;
            $created = $request->created;
            $id = PaidHoliday::createHoliday($user_id, $amount, $created)->id;
            return response()->json(["error" => false, "code" => 0, "message" => "有給を追加しました。", "id" => $id]);
        } catch (\Exception $e) {
            return response()->json(["error" => true, "code" => 21, "message" => "データの処理中に問題が発生しました。\n" . $e->getMessage() . "\n" . $e->getTraceAsString() . ""]);
        }
    }

    function deleteHoliday(Request $request)
    {
        $user_id = $request->user_id;
        if (empty($request->id)) {
            return redirect("/admin/attends/holidays/'.$user_id")->with('error', '指定された休日が見つかりません。(E20)');
        }
        $id = $request->id;
        $data = PaidHoliday::find($id);
        if ($data == null || $data->deleted_at != null) {
            return redirect("/admin/attends/holidays/'.$user_id")->with('error', '指定された休日が見つかりません。(E21)');
        }
        try {
            $data->update(['deleted_at' => new DateTime()]);
            return redirect("/admin/attends/holidays/'.$user_id")->with('result', '休日を削除しました。');
        } catch (\Exception $e) {
            return redirect("/admin/attends/holidays/'.$user_id")->with('error', '休日の削除に失敗しました。(E30)');
        }
    }

}
