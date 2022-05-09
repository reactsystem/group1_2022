<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function redirect;
use function view;

class AdminTopPageController extends Controller
{

    function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        return view('admin.index');
    }

}
