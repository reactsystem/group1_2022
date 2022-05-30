@extends('layouts.admin')

@section('pageTitle', "システム設定")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">通知一覧</h2>
            </div>
            <div class="col-md-6">
                <a href="/admin/settings" class="btn btn-secondary float-right mr-10px">システム設定に戻る</a>
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
        <table class="table">
            <tr>
                <th>タイトル</th>
                <th>日付</th>
            </tr>
            @foreach($data as $dat)
                <tr class="attends-row<?php if ($dat->status != 0) echo ' bg-gray2';?>"
                    onclick="jump('/admin/settings/notifications/{{$dat->id}}')">
                    <td>{{$dat->title}}</td>
                    <td>{{$dat->created_at ?? "---"}}</td>
                </tr>
            @endforeach
        </table>
        {{$data->links()}}
        <div class="mb-5">
            &nbsp;
        </div>
    </div>
@endsection
