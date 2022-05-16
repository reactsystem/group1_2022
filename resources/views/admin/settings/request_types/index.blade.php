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
                <h2 class="fw-bold">申請種別設定</h2>
            </div>
            <div class="col-md-6">
                <a href="/admin/settings/request-types/new" class="btn btn-primary" style="float: right">申請種別を追加</a>
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
                <th>名称</th>
                <th>タイプ</th>
                <th>最終更新</th>
            </tr>
            @foreach($data as $dat)
                <tr class="attends-row" onclick="jump('/admin/settings/request-types/edit/{{$dat->id}}')">
                    <td>{{$dat->name}}</td>
                    <td>
                        @if($dat->type == 1)
                            時間指定
                        @elseif($dat->type == 2)
                            有給消費(理由不要)
                        @elseif($dat->type == 0)
                            理由必要
                        @elseif($dat->type == 3)
                            理由不要
                        @else
                            ---
                        @endif
                    </td>
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
