@extends('layouts.main')

@section('pageTitle', "ユーザー管理")

@section('content')
    <div class="container">
        <h2 class="fw-bold">ユーザー情報編集</h2>
        <hr>
        <form action="/account/account_edit_done" method="POST">
            @csrf

            @if(env('ENABLE_NAME_EDIT', true))
                <div class="mb-3">
                    <label for="nameInput" class="form-label">名前</label>
                    <input type="text" class="form-control" id="nameInput" name="InputName" placeholder="名前を入力してください"
                           value="{{$user['name']}}" required>
                </div>
            @else
                <div class="mb-3">
                    <label for="nameInput" class="form-label">名前</label>
                    <input type="text" class="form-control text-muted pointer-no-drop" id="nameInput" name="InputName"
                           placeholder="名前を入力してください"
                           value="{{$user['name']}}" disabled>
                </div>
            @endif

            <div class="mb-3">
                <label for="nameInput" class="form-label">部署</label>
                <input class="form-control text-muted pointer-no-drop" value="{{$user['dname']}}" disabled>
            </div>

            @if(env('ENABLE_EMAIL_EDIT', true))
                <div class="mb-3">
                    <label for="emailInput" class="form-label">メールアドレス</label>
                    <input type="email" class="form-control" id="emailInput" name="InputEmail" placeholder="メールアドレスを入力"
                           value="{{$user['email']}}" required>
                </div>
            @else
                <div class="mb-3">
                    <label for="emailInput" class="form-label">メールアドレス</label>
                    <input type="email" class="form-control text-muted pointer-no-drop" id="emailInput"
                           name="InputEmail" placeholder="メールアドレスを入力"
                           value="{{$user['email']}}" disabled>
                </div>
            @endif

            <div class="mb-3">
                <label for="joinedDate" class="form-label">入社日</label>
                <input class="form-control text-muted pointer-no-drop" id="joinedDate"
                       value="{{$user['joined_date']}}" disabled>
            </div>

            <div class="mb-3">
                <label for="employeeId" class="form-label">社員番号</label>
                <input class="form-control text-muted pointer-no-drop" id="employeeId"
                       value="{{$user['employee_id']}}" disabled>
            </div>

            <button type="submit" class="btn btn-primary">決定</button>
            <a href='/account' class="btn btn-secondary">キャンセル</a>
        </form>

    </div>
@endsection
