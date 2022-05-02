@extends('layouts.main')

@section('styles')
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
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">各種申請</h2>
            </div>
            <div class="col-md-6">
                <a class="btn btn-primary" href="#" style="float: right; width: 100px">新規申請</a>
            </div>
        </div>
        <hr>
        @foreach($results as $result)
            <?php

            // CHECK STATUS
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
            <div class="card card-hover" style="width: 100%; cursor: pointer"
                 onclick="href('/request/{{$result->id}}')">
                <div class="card-body">
                    <div style="font-size: 13pt; font-weight: bold">
                        {!! $statusText !!}<span>2022年5月2日～2022年5月6日</span>
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
        {{$results->links()}}
    </div>
@endsection
