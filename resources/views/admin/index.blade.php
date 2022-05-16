@extends('layouts.admin')

@section('pageTitle', "管理者コントロールパネル")

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
    <style>
        .card-hover {
            box-shadow: 0 0 0;
            transition-duration: 0.1s;
        }

        .card-hover:hover {
            box-shadow: 0 0 10px #CCC;
            transition-duration: 0.2s;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <h2 class="fw-bold">仮設管理者トップページ</h2>
        <hr>
        <h4 class="fw-bold mt-4">新着通知</h4>
        <hr>
        @if($notifications != null && count($notifications) != 0)
            @foreach($notifications as $notification)
                <div class="card mb-3 card-hover" onclick="href('/notification/{{$notification->id}}')"
                     style="cursor: pointer">
                    <div class="card-body">
                        <span
                            style="color: #{{$notification->badge_color}}; text-shadow: #{{$notification->badge_color}} 0 0 10px">●</span><strong> {{$notification->title}}</strong><span
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
        <h4 class="fw-bold mt-5">対応待ちの申請 ({{$requests->total()}}個)</h4>
        <hr>
        @if($requests != null && count($requests) != 0)
            @foreach($requests as $request)
                <div class="card mb-3 card-hover" onclick="href('/notification/{{$request->id}}')"
                     style="cursor: pointer">
                    <div class="card-body">
                        <?php

                        // CHECK STATUS
                        $statusText = '<span style="color: #E80">●</span> <span>申請中 / </span>';
                        switch ($request->status) {
                            case 1:
                                $statusText = '<span style="color: #0E0">●</span> <span>承認 / </span>';
                                break;
                            case 2:
                                $statusText = '<span style="color: #E00">●</span> <span>却下 / </span>';
                                break;
                            case 3:
                                $statusText = '<span style="color: #AAA">●</span> <span>取消 / </span>';
                                break;
                        }
                        ?>
                        <div style="font-size: 13pt; font-weight: bold">
                            {!! $statusText !!}<span>{{ implode(", ", $related[$request->id]['date']) }}</span>
                        </div>
                        <div>
                            申請種別: {{$request->request_types()->first()->name}}
                        </div>
                        <div>
                            理由: {{$request->reason}}
                        </div>
                    </div>
                </div>
            @endforeach
            {{$requests->links()}}
        @else
            <span class="text-muted text-center">
                対応待ちの申請はありません
            </span>
        @endif
        <h4 class="fw-bold mt-5">最新15件の勤務情報</h4>
        <hr>
        <table class="table mb-5">
            <tr>
                <th>日付</th>
                <th>社員名</th>
                <th>状態</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>勤務時間</th>
            </tr>
            @foreach($data as $dat)
                <tr class="attends-row" onclick="jump('/admin/attend-manage/view/{{$dat->id}}')">
                    <td>
                        <?php
                        $date_now = new DateTime($dat->date);
                        echo $date_now->format('Y年m月d日');
                        ?>
                    </td>
                    <td>
                        {{$dat->name}}
                    </td>
                    <td>
                        {{$dat->mode == 1 ? "退勤済" : "出勤中"}}
                    </td>
                    <td>
                        {{$dat->created_at}}
                    </td>
                    <td>
                        {{$dat->left_at ?? "--:--"}}
                    </td>
                    <td>
                        <?php
                        $date_now = new DateTime();
                        if ($dat->mode == 1) {
                            $date_now = $dat->left_at ?? $dat->updated_at;
                        }
                        $interval = $dat->created_at->diff($date_now);

                        $datArray = preg_split("/:/", $interval->format('%h:%I'));
                        $restData = preg_split("/:/", $dat->rest ?? "00:00");
                        $wHours = intval($datArray[0]);
                        $wMinutes = intval($datArray[1]);
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
                        echo $xHours . ":" . sprintf("%02d", $xMinutes);
                        ?>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
