@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">社員一覧</h2>
            </div>
            <div class="col-md-6">
                <a href='/admin/attends/new' class="btn btn-primary" style="float: right">新規登録</a>
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
                <tr>
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
                                    <a class="dropdown-item" href="/admin/attends/view?id={{$user -> id}}">社員情報確認・編集</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/admin/hogehoge?id={{$user -> id}}">社員申請確認</a>
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

