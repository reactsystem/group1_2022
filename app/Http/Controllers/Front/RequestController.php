<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\VariousRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function view;

class RequestController extends Controller
{

    function index(Request $request){
        $results = VariousRequest::leftJoin('request_types', 'various_requests.type', '=', 'request_types.id')->select("various_requests.*", "request_types.name as name")->where('user_id', Auth::id())->paginate(10);
        return view('front.request.index', compact('results'));
    }

    function show(Request $request){
        $result = VariousRequest::leftJoin('request_types', 'various_requests.type', '=', 'request_types.id')->select("various_requests.*", "request_types.name as name")->where('user_id', Auth::id())->find($request->id);
        if($result == null || $result->status == null){
            return redirect("/request");
        }
        return view('front.request.show', compact('result'));
    }

}
