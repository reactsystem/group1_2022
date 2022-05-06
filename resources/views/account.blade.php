@extends('layouts.main')

@section('content')
    <div class="container">
        <h2 class="fw-bold"></h2>
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
        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#sampleModal">
            確認・編集
        </button>
        </td>
    </tr>
    <tr>
        <td>
            パスワード変更
        </td>
        <td>
        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#sampleModal">
            変更
        </button>
        </td>
    </tr>
        
</table>
    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#sampleModal">
        ログアウト
    </button>
    

    </div>
@endsection 