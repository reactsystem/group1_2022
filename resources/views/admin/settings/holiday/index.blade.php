@extends('layouts.admin')

@section('pageTitle', "システム設定")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">休日設定</h2>
            </div>
            <div class="col-md-6">
                <a href="/admin/settings/holiday/new" class="btn btn-primary float-right">休日を追加</a>
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
                <th>名称</th>
                <th>年</th>
                <th>月</th>
                <th>日</th>
                <th>種別</th>
            </tr>
            @foreach($data as $dat)
                <tr class="attends-row" onclick="jump('/admin/settings/holiday/edit/{{$dat->id}}')">
                    <td>{{$dat->name}}</td>
                    <td>{{$dat->year ?? "毎年"}}</td>
                    <td>{{$dat->month ?? "毎月"}}</td>
                    <td>{{$dat->day}}</td>
                    <td>{{$dat->mode == 0 ? "有給" : "無給"}}</td>
                </tr>
            @endforeach
        </table>
        {{$data->links()}}
        <div class="mb-5">
            &nbsp;
        </div>
    </div>
@endsection
