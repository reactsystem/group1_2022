@extends('layouts.admin')

@section('pageTitle', "社員情報管理")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">社員一覧</h2>
            </div>
            <div class="col-md-6">
                <a href='/admin/attends/new' class="btn btn-primary" style="float: right">新規登録</a>
                <a href='/admin/attends/notify' class="btn btn-success"
                   style="float: right; margin-right: 10px">メッセージ送信</a>
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

            {{--<pre>{{var_dump($users)}}  </pre>--}}

            @foreach($users as $user)
                @if($user->left_date != null)
                    <tr style="background-color: #BBB">
                @else
                    <tr>
                        @endif
                        <th scope="row">{{$user -> id}}</th>
                        <td>{{$user -> employee_id}}</td>
                        <td>{{$user -> name}}</td>
                        <td>{{$user -> departments -> name}}</td>
                        <td>{{$user -> latestAttemdance->date ?? ""}}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    操作
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item"
                                           href="/admin/attends/view?id={{$user -> id}}">社員情報確認・編集</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/admin/attend-manage/calender/{{$user->id}}">勤怠情報確認</a>
                                    </li>
                                    <li>
                                        <form action="/admin/request" method="post">
                                            @csrf
                                            <input type='hidden' value='{{$user -> id}}' name='id'>
                                            <button class="dropdown-item" type="submit">社員申請確認</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
            </tbody>
        </table>
    </div>
@endsection

