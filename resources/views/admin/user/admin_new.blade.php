@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="fw-bold">社員新規登録</h2>
        <hr>
        <h2>社員登録</h2>
        <a href='/admin/attends' class="btn btn-primary">戻る</a>
        <form>
            <div class="mb-3">
              <label for="InputName" class="form-label">名前</label>
              <input type="text" class="form-control" id="InputName" required>
            </div>
            
            <div class="mb-3">
                <label for="InputMemo" class="form-label">社員メモ</label>
                <input type="text" class="form-control" id="InputMemo" value = {{$user->user_memo->text}}required>
              </div>
            
              <div class="mb-3">
                <label for="InputemployeeID" class="form-label">社員番号</label>
                <input type="text" class="form-control" id="InputemployeeID" required>
              </div>
              <div class="mb-3">
              権限<select class="form-select" aria-label="権限" required>
                <option selected value="">ここから選択</option>
                <option value="0">一般ユーザー</option>
                <option value="1">管理者</option>
              </select>
            </div>

            <div class="mb-3">    
              部署<select class="form-select" aria-label="部署" required>

                <option selected value="">ここから選択</option>
                @foreach($departments as $item)
                <option value="{{$item['id']}}">{{$item['name']}}</option>
                @endforeach
              </select>
            </div>

              <div class="mb-3">
                <label for="InputPassword" class="form-label">パスワード</label>
                <input type="text" class="form-control" id="InputPassword" required>
              </div>
                
              <div class="mb-3">
                  <label for="InputEMail" class="form-label">メールアドレス</label>
                  <input type="email" class="form-control" id="InputEMail" required>
              </div>
                
                <div class="mb-3">
                  <label for="InputHoliday" class="form-label">有給休暇</label>
                  <input type="number" class="form-control" id="InputHoliday" required>
                </div>
                
                <div class="mb-3">
                  <label for="InputJoined" class="form-label">入社日</label>
                  <input type="text" class="form-control" id="InputJoined" required>
                </div>

                <div class="mb-3" >
                  <label for="InputAlive" class="form-label">退社日</label>
                  <input type="text" class="form-control" id="InputAlive" disabled>
                </div>
            <button type="submit" class="btn btn-primary">登録</button>
          </form>

    </div>
@endsection
