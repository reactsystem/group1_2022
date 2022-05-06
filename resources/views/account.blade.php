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
                確認・編集
            </button>
            </td>
        </tr>
        <tr>
            <td>
                パスワード変更
            </td>
            <td>
            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="">
                変更
            </button>
            </td>
        </tr>    
    </table>

    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#sampleModal">
        ログアウト
    </button>
    
    <!-- モーダル・ダイアログ -->
    <div class="modal fade" id="sampleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                    <h4 class="modal-title">ログアウト</h4>
                </div>
                <div class="modal-body">
                    ログアウトしますか？
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                    <button type="button" class="btn btn-primary">ボタン</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection 
