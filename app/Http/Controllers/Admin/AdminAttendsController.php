<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Department;
use App\Models\User_memo;


class AdminAttendsController extends Controller
{
    function admin_attends(Request $request){
        $sort = $request ->sort;
        if(empty($sort))$sort = 'id';
        $users = User::orderBy($sort,'asc')->paginate(15);
        $param =[
            'users' => $users,
            'sort' => $sort,
        ];
 
        return view('admin/user/admin_attends',$param);
    }

    function admin_new(){

        $departments = Department::all();
        return view('admin/user/admin_new',['departments' => $departments]);
    }
    
    function admin_edit(Request $request){
        $user = User::find($request -> id);
        $departments = Department::all();
        if(empty($user_memo))$user_memo = '';
        return view('admin/user/admin_edit',['user' => $user,'departments' => $departments,'user_memo' =>$user_memo]);
    }

}
