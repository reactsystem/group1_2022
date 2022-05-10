@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="fw-bold">社員情報確認</h2>
        <hr>
        <a href='/admin/attends' class="btn btn-primary">戻る</a>
        <a href='/admin/attends/edit?id={{$user['id']}}' class="btn btn-primary">編集</a>

        <form>
          @csrf
          <input type="hidden" name="id" value="{{$user['id']}}">
            <div class="mb-3">
              <label for="InputName" class="form-label" >名前</label>
              <input type="text" class="form-control" value="{{$user['name']}}" id="InputName" name ='name'  disabled>
            </div>
            
            <div class="mb-3">
                <label for="InputMemo" class="form-label">社員メモ</label>
                <input type="text" class="form-control" id="InputMemo" value="{{$user->user_memo->memo}}" name='memo'  disabled>
              </div>
            
              <div class="mb-3">
                <label for="InputemployeeID" class="form-label">社員番号</label>
                <input type="text" class="form-control" value="{{$user['employee_id']}}" name='employee_id' id="InputemployeeID"  disabled required>
              </div>

              <div class="mb-3">
              権限<select class="form-select" aria-label="権限" name='group_id'  disabled>
                @if(0==$user['group_id'])
                <option value="">ここから選択</option>
                <option selected value="0">一般ユーザー</option>
                <option value="1">管理者</option>
                @elseif(1==$user['group_id'])
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
              部署<select class="form-select" aria-label="部署" name='department'  disabled>

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
                <input type="password" class="form-control" id="InputPassword" name='password' disabled>
              </div>
                
              <div class="mb-3">
                  <label for="InputEmail" class="form-label">メールアドレス</label>
                  <input type="email" class="form-control" id="InputEMail" name='email' value = "{{$user ['email']}}"  disabled>
              </div>
                
                <div class="mb-3">
                  <label for="InputHoliday" class="form-label">有給休暇</label>
                  <input type="number" class="form-control" name='paid_holiday' id="InputHoliday" value = "{{$user ['paid_holiday']}}"  disabled>
                </div>
                
                <div class="mb-3">
                  <label for="InputJoined" class="form-label">入社日</label>
                  <input type="text" class="form-control" name='joined_date' id="InputJoined" placeholder ='YYYY-MM-DD' value = "{{$user ['joined_date']}}"  disabled>
                </div>

                <div class="mb-3" >
                  <label for="InputAlive" class="form-label">退社日</label>
                  <input type="text" class="form-control" name='left_date' id="InputAlive"  placeholder ='YYYY-MM-DD' value = "{{$user ['left_date']}}"  disabled>
                </div>
                </div>

          </form>

    </div>
@endsection
