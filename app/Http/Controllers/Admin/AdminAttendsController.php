<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Models\UserMemo;
use Illuminate\Http\Request;


class AdminAttendsController extends Controller
{
    // メイン画面
    function admin_attends(Request $request)
    {
        $sort = $request->sort;
        if (empty($sort)) $sort = 'id';
        $users = User::all();
        // 最終出勤日


        $users->join('attendance', 'id', '=', 'user_id');
        $param =[
            'users' => $users,
            'sort' => $sort,
        ];

        return view('admin/user/admin_attends',$param);
    }

    // ユーザー新規登録
    function admin_new(){
        $departments = Department::all();
        return view('admin/user/admin_new',['departments' => $departments]);
    }

    // ユーザー情報個別確認
    function admin_view(Request $request){
        if(empty($request -> id)){
            return redirect('/admin/attends');
        }

        $user = User::find($request -> id);
        $departments = Department::all();
        if(empty($user_memo))$user_memo = '';
        return view('admin/user/admin_view',['user' => $user,'departments' => $departments,'user_memo' =>$user_memo]);
    }

    // ユーザー情報個別編集
    function admin_edit(Request $request){
        if(empty($request -> id)){
            return redirect('/admin/attends');
        }

        $user = User::find($request -> id);
        $departments = Department::all();
        if(empty($user_memo))$user_memo = '';
        return view('admin/user/admin_edit',['user' => $user,'departments' => $departments,'user_memo' =>$user_memo]);
    }

    // ユーザー追加
    function add_new_user(Request $request){

        $user = new User();
        $user->name=$request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->department = $request->department;
        $user->employee_id=$request->employee_id;
        $user->group_id=$request->group_id;
        $user->joined_date=$request->joined_date;
        $user->paid_holiday=$request->paid_holiday;

        $user->save();
        $last_insert_id = $user->id;

        $user_memo = new UserMemo();
        $user_memo->user_id = $last_insert_id;
        $user_memo -> memo = $request -> memo;
        $user_memo -> save();
        return redirect('/admin/attends');
    }

    // ユーザー更新
    function update_user(Request $request){
        $user =User::find($request -> id);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->department=$request->department;
        $user->employee_id=$request->employee_id;
        $user->group_id=$request->group_id;
        $user->joined_date=$request->joined_date;
        $user->left_date=$request->left_date;
        $user->paid_holiday=$request->paid_holiday;

        $user->save();

        $user_memo = UserMemo::where('user_id', '=', $request->id)->first();
        $user_memo->memo = $request->memo;
        $user_memo -> save();
        return redirect('/admin/attends');
    }

}
