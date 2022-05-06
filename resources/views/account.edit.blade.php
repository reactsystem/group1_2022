@extends('layouts.main')

@section('content')
    <div class="container">
        <h2 class="fw-bold">ユーザー情報編集</h2>
        <hr>
        名前 {{$user['name']}}
        部署 {{$user['group_id']}}
        メールアドレス {{$user['name']}}
        入社日 {{$user['joined_date']}}
        社員番号 {{$user['employee_id']}}



</div>
@endsection 
