@extends('layouts.main')

@section('content')
    <div class="container">
        <h2 class="fw-bold">ユーザー管理</h2>
        <hr>
        {{$user['name']}} さんとしてログイン中 <br>
        有給休暇残：{{$user['paid_holiday']}}日 <br>

    <table>
        <tr>
            <th>ユーザー情報</th>
        </tr>
        <tr>
            <td>ユーザー情報確認・編集</td>
            <td>      
            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="">
                <form action="account/edit">
                    <input type="submit" value="確認・編集">
                </form>
            </button>
            </td>
        </tr>
        <tr>
            <td>
                パスワード変更
            </td>
            <td>
            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="">
                <form action="account/password_update">
                    <input type="submit" value="変更">
                </form>
            </button>
            </td>
        </tr>    
    </table>

    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#sampleModal">
        ログアウト
    </button>


</div>
@endsection 
