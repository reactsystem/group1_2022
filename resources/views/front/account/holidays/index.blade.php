@extends('layouts.main')

@section('pageTitle', "ユーザー管理")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">有給一覧</h2>
            </div>
            <div class="col-md-6">
                <a href="/account" class="btn btn-secondary float-right mr-10px">ユーザー管理へ戻る</a>
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
        @if($searchStr != "")
            <span>{!! $searchStr !!}</span>
        @endif
        <hr>
        <table class="table">
            <tr>
                <th>残日数</th>
                <th>付与日</th>
                <th>ステータス</th>
            </tr>
            @foreach($data as $dat)
                <?php
                $twoYearAgo = date("Y-m-d H:i:s", strtotime("-2 year"));
                $status = "有効";
                $className = "";
                /* @var $dat */
                if ($dat->created_at < $twoYearAgo) {
                    $className = "background-color: #999;";
                    $status = "失効 - 期限切れ";
                } elseif ($dat->amount == 0) {
                    $className = "background-color: #999;";
                    $status = "無効 - 使用済み";
                }
                ?>
                <tr class="attends-row pointer-default-i pointer-nonevent" style="{{$className}}">
                    <td>{{$dat->amount}}日</td>
                    <td>{{$dat->created_at->format('Y年 n月 j日')}}</td>
                    <td>{{$status}}</td>
                </tr>
            @endforeach
        </table>
        {{$data->appends($parameters)->links()}}
        <div class="mb-5">
            &nbsp;
        </div>
    </div>
@endsection
