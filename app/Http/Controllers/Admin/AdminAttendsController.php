<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Department;


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
}
