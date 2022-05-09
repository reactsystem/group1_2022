@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="fw-bold">社員情報確認・編集</h2>
        <hr>
        <h2>社員情報確認・編集</h2>
        <a href='/admin/attends' class="btn btn-primary">戻る</a>
        <form>
            <div class="mb-3">
              <label for="InputName" class="form-label" >名前</label>
              <input type="text" class="form-control" value="{{$user['name']}}" id="InputName" required>
            </div>
            
            <div class="mb-3">
                <label for="InputMemo" class="form-label">社員メモ</label>
                <input type="text" class="form-control" id="InputMemo" value="{{$user_memo}}" >
              </div>
            
              <div class="mb-3">
                <label for="InputemployeeID" class="form-label">社員番号</label>
                <input type="text" class="form-control" value="{{$user['employee_id']}}" id="InputemployeeID" required>
              </div>
              <div class="mb-3">
              権限<select class="form-select" aria-label="権限" required>
                @if(0==$user['employee_id'])
                <option value="">ここから選択</option>
                <option selected value="0">一般ユーザー</option>
                <option value="1">管理者</option>
                @elseif(1==$user['employee_id'])
                <option value="">ここから選択</option>
                <option value="0">一般ユーザー</option>
                <option selected value="1">管理者</option>
                @else
                <option selected value="">ここから選択</option>
                <option value="0">一般ユーザー</option>
                <option value="1">管理者</option>
                @endif
              </select>
            </div>

            <div class="mb-3">    
              部署<select class="form-select" aria-label="部署" required>

                @foreach($departments as $item)
                @if($item['id'] == $user['department'])
                <option selected value = "{{$item['id']}}">{{$item['name']}}</option>
                @else
                <option value = "{{$item['id']}}">{{$item['name']}}</option>
                @endif
                @endforeach
              </select>
            </div>

              <div class="mb-3">
                <label for="InputPassword" class="form-label">パスワード</label>
                <input type="password" class="form-control" id="InputPassword" value = {{$user['password']}} required>
              </div>
                
              <div class="mb-3">
                  <label for="InputEmail" class="form-label">メールアドレス</label>
                  <input type="email" class="form-control" id="InputEMail" value = {{$user ['mail']}}required>
              </div>
                
                <div class="mb-3">
                  <label for="InputHoliday" class="form-label">有給休暇</label>
                  <input type="number" class="form-control" id="InputHoliday" value = {{$user ['paid_holiday']}} required>
                </div>
                
                <div class="mb-3">
                  <label for="InputJoined" class="form-label">入社日</label>
                  <input type="text" class="form-control" id="InputJoined" value = {{$user ['joined_date']}} required>
                </div>

                <div class="mb-3" >
                  <label for="InputAlive" class="form-label">退社日</label>
                  <input type="text" class="form-control" id="InputAlive"  value = {{$user ['left_date']}}>
                </div>
            <button type="submit" class="btn btn-primary">確定</button>
          </form>

    </div>
@endsection
