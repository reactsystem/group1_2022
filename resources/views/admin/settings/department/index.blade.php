@extends('layouts.admin')

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
@endsection
@section('pageTitle', "システム設定")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">部署設定</h2>
            </div>
            <div class="col-md-6">
                <a href="/admin/settings/department/new" class="btn btn-primary" style="float: right">部署を追加</a>
                <a href="/admin/settings" class="btn btn-secondary"
                   style="float: right; margin-right: 10px">システム設定に戻る</a>
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
                <th>部署名</th>
                <th>最終更新</th>
            </tr>
            @foreach($data as $dat)
                <tr class="attends-row" onclick="jump('/admin/settings/department/edit/{{$dat->id}}')">
                    <td>{{$dat->name}}</td>
                    <td>{{$dat->updated_at}}</td>
                </tr>
            @endforeach
        </table>
        {{$data->links()}}
        <div class="mb-5">
            &nbsp;
        </div>
    </div>
@endsection
