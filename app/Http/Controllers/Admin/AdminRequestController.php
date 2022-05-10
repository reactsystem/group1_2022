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

    function approve(Request $request){
        VariousRequest::find($request ->id)->update(['status' => 1]);
        VariousRequest::where('related_id','=',$request ->uuid)->update(['status' => 1]);
        return redirect();
    }

    function reject(Request $request){
        VariousRequest::find($request ->id)->update(['status' => 2]);
        VariousRequest::where('related_id','=',$request ->uuid)->update(['status' => 2]);
        
        // 残業戻す
        
        return redirect();
    }
}
