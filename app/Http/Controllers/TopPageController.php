<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopPageController
{

    function index(Request $request){
        if(!Auth::check()){
            return redirect('/login');
        }
        return view('index');
    }

}