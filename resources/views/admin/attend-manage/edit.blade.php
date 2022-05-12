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
                    <h2 class="fw-bold">勤怠情報編集</h2>
                </div>
                <div class="col-md-6">
                    <button type="button" onclick="saveAttendData()" class="btn btn-primary" style="float: right"
                            id="saveBtn">
                        保存
                    </button>
                    <a href="/admin/attend-manage/view/{{$id}}" class="btn btn-secondary"
                       style="float: right; margin-right: 10px;">キャンセル</a>
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
                    <input type="text" class="form-control" id="dateInput" placeholder="XXXX-XX-XX"
                           value="{{$data->date}}"
                    >
                </div>
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="dateInput" class="form-label">社員名</label>
                    <input type="text" class="form-control" placeholder="---"
                           value="{{sprintf("%03d", $data->uid)}} / {{$data->name}}" disabled>
                </div>
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="status" class="form-label">状態</label>
                    <select class="form-select" aria-label="" id="status">
                        <option value="0" <?php if($data->mode == 0){?>selected<?php }?>>出勤中</option>
                        <option value="1" <?php if($data->mode == 1){?>selected<?php }?>>退勤済み</option>
                    </select>
                </div>
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="startTime" class="form-label">出勤時刻</label>
                    <input type="time" class="form-control" id="startTime" placeholder="--:--"
                           value="{{$data->created_at->format("H:i")}}">
                </div>
                @if($data->mode == 1)
                    <div class="mb-3 col-md-12 col-lg-6">
                        <label for="endTime" class="form-label">退勤時刻</label>
                        <input type="time" class="form-control" id="endTime" placeholder="--:--" value="<?php
                        if ($data->left_at != null) {
                            $dateTime = new DateTime($data->left_at);
                            echo $dateTime->format("H:i");
                        } else {
                            echo "--:--";
                        }
                        ?>">
                    </div>
                @else
                    <div class="mb-3 col-md-12 col-lg-6">
                        <label for="endTime" class="form-label">退勤時刻</label>
                        <input type="time" class="form-control" id="endTime" placeholder="--:--" value="">
                    </div>
                @endif
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="workTime" class="form-label">勤務時間</label>
                    <?php
                    $workTime = "--:--";
                    if ($data->time != null) {
                        $timeData = preg_split("/:/", $data->time);
                        $restData = preg_split("/:/", $data->rest ?? "00:00");
                        $wHours = intval($timeData[0]);
                        $wMinutes = intval($timeData[1]);
                        $rHours = intval($restData[0]);
                        $rMinutes = intval($restData[1]);
                        $xHours = max(0, $wHours - $rHours);
                        $xMinutes = $wMinutes - $rMinutes;
                        if ($xMinutes < 0 && $xHours != 0) {
                            $xMinutes = 60 - abs($xMinutes);
                            $xHours -= 1;
                        } else if ($xMinutes < 0) {
                            $xMinutes = 0;
                        }
                        $workTime = sprintf("%02d", $xHours) . ":" . sprintf("%02d", $xMinutes);
                    }
                    ?>
                    <input type="time" class="form-control" id="workTime" placeholder="--:--" value="{{$workTime}}"
                           disabled>
                </div>
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="restTime" class="form-label">休憩時間</label>
                    <?php
                    $workTime = "--:--";
                    if ($data->rest != null) {
                        $timeData = preg_split("/:/", $data->rest);
                        $workTime = sprintf("%02d", intval($timeData[0])) . ":" . sprintf("%02d", intval($timeData[1]));
                    }
                    ?>
                    <input type="time" class="form-control" id="restTime" placeholder="--:--" value="{{$workTime}}">
                </div>
                <div class="mb-3 col-md-12">
                    <label for="comment" class="form-label">勤務詳細</label>
                    <textarea class="form-control" id="comment"
                              placeholder="勤務詳細が記入されていません">{{$data->comment ?? ""}}</textarea>
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
            let status = document.getElementById("status")
            let startTime = document.getElementById("startTime")
            let endTime = document.getElementById("endTime")
            let workTime = document.getElementById("workTime")
            let restTime = document.getElementById("restTime")

            saveBtn.setAttribute("disabled", "")
            saveBtn.innerText = "保存しています"

            axios
                .post("/admin/attend-manage/edit/{{$id}}", {
                    date: dateInput.value,
                    status: status.value,
                    start: startTime.value,
                    end: endTime.value,
                    rest: restTime.value,
                })
                .then(async (res) => {
                    const resultCode = res.data.code
                    console.log("Result: " + resultCode + " / " + res.data.message)
                    if (resultCode == 0) {
                        saveBtn.className = "btn btn-success"
                        saveBtn.innerText = "保存しました"
                        await _sleep(1500)
                    } else {
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
