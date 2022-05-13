@extends('layouts.main')

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
@section('pageTitle', "ユーザー管理")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">通知確認</h2>
            </div>
            <div class="col-md-6">
                <button class="btn btn-danger"
                        style="float: right;" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    削除
                </button>
                <a href="/account/notifications" class="btn btn-secondary"
                   style="float: right; margin-right: 10px;">通知一覧に戻る</a>
            </div>
            <div class="col-md-12 mt-3" id="alert">
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
        <div style="display: flex">
            <h5 style="flex: 2;">
                {{$data->title}}
            </h5>
            <div class="text-muted" style="flex: 1;">
                <span style="float: right;">
                作成日時: {{$data->created_at}}
                </span>
            </div>
        </div>
        <hr>
        <div style="">
            {!! $data->data !!}
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">削除確認</h5>
                </div>
                <div class="modal-body">
                    この通知を削除してもよろしいですか?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <a href="/account/notifications/delete/{{$id}}" type="button" class="btn btn-danger">削除</a>
                </div>
            </div>
        </div>
    </div>
@endsection
