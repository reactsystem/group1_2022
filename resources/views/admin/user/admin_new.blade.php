@extends('layouts.admin')

@section('pageTitle', "社員情報管理")

@section('content')
  <div class="container mb-5">
    <div class="row">
      <div class="col-md-6">
        <h2 class="fw-bold">社員情報登録</h2>
      </div>
      <div class="col-md-6">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal"
          style="float: right;">登録
        </button>
        <a href='/admin/attends' class="btn btn-secondary" style="float: right; margin-right: 10px;">戻る</a>
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
  <form action='/admin/attends/add' method='POST'>
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
          登録しますか？
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
            <button type="submit" class="btn btn-primary">登録</button>
          </div>
        </div>
      </div>
    </div>


    <div class="container">
      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="InputName" class="form-label">名前</label>
            <input type="text" class="form-control" id="InputName" name = 'name' required>
          </div>
        </div>
        <div class="col">
          <div class="mb-3">
            <label for="InputemployeeID" class="form-label">社員番号</label>
            <input type="number" class="form-control" id="InputemployeeID" name='employee_id' required>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <div class="mb-3">
            権限<select class="form-select" aria-label="権限" name='group_id' required>
                <option selected value="">ここから選択</option>
                <option value="0">一般ユーザー</option>
                <option value="1">管理者</option>
            </select>
          </div>
        </div>
        <div class="col">
          <div class="mb-3">
            部署
            <select class="form-select" aria-label="部署" name='department' required>
                <option selected value="">ここから選択</option>
              @foreach($departments as $item)
                <option value="{{$item['id']}}">{{$item['name']}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="InputMemo" class="form-label">社員メモ</label>
            <input type="text" class="form-control" value = '' id="InputMemo" name='memo' >
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="InputPassword" class="form-label">パスワード</label>
            <input type="password" class="form-control" id="InputPassword" name='password' required>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">
            <div class="mb-3">
                <label for="InputEMail" class="form-label">メールアドレス</label>
                <input type="email" class="form-control" id="InputEMail" name='email' required>
            </div>
        </div>
      </div>

        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label for="InputJoined" class="form-label">入社日</label>
                    <input type="date" class="form-control" id="InputJoined" placeholder='YYYY-MM-DD' name='joined_date'
                           required>
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label for="InputHoliday" class="form-label">有給休暇</label>
                    <input type="number" min = "0" step="1" class="form-control" id="InputHoliday" name='paid_holiday' required>
                </div>
            </div>
        </div>
  </form>
  </div>
@endsection
