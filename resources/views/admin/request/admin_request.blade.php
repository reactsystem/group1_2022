@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="fw-bold">申請一覧</h2>
    
        <hr>
        <a href='/admin/attends/new' class="btn btn-primary">検索</a>
        <a href='/admin/attends/new' class="btn btn-primary">追加</a>

        
        <table class="table">
            <thead>
            <tr>
            <th scope="col">社員名</th>
            <th scope="col">期間</th>
            <th scope="col">申請種別</th>
            <th scope="col">理由</th>
            <th scope="col">クイックアクション</th>
            </tr>
        </thead>
        <tbody>
            
        {{--<pre>{{var_dump($requests)}}  </pre>--}}
            
            @foreach($all_requests as $request)
            <tr>
                <td>{{$request -> user -> name}}</td>
                <td>{{$request -> related_request() -> count() +1}}</td>
                <td>{{$request -> request_types -> name}}</td>
                <td>{{$request -> comment ?? "記入なし"}}</td>
                <td>    
                    <a href="#" class="btn btn-primary">承認</a>
                    <a href="#" class="btn btn-secondary">却下</a>
                </td>
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>
@endsection
