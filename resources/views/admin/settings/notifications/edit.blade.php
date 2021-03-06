@extends('layouts.admin')

@section('pageTitle', "システム設定")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h2 class="fw-bold">通知確認</h2>
            </div>
            <div class="col-md-8">
                <button class="btn btn-danger float-right" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    削除
                </button>
                <a href="/admin/settings/notifications" class="btn btn-secondary float-right mr-10px">通知一覧に戻る</a>
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
        <div class="flex-view">
            <h5 class="fw-bold flex-2">
                {{$data->title}}
            </h5>
            <div class="text-muted flex-1">
                <span class="float-right">
                作成日時: {{$data->created_at}}
                </span>
            </div>
        </div>
        <hr>
        <div class="pointer-cursor" onclick="href('/notification/{{$data->id}}')">
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
