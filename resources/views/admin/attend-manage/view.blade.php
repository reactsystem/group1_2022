@extends('layouts.admin')

@section('pageTitle', "勤怠情報管理")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">勤怠情報確認</h2>
            </div>
            <div class="col-md-6">
                <a href="/admin/attend-manage/edit/{{$id}}" class="btn btn-primary float-right">
                    編集
                </a>
                <button class="btn btn-danger float-right mr-10px" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    削除
                </button>
                <a href='/admin/attend-manage' class="btn btn-secondary float-right mr-10px">戻る</a>
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
                <input type="text" class="form-control" id="dateInput" placeholder="XXXX-XX-XX" value="{{$data->date}}"
                       disabled>
            </div>
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="dateInput" class="form-label">社員名</label>
                <input type="text" class="form-control" id="dateInput" placeholder="---"
                       value="{{sprintf("%03d", $data->uid)}} / {{$data->name}}" disabled>
            </div>
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="dateInput" class="form-label">状態</label>
                <input type="text" class="form-control" id="dateInput" placeholder="---"
                       value="{{$data->mode == 1 ? "退勤済" : "出勤中"}}" disabled>
            </div>
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="dateInput" class="form-label">出勤時刻</label>
                <input type="time" class="form-control" id="dateInput" placeholder="--:--"
                       value="{{$data->created_at->format("H:i")}}" disabled>
            </div>
            @if($data->mode == 1)
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="dateInput" class="form-label">退勤時刻</label>
                    <input type="time" class="form-control" id="dateInput" placeholder="--:--" value="<?php
                    /* @var $data */
                    if ($data->left_at != null) {
                        $dateTime = new DateTime($data->left_at);
                        echo $dateTime->format("H:i");
                    } else {
                        echo "--:--";
                    }
                    ?>" disabled>
                </div>
            @else
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="dateInput" class="form-label">退勤時刻</label>
                    <input type="time" class="form-control" id="dateInput" placeholder="--:--" value="" disabled>
                </div>
            @endif
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="workInput" class="form-label">勤務時間</label>
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
                <input type="time" class="form-control" placeholder="--:--" value="{{$workTime}}"
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
                <input type="time" class="form-control" id="restTime" placeholder="--:--" value="{{$workTime}}"
                       disabled>
            </div>
            <div class="mb-3 col-md-12">
                <label for="dateInput" class="form-label">勤務詳細</label>
                <textarea class="form-control" id="dateInput" placeholder="勤務詳細が記入されていません"
                          disabled>{{$data->comment ?? ""}}</textarea>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">削除確認</h5>
                </div>
                <div class="modal-body">
                    勤怠情報を削除してもよろしいですか?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <a href="/admin/attend-manage/delete/{{$id}}" type="button" class="btn btn-danger">削除</a>
                </div>
            </div>
        </div>
    </div>
@endsection
