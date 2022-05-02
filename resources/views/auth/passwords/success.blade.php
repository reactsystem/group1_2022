@extends('layouts.basic')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <div class="container" style="text-align: center">
                        <h3>パスワードを再設定しました</h3>
                        <p>
                            パスワードを再設定しました。次のページでログインを行ってください。
                        </p>
                        <a class="btn btn-primary" href="/login">ログイン</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
