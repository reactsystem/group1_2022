@extends('layouts.main')

@section('pageTitle', "ホーム")

@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-8 col-sm-6 col-6">
                <h3 class="fw-bold">新着通知</h3>
            </div>
            <div class="col-md-4 col-sm-6 col-6">
                <a href="/account/notifications" class="btn btn-primary float-right">通知一覧</a>
            </div>
        </div>
        <hr>
        @if($notifications != null && count($notifications) != 0)
            @foreach($notifications as $notification)
                <div class="card card-hover mb-3 pointer-cursor"
                     onclick="href<?php echo(preg_match("/http[ |s]:\/\//", $notification->url) ? "Blank" : "");?>('/notification/{{$notification->id}}')">
                    <div class="card-body">
                        <span
                            style="color: {{"#".$notification->badge_color}}; text-shadow: #{{$notification->badge_color}} 0 0 10px">●</span><strong> {{$notification->title}}</strong><span
                            class="text-muted"> - </span>
                        {!! $notification->data !!}
                    </div>
                </div>
            @endforeach
            {{$notifications->links()}}
        @else
            <span class="text-muted text-center">
                通知はありません
            </span>
        @endif
        @if($data == null)
            <hr>
            <div class="alert alert-primary mt-3 flex-view">
                <strong class="dashboard-non-attends">
                    本日の出勤情報が入力されていません
                </strong>
                <a href="/attends" class="btn btn-primary">
                    出勤情報入力
                </a>
            </div>
        @endif
        <hr>
        <div class="mt-3 dashboard-data-basement">
            @if($data != null)
                <div class="flex-1">
                    <span class="text-muted">本日の勤務時間(未確定)</span>
                    <div class="font-40">{{$xHours}}:{{sprintf("%02d", $xMinutes)}}</div>
                </div>
                <div class="flex-1">
                    <span class="text-muted">本日の出勤時刻</span>
                    <div class="font-40">{{$data->created_at->format("G:i")}}</div>
                </div>
            @endif
            <div class="flex-1">
                <span class="text-muted">今月の労働時間</span>
                <div class="font-40">{{$hours}}:{{sprintf("%02d", $minutes)}}</div>
            </div>
            <div class="flex-1">
                <span class="text-muted">今月の残業時間</span>
                <div class="font-40">{{$hoursReq}}:{{sprintf("%02d", $minutesReq)}}</div>
            </div>
            @if($data == null)
                <div class="flex-1">
                </div>
                <div class="flex-1">
                </div>
            @endif
        </div>
    </div>
@endsection
