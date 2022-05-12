@extends('layouts.main')

@section('pageTitle', "ホーム")

@section('content')
    <div class="container">
        <h3 class="fw-bold">新着通知</h3>
        <hr>
        <div class="card">
            <div class="card-body">
                ● ここに通知が入ります
            </div>
        </div>
        @if($data == null)
            <hr>
            <div class="alert alert-primary mt-3" style="display: flex">
                <strong style="flex: 1; line-height: 40px; height: 40px;">
                    本日の出勤情報が入力されていません
                </strong>
                <a href="/attends" class="btn btn-primary">
                    出勤情報入力
                </a>
            </div>
        @endif
        <hr>
        <div style="display: flex; width: 100%; gap: 10px" class="mt-3">
            @if($data != null)
                <div style="flex: 1">
                    <span class="text-muted">本日の勤務時間(未確定)</span>
                    <div style="font-size: 40pt">{{$xHours}}:{{sprintf("%02d", $xMinutes)}}</div>
                </div>
                <div style="flex: 1">
                    <span class="text-muted">本日の出勤時刻</span>
                    <div style="font-size: 40pt">{{$data->created_at->format("G:i")}}</div>
                </div>
            @endif
            <div style="flex: 1">
                <span class="text-muted">今月の労働時間</span>
                <div style="font-size: 40pt">{{$hours}}:{{sprintf("%02d", $minutes)}}</div>
            </div>
            <div style="flex: 1">
                <span class="text-muted">今月の残業時間</span>
                <div style="font-size: 40pt">{{$hoursReq}}:{{sprintf("%02d", $minutesReq)}}</div>
            </div>
            @if($data == null)
                <div style="flex: 1">
                </div>
                <div style="flex: 1">
                </div>
            @endif
        </div>
    </div>
@endsection
