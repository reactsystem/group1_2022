@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">パスワードの変更</div>
     
                    <div class="panel-body">
                        {{-- フラッシュメッセージの表示 --}}
                        @if (session('warning'))
                            <div class="alert alert-warning">
                                {{ session('warning') }}
                            </div>
                        @endif
                        @if (session('status'))
                            <div class="alert alert-info">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form class="form-horizontal" method="POST" action='password_update_done'>
                            {{ csrf_field() }}
                            {{ method_field('patch') }}
     
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
                                <label for="current_password" class="col-md-4 control-label">現在のパスワード</label>
     
                                <div class="col-md-6">
                                    <input id="current_password" type="password" class="form-control" name="current_password" required>
     
                                    @if ($errors->has('current_password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('current_password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
     
                            <div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
                                <label for="new_password" class="col-md-4 control-label">新しいパスワード</label>
     
                                <div class="col-md-6">
                                    <input id="new_password" type="password" class="form-control" name="new_password" required>
     
                                    @if ($errors->has('new_password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('new_password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
     
                            <div class="form-group{{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
                                <label for="new_password-confirm" class="col-md-4 control-label">新しいパスワード（確認）</label>
                                <div class="col-md-6">
                                    <input id="new_password-confirm" type="password" class="form-control" name="new_password_confirmation" required>
     
                                    @if ($errors->has('new_password_confirmation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('new_password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
     
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        パスワードの変更
                                    </button>
                                
                                
                                    <a href='/account' class="btn btn-secondary">
                                        キャンセル
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection 
