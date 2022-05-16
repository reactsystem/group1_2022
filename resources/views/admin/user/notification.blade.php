@extends('layouts.admin')

@section('pageTitle', "社員情報管理")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">メッセージ送信</h2>
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-primary" style="float: right" data-bs-toggle="modal"
                        data-bs-target="#Modal">
                    送信
                </button>
                <a href='/admin/attends' class="btn btn-secondary" style="float: right; margin-right: 10px;">社員一覧へ戻る</a>
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


        <form action='/admin/attends/notify' method='POST'>
            @csrf

            {{--モーダル--}}
            <div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">送信確認</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            メッセージを送信してもよろしいですか？
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
                            <button type="submit" class="btn btn-primary">送信実行</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm">
                <label for="userInput" class="form-label">社員</label>
                <select class="form-select" aria-label="" id="userInput" name="user_id" required>
                    <option value="0">指定してください</option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{sprintf("%03d", $user->employee_id)}}
                            / {{$user->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-sm mt-3">
                <label for="titleInput" class="form-label">タイトル</label>
                <input id="titleInput" name="title" class="form-control">
            </div>

            <div class="col-sm mt-3">
                <label for="messageInput" class="form-label">メッセージ</label>
                <textarea id="messageInput" name="data" class="form-control"></textarea>
            </div>

            <div class="col-sm mt-3">
                <label for="linkInput" class="form-label">URL</label>
                <input id="linkInput" name="url" class="form-control">
            </div>


        </form>

    </div>
@endsection
