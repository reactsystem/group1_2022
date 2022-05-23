@extends('layouts.main')

@section('pageTitle', "各種申請")

@section('content')
    <div class="container mb-5">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">各種申請</h2>
            </div>
            <div class="col-md-6">
                <a class="btn btn-primary float-right ml-5px width-100" href="/request/create">新規申請</a>
                @if($mode == 1)
                    <a class="btn btn-outline-secondary float-right" href="/request">取消済の申請を非表示</a>
                @else
                    <a class="btn btn-secondary float-right" href="/request?mode=1">取消済の申請を表示</a>
                @endif
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
        @foreach($results as $result)
            <?php

            // CHECK STATUS
            /* @var $result */
            $statusText = '<span style="color: #E80">●</span> <span>申請中 / </span>';
            switch ($result->status) {
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
                <div class="card card-hover width-100pct pointer-cursor mb-10px"
                     onclick="href('/request/{{$result->id}}')">
                    <div class="card-body">
                        <div class="fw-bold font-13">
                            {!! $statusText !!}<span>{{ implode(", ", $related[$result->id]['date']) }}</span>
                    </div>
                    <div>
                        申請種別: {{$result->name}}
                    </div>
                    <div>
                        理由: {{$result->reason}}
                    </div>
                </div>
            </div>
        @endforeach
        {{$results->appends($parameters)->links()}}
    </div>
@endsection
