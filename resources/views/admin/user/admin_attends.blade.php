@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="fw-bold">社員情報管理</h2>
        <hr>
        <h2>社員一覧</h2>
        <a href='/admin/attends/new' class="btn btn-primary">新規登録</a>
        
        <table class="table">
            <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">社員番号</th>
            <th scope="col">社員名</th>
            <th scope="col">部署</th>
            <th scope="col">最終出勤</th>
            <th scope="col">操作</th>
            </tr>
        </thead>
        <tbody>
            {{var_damp($last_attends)}}

            @foreach($users as $user)
            <tr>
                <th  scope="row">{{$user -> id}}</td>
                <td>{{$user -> employee_id}}</td>
                <td>{{$user -> name}}</td>
                <td>{{$user -> departments -> name}}</td>
                <td>ここに最終出勤日</td>
                <td>            
                    <div class="btn-group">
                        <button type="button" class="btn btn-Primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        操作
                        </button>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/admin/attends/view?id={{$user -> id}}">社員情報確認・編集</a></li>

                        <li><a class="dropdown-item" href="/admin/hogehoge?id={{$user -> id}}">社員申請確認</a></li>
                        
                        </ul>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
            {{$users ->appends(['sort' => $sort])->links() }}
        </table>
    </div>
@endsection

