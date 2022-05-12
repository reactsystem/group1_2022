@extends('layouts.admin')

@section('pageTitle', "社員情報管理")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">社員情報編集</h2>
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-primary" style="float: right" data-bs-toggle="modal"
                        data-bs-target="#Modal">
                    保存
                </button>
                <a href='/admin/attends' class="btn btn-primary" style="float: right; margin-right: 10px;">社員一覧へ戻る</a>
            </div>
            @if (session('error'))
                <div class="col-md-12 mt-3">
                    <div class="alert alert-danger" role="alert">
                        <strong>エラー</strong> {{ session('error') }}
                    </div>
                </div>
            @endif
            @if (session('result'))
                <div class="col-md-12 mt-3">
                    <div class="alert alert-success" role="alert">
                        {{ session('result') }}
                    </div>
                </div>
            @endif
        </div>
        <hr>


        <form action='/admin/attends/update' method='POST'>
            @csrf

            {{--モーダル--}}
            <div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">確認</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  保存しますか？
{{--                   
                  <div>
                    <p class="text-muted">名前</p>
                    <p class="px-2" id="InputName"></p>
                  </div>
                  <div>
                    <p class="text-muted">社員メモ</p>
                    <p class="px-2" id="InputMemo"></p>
                  </div>
                  <div>
                    <p class="text-muted">社員番号</p>
                    <p class="px-2" id="InputemployeeID"></p>
                  </div>
                  <div>
                    <p class="text-muted">権限</p>
                    <p class="px-2" id='InputGroup_id'></p>
                  </div>
                  <div>
                    <p class="text-muted">部署</p>
                    <p class="px-2" id="InputDepartment"></p>
                  </div>

                  <div>
                    <p class="text-muted">メールアドレス</p>
                    <p class="px-2" id="InputEMail"></p>
                  </div>
                  <div>
                    <p class="text-muted">有給休暇残</p>
                    <p class="px-2"id="InputHoliday"></p>
                  </div>

                  <div>
                    <p class="text-muted">入社日</p>
                    <p class="px-2" id="InputJoined"></p>
                  </div>

                  <div>
                    <p class="text-muted">退社日</p>
                    <p class="px-2" id="InputAlive"></p>
                  </div> 
--}}

                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </div>
            </div>
          </div>






          <input type="hidden" name="id" value="{{$user['id']}}">
            <div class="mb-3">
              <label for="InputName" class="form-label" >名前</label>
              <input type="text" class="form-control" value="{{$user['name']}}" id="InputName" name ='name' required>
            </div>
            
            <div class="mb-3">
                <label for="InputMemo" class="form-label">社員メモ</label>
                <input type="text" class="form-control" id="InputMemo" value="{{$user->user_memo->memo}}" name='memo' >
              </div>
              
              <div class="mb-3">
                <label for="InputemployeeID" class="form-label">社員番号</label>
                <input type="text" class="form-control" value="{{$user['employee_id']}}" name='employee_id' id="InputemployeeID" required>
              </div>

              <div class="mb-3">
              権限<select class="form-select" aria-label="権限" name='group_id' id='InputGroup_id' required>
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
              部署<select class="form-select" aria-label="部署" name='department' id="InputDepartment" required>

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
                  <input type="email" class="form-control" id="InputEMail" name='email' value = "{{$user ['email']}}" required>
              </div>
                
                <div class="mb-3">
                  <label for="InputHoliday" class="form-label">有給休暇</label>
                  <input type="number" class="form-control" name='paid_holiday' id="InputHoliday" value = "{{$user ['paid_holiday']}}" required>
                </div>
                
                <div class="mb-3">
                  <label for="InputJoined" class="form-label">入社日</label>
                  <input type="text" class="form-control" name='joined_date' id="InputJoined" placeholder ='YYYY-MM-DD' value = "{{$user ['joined_date']}}" required>
                </div>

                <div class="mb-3" >
                  <label for="InputAlive" class="form-label">退社日</label>
                  <input type="text" class="form-control" name='left_date' id="InputAlive"  placeholder ='YYYY-MM-DD' value = "{{$user ['left_date']}}">
                </div>

          </form>

    </div>
@endsection
