<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\VariousRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRequestController extends Controller
{
    function request(){
        $all_requests = VariousRequest::where('related_id','=',NULL)->get();
        return view('admin/request/admin_request',['all_requests' => $all_requests,]); 
    }

    function detail(Request $request){
        $this_request = VariousRequest::find($request ->id);
        return view('admin/request/admin_detail',['this_request' => $this_request,]); 
    }
}
