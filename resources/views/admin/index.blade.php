@extends('layouts.admin')

@section('pageTitle', "管理者CP")

@section('content')
    <div class="container">
        <h2 class="fw-bold">ダッシュボード</h2>
        <hr>
        <div class="row mt-4">
            <div class="col-md-8 col-sm-6 col-6">
                <h3 class="fw-bold">新着通知</h3>
            </div>
            <div class="col-md-4 col-sm-6 col-6">
                <a href="/admin/settings/notifications" class="btn btn-primary float-right">通知一覧</a>
            </div>
        </div>
        <hr>
        @if($notifications != null && count($notifications) != 0)
            @foreach($notifications as $notification)
                <div class="card mb-3 card-hover pointer-cursor"
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
        <div class="row mt-5">
            <div class="col-md-8 col-sm-6 col-6">
                <h4 class="fw-bold">対応待ちの申請 ({{$requests->total()}}個)</h4>
            </div>
            <div class="col-md-4 col-sm-6 col-6">
                <a href="/admin/request" class="btn btn-primary float-right">申請一覧</a>
            </div>
        </div>
        <hr>
        @if($requests != null && count($requests) != 0)
            @foreach($requests as $request)
                <div class="card mb-3 card-hover pointer-cursor"
                     onclick="href('/admin/request/detail?id={{$request->id}}')">
                    <div class="card-body">
                        <?php

                        // CHECK STATUS
                        /* @var $request */
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
        <div class="row mt-5">
            <div class="col-md-8 col-sm-7 col-7">
                <h4 class="fw-bold">最新15件の勤怠</h4>
            </div>
            <div class="col-md-4 col-sm-5 col-5">
                <a href="/admin/attend-manage" class="btn btn-primary float-right">勤怠管理</a>
            </div>
        </div>
        <hr>
        <div class="table-responsive">
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
                            /* @var $dat */
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
                            $interval = $dat->created_at->diff($date_now);
                            $created = new DateTime($dat->created_at->format("H:i:50"));
                            $intervalTime = $created->diff($date_now);
                            if ($dat->mode == 1 && $dat->left_at != null) {
                                $tempLeftDat1 = preg_split("/ /", $dat->left_at);
                                $tempLeftDat2 = preg_split("/:/", $tempLeftDat1[1]);
                                $datx = $tempLeftDat2[0] . ":" . $tempLeftDat2[1] . ":50";
                                $left = new DateTime($tempLeftDat2[0] . ":" . $tempLeftDat2[1] . ":50");
                                $intervalTime = $created->diff($left);
                                //$intervalTime->set
                            }

                            $datArray = preg_split("/:/", $intervalTime->format('%h:%I'));
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
    </div>
@endsection
