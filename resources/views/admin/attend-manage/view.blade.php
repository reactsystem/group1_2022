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
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">勤怠情報確認</h2>
            </div>
            <div class="col-md-6">
                <a href="/admin/attend-manage/edit/{{$id}}" class="btn btn-primary" style="float: right">
                    編集
                </a>
                <button class="btn btn-danger"
                        style="float: right; margin-right: 10px;" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    削除
                </button>
                <a href='/admin/attend-manage' class="btn btn-secondary"
                   style="float: right; margin-right: 10px;">戻る</a>
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
                <label for="dateInput" class="form-label">勤務時間</label>
                <?php
                $workTime = "--:--";
                if ($data->time != null) {
                    $timeData = preg_split("/:/", $data->time);
                    $workTime = sprintf("%02d", intval($timeData[0])) . ":" . sprintf("%02d", intval($timeData[1]));
                }
                ?>
                <input type="time" class="form-control" id="dateInput" placeholder="--:--" value="{{$workTime}}"
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
