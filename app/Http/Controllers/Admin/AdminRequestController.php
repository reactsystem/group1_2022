<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\PaidHoliday;
use App\Models\RequestType;
use App\Models\User;
use App\Models\VariousRequest;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminRequestController extends Controller
{
    // メイン
    function request(Request $request)
    {
        if ($request->mode == 'all') {
            $all_requests = VariousRequest::whereNull('related_id')->orderByDesc('id')->paginate(15);
        } else {
            $all_requests = VariousRequest::whereNull('related_id')->where('status', '=', 0)->orderByDesc('id')->paginate(10);
        }
        $all_user = User::all();
        $parameters = [];
        if (isset($request->mode)) {
            $parameters['mode'] = $request->mode;
        }
        if (isset($request->status)) {
            $parameters['status'] = $request->status;
        }
        if (isset($request->id)) {
            $parameters['id'] = $request->id;
        }
        if (isset($request->dateInput)) {
            $parameters['dateInput'] = $request->dateInput;
        }
        return view('/admin/request/admin_request', ['users' => $all_user, 'all_requests' => $all_requests, 'parameters' => $parameters, 'request' => $request]);
    }

    // 検索
    function search(Request $request){
        $all_requests = new VariousRequest;
        $all_user= User::all();

        // 条件：状態
        if(isset($request->status)){
            $all_requests = $all_requests -> where('status','=',$request->status);
        }

        // 条件：名前
        if (isset($request->id)) {
            $all_requests = $all_requests->where('user_id', '=', $request->id);
        }

        // 条件：日付
        if (isset($request->dateInput)) {
            $requests = $all_requests->where('date', '=', $request->dateInput)->paginate(15);
        } else {
            $requests = $all_requests->whereNull('related_id')->paginate(15);
        }
        $parameters = [];
        if (isset($request->mode)) {
            $parameters['mode'] = $request->mode;
        }
        if (isset($request->status)) {
            $parameters['status'] = $request->status;
        }
        if (isset($request->id)) {
            $parameters['id'] = $request->id;
        }
        if (isset($request->dateInput)) {
            $parameters['dateInput'] = $request->dateInput;
        }

        return view('/admin/request/admin_request', ['users' => $all_user, 'all_requests' => $requests, 'parameters' => $parameters, 'request' => $request]);
    }

    // 詳細画面
    function detail(Request $request){
        $this_request = VariousRequest::find($request ->id);
        $uuid = $this_request['uuid'];
        $related_request = VariousRequest::where('related_id','=',$uuid)->get();

        return view('admin/request/admin_detail', ['this_request' => $this_request, 'related_request' => $related_request]);
    }

    // 申請承認
    function approve(Request $request){
        // 有給休暇を戻すかどうか
        $this_request = VariousRequest::find($request ->id);
        $user = User::find($this_request ->user_id);
        $request_days = $this_request -> related_request() -> count() +1;

        // 前の状態が承認済みだった場合
        if ($this_request->status == 2) {
            $holidayData = PaidHoliday::useHolidays($this_request->user_id, $request_days);
            if (!$holidayData[0]) {
                return redirect('/admin/request')->with('error', "有給消費が残日数を超えています");
            }
            //$user -> paid_holiday = $user->paid_holiday - $request_days;
            $user->save();
            VariousRequest::find($request->id)->update(['holidays_key' => $holidayData[1], 'status' => 1]);
        } else {
            VariousRequest::find($request->id)->update(['status' => 1]);
        }
        VariousRequest::where('related_id','=',$request ->uuid)->update(['status' => 1]);
        $message = '申請を承認しました。';
        Notification::create(['user_id' => $user->id, 'badge_color' => '00DD00', 'title' => '申請が承認されました', 'data' => '申請が承認されました。ここをクリックして詳細を確認できます。', 'url' => '/request/' . $request->id, 'status' => 0]);

        return redirect('/admin/request')->with('flash_message', $message);
    }

    // 申請却下
    function reject(Request $request)
    {
        $requestData = VariousRequest::find($request->id);
        VariousRequest::where('related_id', '=', $request->uuid)->update(['status' => 2]);
        $this_request = VariousRequest::find($request->id);
        $request_days = $this_request->related_request()->count() + 1;
        $user = User::find($this_request->user_id);

        // 有給休暇を戻す

        if ($requestData->holidays_key != null) {
            PaidHoliday::revertHolidays($this_request->user_id, $requestData->holidays_key);
        }
        $user = Auth::user();
        //$user->paid_holiday = PaidHoliday::getHolidays($this_request->user_id);
        //$user->save();
        //$user->paid_holiday = $user->paid_holiday + $request_days;
        $user->save();
        $requestData->update(['status' => 2]);

        Notification::create(['user_id' => $user->id, 'badge_color' => 'DD0000', 'title' => '申請が却下されました', 'data' => '申請が却下されました。ここをクリックして詳細を確認できます。', 'url' => '/request/' . $request->id, 'status' => 0]);

        return redirect('/admin/request')->with('error', '申請を却下しました。');
    }

    // 申請新規追加
    function create(Request $request){
        $all_user = User::all();
        $types = RequestType::where('deleted_at', null)->get();
        return view('admin/request/admin_create', ['types' => $types, 'users' => $all_user]);
    }

    // 申請新規追加
    function check(Request $request){
        {
            $user = user::find($request->user_id);
            if ($user == null) {
                return redirect("/admin/request/create")->with('error', '指定されたユーザーが見つかりません');
            }
            $tempDate = preg_split("/,/", $request->dates);
            $dates = [];
            foreach ($tempDate as $item) {
                $temp = new DateTime($item);
                $dates[] = $temp->format('Y-n-j');
            }
            if (count($dates) == 0) {
                return redirect("/admin/request/create")->with('error', '日付が指定されていません');
            }
            $type = RequestType::find($request->type);
            if ($type == null || $type->deleted_at != null) {
                return redirect("/admin/request/create")->with('error', '種別が指定されていません');
            }
            $holidays = 0;
            $time = NULL;
            if (isset($request->time) && $type->type == 1) {
                $time = $request->time;
                if ($time == null || $time == "00:00") {
                    return redirect("/admin/request/create")->with('error', '労働時間が指定されていません');
                }
            }
            if ($request->type == 2) {
                $holidays = count($dates);
            }
            if ($holidays > PaidHoliday::getHolidays($request->user_id)) {
                return redirect("/admin/request/create")->with('error', '有給消費が残日数を超えています');
            }
            $reason = $request->reason;
            $uuid = Str::uuid();
            foreach ($tempDate as $index => $item) {
                if ($index == 0) {
                    $holidaysKey = [true, null];
                    if ($holidays > 0) {
                        $holidaysKey = PaidHoliday::useHolidays($request->user_id, $holidays);
                        if (!$holidaysKey[0]) {
                            return redirect("/request")->with('error', '有給消費が残日数を超えています');
                        }
                        //$user->paid_holiday = PaidHoliday::getHolidays($request->user_id);
                        $user->save();
                    }
                    VariousRequest::create([
                        'uuid' => $uuid,
                        'user_id' => $request->user_id,
                        'type' => $type->id,
                        'date' => $item,
                        'status' => 0,
                        'holidays_key' => $holidaysKey[1],
                        'time' => $request->time,
                        'reason' => $request->reason ?? "",
                    ]);
                } else {
                    VariousRequest::create([
                        'uuid' => Str::uuid(),
                        'user_id' => $request->user_id,
                        'type' => $type->id,
                        'date' => $item,
                        'status' => 0,
                        'time' => $request->time,
                        'reason' => $request->reason ?? "",
                        'related_id' => $uuid,
                    ]);
                }
            }
//            if ($holidays > 0) {
//                $user->paid_holiday = $user->paid_holiday - $holidays;
//                $user->save();
//            }
            return redirect("/admin/request")->with('result', '申請を行いました');
            //return view('front.request.check', compact('dates', 'type', 'holidays', 'reason', 'time'));
        }
    }
}
