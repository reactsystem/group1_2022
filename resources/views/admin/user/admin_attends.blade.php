@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="fw-bold">社員情報管理</h2>
        <hr>
        <h2>社員一覧</h2>
        ##新規登録ボタン##
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
            @foreach($users as $user)
            <tr>
                <th  scope="row">{{$user -> id}}</td>
                <td>{{$user -> employee_id}}</td>
                <td>{{$user -> name}}</td>
                <td>{{$user -> departments -> name}}</td>
                <td></td>
                <td>プルダウン</td>
            </tr>
            @endforeach
        </tbody>
            {{$users ->appends(['sort' => $sort])->links() }}
        </table>
    </div>
@endsection

<nav aria-label="Page navigation example">
    <ul class="pagination">
      <li class="page-item">
        <a class="page-link" href="#" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
        </a>
      </li>
      <li class="page-item"><a class="page-link" href="#">1</a></li>
      <li class="page-item"><a class="page-link" href="#">2</a></li>
      <li class="page-item"><a class="page-link" href="#">3</a></li>
      <li class="page-item">
        <a class="page-link" href="#" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
    </ul>
  </nav>