@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">申請確認</h2>
            </div>
            <div class="col-md-6">
                @if($result->status === 0)
                    <a class="btn btn-danger" href="#" style="float: right; ">申請取消</a>
                @else
                    <a class="btn btn-danger disabled" href="#" style="float: right;">申請取消</a>
                @endif
                <a class="btn btn-outline-secondary" href="/request" style="float: right; margin-right: 10px">申請一覧に戻る</a>
            </div>
        </div>
        <hr>
        <div style="font-size: 14pt">
            <?php
            // CHECK STATUS
            $statusText = '<span style="color: #E80">●</span> <strong>申請中</strong>';
            switch($result->status){
                case 1:
                    $statusText = '<span style="color: #0E0">●</span> <strong>承認</strong>';
                    break;
                case 2:
                    $statusText = '<span style="color: #E00">●</span> <strong>却下</strong>';
                    break;
                case 3:
                    $statusText = '<span style="color: #AAA">●</span> <strong>取消</strong>';
                    break;
            }
            ?>
            <div>日時: <strong>2022年5月2日～2022年5月6日</strong></div>
            <div>ステータス: {!! $statusText !!}</div>
            <div>申請種別: <strong>{{$result->name}}</strong></div>
            <div>有給消費: <strong>2日(残り8日)</strong></div>
            <div>理由: <strong>{{$result->reason}}</strong></div>
        </div>
    </div>
@endsection
