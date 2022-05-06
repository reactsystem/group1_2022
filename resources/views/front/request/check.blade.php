@extends('layouts.main')
@section('pageTitle', "新規申請")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">申請内容確認</h2>
            </div>
        </div>
        <hr>
        <div>日時: <strong>{{implode(", ", $dates)}}</strong></div>
        <div>申請種別: <strong>{{$type->name}}</strong></div>
        @if($time != "")
            <div>労働時間: <strong>{{$time}}</strong></div>
        @endif
        @if($holidays != 0)
            <div>有給消費: <strong>{{$holidays}}日 (申請前の残有給日数: 8日)</strong></div>
        @endif
        <div>理由: <strong>{{$reason}}</strong></div>

        <div style="float: right">
            <button type="submit" class="btn btn-primary">申請</button>
            <a href="/request/create/back" class="btn btn-secondary">キャンセル</a>
        </div>
    </div>
@endsection
