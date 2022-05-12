<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\VariousRequest;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\TextUI\XmlConfiguration\Variable;

class AdminRequestController extends Controller
{
    function request(){
        $all_requests = VariousRequest::whereNull('related_id')->get();
        $all_user= User::all();
        return view('/admin/request/admin_request',['users' => $all_user,'all_requests' => $all_requests,]); 
    }

    function search(Request $request){
        $all_requests = new VariousRequest;
        $all_user= User::all();
        
        if(isset($request->status)){
            $all_requests -> where('status','=',$request->status);
        }

        if(isset($request->id)){
            $all_requests -> where('user_id','=',$request->id);

        }

        if(isset($request->dateInput)){
            $requests = $all_requests -> where('date','=',$request->dateInput) ->get();
            echo 'a';

        }else{
            $requests = $all_requests -> whereNull('related_id')->get();
            echo 'b';
        }


        return view('/admin/request/admin_request',['users' => $all_user,'all_requests' => $requests,]);
    }

    function detail(Request $request){
        $this_request = VariousRequest::find($request ->id);
        $uuid = $this_request['uuid'];
        $related_request = VariousRequest::where('related_id','=',$uuid)->get();

        return view('admin/request/admin_detail',['this_request' => $this_request,'related_request'=>$related_request]); 
    }

    function approve(Request $request){
        // 有給休暇を戻すかどうか
        $this_request = VariousRequest::find($request ->id);
        $user = User::find($this_request ->user_id);
        $request_days = $this_request -> related_request() -> count() +1;

        // 前の状態が承認済みだった場合
        if($this_request -> status == 2){
            $user -> paid_holiday = $user->paid_holiday - $request_days;
            $user ->save();
        }
        
        VariousRequest::find($request ->id)->update(['status' => 1]);
        VariousRequest::where('related_id','=',$request ->uuid)->update(['status' => 1]);
        $message = '申請を承認しました。';

        return redirect('/admin/request')->with('flash_message', $message);
    }

    function reject(Request $request){
        VariousRequest::find($request ->id)->update(['status' => 2]);
        VariousRequest::where('related_id','=',$request ->uuid)->update(['status' => 2]);
        $this_request = VariousRequest::find($request ->id);
        $request_days = $this_request -> related_request() -> count() +1;
        $user = User::find($this_request ->user_id);

        // 有給休暇を戻す
        $user -> paid_holiday = $user->paid_holiday + $request_days;
        $user ->save();

        return redirect('/admin/request')->with('flash_message', '申請を却下しました。');
    }
}
