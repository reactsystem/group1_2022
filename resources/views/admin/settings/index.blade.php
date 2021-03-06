@extends('layouts.admin')

@section('pageTitle', "システム設定")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">システム設定</h2>
            </div>
            <div class="col-md-6">
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
        <div class="card mb-3 mt-3">
            <div class="card-header">
                通知
            </div>
            <div class="card-body">
                <div>
                    <span>
                        通知管理<br>
                        <span class="text-muted">通知の再表示と削除が行えます</span>
                    </span>
                    <a href="/admin/settings/notifications"
                       class="btn btn-primary float-right mt--19">通知管理</a>
                </div>
            </div>
        </div>
        <div class="card mb-3 mt-3">
            <div class="card-header">
                環境設定
            </div>
            <div class="card-body">
                <div>
                    <span>
                        有給・定時・休憩設定<br>
                        <span class="text-muted">勤務時間や有給付与のタイミングを設定します</span>
                    </span>
                    <a href="/admin/settings/general"
                       class="btn btn-primary float-right mt--19">設定</a>
                </div>
            </div>
        </div>
        <div class="card mb-3 mt-3">
            <div class="card-header">
                マスタ設定
            </div>
            <div class="card-body">
                <div>
                    <span>
                        休日設定<br>
                        <span class="text-muted">会社としての休日を設定します</span>
                    </span>
                    <a href="/admin/settings/holiday"
                       class="btn btn-primary float-right mt--19">設定</a>
                </div>
                <hr>
                <div>
                    <span>
                        部署設定<br>
                        <span class="text-muted">部署を管理します</span>
                    </span>
                    <a href="/admin/settings/department"
                       class="btn btn-primary float-right mt--19">設定</a>
                </div>
                <hr>
                <div>
                    <span>
                        申請種別設定<br>
                        <span class="text-muted">各種申請の種別を管理します</span>
                    </span>
                    <a href="/admin/settings/request-types"
                       class="btn btn-primary float-right mt--19">設定</a>
                </div>
            </div>
        </div>
    </div>
@endsection
