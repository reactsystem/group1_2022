@extends('layouts.main')

@section('content')
    <div class="container">
        <h2 class="fw-bold">ユーザー情報編集</h2>
        <hr>
        <form action="/account/account_edit_done" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nameInput" class="form-label">名前</label>
                <input type="text" class="form-control" id="nameInput" name="InputName" placeholder="名前を入力してください"
                       value="{{$user['name']}}" required>
            </div>

            <div class="mb-3">
                <label for="nameInput" class="form-label">部署</label>
                <input class="form-control text-muted" style="cursor: no-drop" value="{{$user['group_id']}}" disabled>
            </div>

            <div class="mb-3">
                <label for="emailInput" class="form-label">名前</label>
                <input type="email" class="form-control" id="emailInput" name="InputEmail" placeholder="メールアドレスを入力"
                       value="{{$user['email']}}" required>
            </div>

            <div class="mb-3">
                <label for="joinedDate" class="form-label">入社日</label>
                <input class="form-control text-muted" id="joinedDate" style="cursor: no-drop"
                       value="{{$user['joined_date']}}" disabled>
            </div>

            <div class="mb-3">
                <label for="employeeId" class="form-label">社員番号</label>
                <input class="form-control text-muted" id="employeeId" style="cursor: no-drop"
                       value="{{$user['employee_id']}}" disabled>
            </div>

            <button type="submit" class="btn btn-primary">決定</button>
            <a href='/account' class="btn btn-secondary">キャンセル</a>
        </form>

    </div>
@endsection
