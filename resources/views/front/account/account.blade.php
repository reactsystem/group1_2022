@extends('layouts.main')

@section('pageTitle', "ユーザー管理")

@section('content')
    <div class="container">
        <h2 class="fw-bold">ユーザー管理</h2>
        <hr>
        @if(session('warning'))
            <div class="alert alert-danger">
                {{ session('warning') }}
            </div>
        @endif

        @if(session('status'))
            <div class="alert alert-danger">
                {{ session('status') }}
            </div>
        @endif
        <div>
            {{$user['name']}} さんとしてログイン中 <br>
            有給休暇残：<strong>{{\App\Models\PaidHoliday::getHolidays(Auth::id())}}日</strong> <br>
        </div>

        <div class="card mb-3 mt-3">
            <div class="card-header">
                データ管理
            </div>
            <div class="card-body">
                <div>
                    <span>
                        有給管理<br>
                        <span class="text-muted">有給の詳細な情報が確認できます</span>
                    </span>
                    <a href="/account/holidays" class="btn btn-primary float-right mt--19">有給管理</a>
                </div>
            </div>
        </div>

        <div class="card mb-3 mt-3">
            <div class="card-header">
                ユーザー情報
            </div>
            <div class="card-body">
                <div>
                    <span>
                        通知管理<br>
                        <span class="text-muted">通知の再表示と削除が行えます</span>
                    </span>
                    <a href="/account/notifications" class="btn btn-primary float-right mt--19">通知管理</a>
                </div>
                <hr>
                <div>
                    <span>
                        ユーザー情報確認・編集<br>
                        <span class="text-muted">ユーザー情報の確認と編集が行えます。</span>
                    </span>
                    <a href="/account/edit" class="btn btn-primary float-right mt--19">確認・編集</a>
                </div>
                <hr>
                <div>
                    <span>
                        パスワード変更<br>
                        <span class="text-muted">パスワードを変更します</span>
                    </span>
                    <a href="/account/password_update" class="btn btn-primary float-right mt--19">変更</a>
                </div>
            </div>
        </div>

        <div class="width-100pct">
            <div class="margin-0-auto flex-view">
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    ログアウト
                </button>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="logoutModalLabel">ログアウト</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ログアウトしますか
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                        <a href={{ route('logout') }} onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                           class="btn btn-danger">
                            ログアウト
                        </a>

                        <form id='logout-form' action={{ route('logout')}} method="POST" class="d-none">
                        @csrf

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
