<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AttendanceManagementController extends Controller
{

    public function index(Request $request): Factory|View|Application
    {
        return view('front.attend-manage.index', compact('request'));
    }

}
