@extends('layouts.main')

@section('content')
    <div class="container">
        <h2 class="fw-bold">ユーザー情報編集</h2>
        <hr>
        <table>
        <form action = "/account/account_edit_done" method = "POST">
            @csrf
            <div class="mb-3">
                <tr><td>名前</td><td>
                <input type="text" class="form-control" id="InputName" name ="InputName" required>
            </div><td></tr>

                <tr><td>部署 </td><td>{{$user['group_id']}}<td></tr>            
            
            <div class="mb-3">
                <tr><td>メールアドレス</td><td>
                <input type="email" class="form-control" id="InputEmail" name ="InputEmail" required>
            </div><td></tr>

            <tr><td>入社日 </td><td>{{$user['joined_date']}}<td></tr>
            <tr><td>社員番号 </td><td>{{$user['employee_id']}}<td></tr>

            <tr>
                <td><button type="submit" class="btn btn-primary">決定</button></td>
                <td>
                    <a href='/account' class="btn btn-secondary">
                        キャンセル
                    </a>
                </td>
            </tr>
        </form>

</div>
@endsection 
