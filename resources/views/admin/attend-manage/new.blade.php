@extends('layouts.admin')

@section('styles')
    <style>
        .attends-row {
            transition-duration: 0.2s;
            cursor: pointer;
        }

        .attends-row:hover {
            transition-duration: 0.05s;
            box-shadow: 0 0 10px #999;
            background-color: #0b5ed7;
            color: #fff;
        }
    </style>
@endsection
@section('pageTitle', "勤怠情報管理")

@section('content')
    <div class="container">
        <form>
            <div class="row">
                <div class="col-md-6">
                    <h2 class="fw-bold">勤怠情報追加</h2>
                </div>
                <div class="col-md-6">
                    <button type="button" onclick="saveAttendData()" class="btn btn-primary" style="float: right"
                            id="saveBtn">
                        保存
                    </button>
                    <a href="/admin/attend-manage" class="btn btn-secondary" style="float: right; margin-right: 10px;">キャンセル</a>
                </div>
                <div class="col-md-12 mt-3" id="alert">
                </div>
                @if (session('error'))
                    <div class="col-md-12 mt-3">
                        <div class="alert alert-danger" role="alert">
                            <strong>エラー</strong> {{ session('error') }}
                        </div>
                    </div>
                @endif
                @if (session('result'))
                    <div class="col-md-12 mt-3">
                        <div class="alert alert-success" role="alert">
                            {{ session('result') }}
                        </div>
                    </div>
                @endif
            </div>
            <hr>
            <div class="row">
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="dateInput" class="form-label">日付</label>
                    <input type="date" class="form-control" id="dateInput" placeholder="XXXX-XX-XX"
                           value="{{old("dateInput")}}"
                    >
                </div>
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="dateInput" class="form-label">社員</label>
                    <select class="form-select" aria-label="" id="user">
                        @foreach($users as $user)
                            <?php
                            $selected = "";
                            if ($user->id == intval(old("user"))) {
                                $selected = "selected";
                            }
                            ?>
                            <option value="{{$user->id}}" {{$selected}}>{{sprintf("%03d", $user->employee_id)}}
                                / {{$user->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="status" class="form-label">状態</label>
                    <select class="form-select" aria-label="" id="status">
                        <option value="0" <?php echo old("status") == 0 ? "selected" : "";?>>出勤中</option>
                        <option value="1" <?php echo old("status") == 1 ? "selected" : "";?>>退勤済み</option>
                    </select>
                </div>
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="startTime" class="form-label">出勤時刻</label>
                    <input type="time" class="form-control" id="startTime" placeholder="--:--" value="{{old("time")}}">
                </div>
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="endTime" class="form-label">退勤時刻</label>
                    <input type="time" class="form-control" id="endTime" placeholder="--:--" value="{{old("start")}}">
                </div>
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="workTime" class="form-label">勤務時間</label>
                    <input type="time" class="form-control" id="workTime" placeholder="--:--" value="{{old("end")}}">
                </div>
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="restTime" class="form-label">休憩時間</label>
                    <input type="time" class="form-control" id="restTime" placeholder="--:--" value="{{old("rest")}}">
                </div>
                <div class="mb-3 col-md-12">
                    <label for="comment" class="form-label">勤務詳細</label>
                    <textarea class="form-control" id="comment"
                              placeholder="勤務詳細が記入されていません">{{old("comment")}}</textarea>
                </div>
            </div>
        </form>
    </div>

    <script>
        let startTime = '{{$data->created_at ?? ""}}'
        const startDate = new Date(startTime)
        const currentDate = new Date()
        let diff = new Date(currentDate.getTime() - startDate.getTime() + 54000000)

        function saveAttendData() {
            const _sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

            let saveBtn = document.getElementById("saveBtn")
            let textArea = document.getElementById("textArea")

            let dateInput = document.getElementById("dateInput")
            let user = document.getElementById("user")
            let status = document.getElementById("status")
            let startTime = document.getElementById("startTime")
            let endTime = document.getElementById("endTime")
            let workTime = document.getElementById("workTime")
            let restTime = document.getElementById("restTime")
            let comment = document.getElementById("comment")
            let alert = document.getElementById("alert")

            saveBtn.setAttribute("disabled", "")
            saveBtn.innerText = "保存しています"

            axios
                .post("/admin/attend-manage/new", {
                    date: dateInput.value,
                    user: user.value,
                    status: status.value,
                    start: startTime.value,
                    end: endTime.value,
                    work: workTime.value,
                    rest: restTime.value,
                    comment: comment.value,
                })
                .then(async (res) => {
                    const resultCode = res.data.code
                    console.log("Result: " + resultCode + " / " + res.data.message)
                    if (resultCode === 0) {
                        saveBtn.className = "btn btn-success"
                        saveBtn.innerText = "保存しました"
                        await _sleep(1000)
                        location = "/admin/attend-manage/view/" + res.data.id
                        return
                    } else {
                        let alertStr = '<div class="alert alert-danger" role="alert">' +
                            '<strong>エラー</strong> - ' +
                            res.data.message + '<br>'
                        if (resultCode === 1) {
                            Object.keys(res.data.errors).forEach(key => {
                                alertStr += res.data.errors[key] + '<br>'
                            });
                        }
                        alertStr += '</div>';
                        alert.innerHTML = alertStr
                        saveBtn.className = "btn btn-danger"
                        saveBtn.innerText = "保存失敗"
                        await _sleep(2000)
                    }
                    saveBtn.removeAttribute("disabled")
                    saveBtn.className = "btn btn-primary"
                    saveBtn.innerText = "保存"
                })
        }
    </script>
@endsection
