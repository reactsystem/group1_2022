@extends('layouts.main')

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
        {{$user['name']}} さんとしてログイン中 <br>
        有給休暇残：{{$user['paid_holiday']}}日 <br>

    <table>
        <tr>
            <th>ユーザー情報</th>
        </tr>
        <tr>
            <td>ユーザー情報確認・編集</td>
            <td>      
                <a class="btn btn-primary" href="/account/edit" role="button">確認・編集</a>
            </td>
        </tr>
        <tr>
            <td>
                パスワード変更
            </td>
            <td>
                <a class="btn btn-primary" href="/account/password_update" role="button">変更</a>

            </td>
        </tr>    
    </table>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#logoutModal">
        ログアウト
    </button>

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
                <a href={{ route('logout') }} onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();" class="btn btn-primary">
                    ログアウト
                </a>

                <form id='logout-form' action={{ route('logout')}} method="POST" style="display: none;">
                    @csrf

                </div>
            </div>
        </div>
  </div>
</div>
@endsection 
