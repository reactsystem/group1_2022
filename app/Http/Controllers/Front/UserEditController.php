<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use function bcrypt;
use function redirect;
use function view;

class UserEditController extends Controller
{
    // ユーザー管理
    function account(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        $user = User::where('users.id', Auth::id())->leftJoin("departments", "users.department", "departments.id")->select("users.*", "departments.name as dname")->first();
        return view('front/account/account', ['user' => $user,]);
    }

    // ユーザー情報編集へ
    function account_edit(Request $request){
        if(!Auth::check()){
            return redirect('/login');
        }
        $user = User::where('users.id', Auth::id())->leftJoin("departments", "users.department", "departments.id")->select("users.*", "departments.name as dname")->first();
        return view('front/account/account_edit',['user' => $user,]);
    }

    // ユーザー情報変更
    function account_edit_done(Request $request){
        if(!Auth::check()){
            return redirect('/login');
        }
        $auth_user = Auth::user();
        $user = User::find ($auth_user['id']);
        $user -> name = $request ->InputName;
        $user -> email = $request ->InputEmail;

        $user -> save();
        return view('front/account/account',['user' => $user,]);
    }

    // ユーザーパスワード変更へ
    function password_update(Request $request){
        if(!Auth::check()){
            return redirect('/login');
        }
        $user = Auth::user();
        return view('front/account/password_update',['user' => $user,]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data,[
            'new_password' => 'required|string|min:8|confirmed',
        ]);
    }

    //
    function password_update_done(Request $request){
        if(!Auth::check()){
            return redirect('/login');
        }
        $auth_user = Auth::user();
        $user = User::find ($auth_user['id']);

        if(!password_verify($request->current_password,$user->password))
        {
            return redirect('/account/password_update')
                ->with('warning','パスワードが違います');
        }

        //新規パスワードの確認
        $this->validator($request->all())->validate();

        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect('/account/password_update')
            ->with('status', 'パスワードの変更が終了しました');
    }

    function notifications(Request $request)
    {
        $data = Notification::paginate(20); // deleted_atに変更する
        return view('front.account.notifications.index', compact('data'));
    }

    function viewNotification(Request $request)
    {
        $id = $request->id;
        $data = Notification::find($id);
        if ($data == null) {
            return redirect("/account/notifications")->with('error', '指定された通知が見つかりません。(E20)');
        }
        return view('front.account.notifications.edit', compact('data', 'id'));
    }

    function deleteNotification(Request $request)
    {
        if (empty($request->id)) {
            return redirect("/account/notifications")->with('error', '指定された通知が見つかりません。(E22)');
        }
        $id = $request->id;
        $data = Notification::find($id);
        if ($data == null) {
            return redirect("/account/notifications")->with('error', '指定された通知が見つかりません。(E21)');
        }
        try {
            $data->delete();
            return redirect("/account/notifications")->with('result', '通知を削除しました。');
        } catch (\Exception $e) {
            return redirect("/account/notifications")->with('error', '通知の削除に失敗しました。(E30)');
        }
    }
}
