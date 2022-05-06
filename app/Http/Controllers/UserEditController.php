<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserEditController extends Controller
{
    // ユーザー管理
    function account(Request $request){
        if(!Auth::check()){
            return redirect('/login');
        }
        $user = Auth::user();
        return view('front/account/account',['user' => $user,]);
    }

    // ユーザー情報編集
    function account_edit(Request $request){
        if(!Auth::check()){
            return redirect('/login');
        }
        $user = Auth::user();
        return view('front/account/account_edit',['user' => $user,]);
    }

    // ユーザーパスワード変更
    function password_update(Request $request){
        if(!Auth::check()){
            return redirect('/login');
        }
        $user = Auth::user();
        return view('front/account/password_update',['user' => $user,]);
    }
}
