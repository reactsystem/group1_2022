@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">申請詳細</h2>
            </div>
            <div class="col-md-6">

                {{-- 承認ボタン --}}
                <form action='/admin/request/approve' method='post' class="float-right">
                    @csrf
                    @if($this_request->status == 0) {{-- 設定待ち--}}
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal1">承認
                    </button>
                    @elseif($this_request->status == 1) {{-- 承認済み--}}
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal1"
                            disabled>承認
                    </button>
                    @elseif($this_request->status == 2) {{-- 却下済み--}}
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal1">承認
                    </button>
                    @elseif($this_request->status == 3) {{-- 取り消し済み--}}
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal1"
                            disabled>承認
                    </button>
                    @else
                    @endif


                    <input type='hidden' value='{{$this_request->id}}' name='id'>
                    <input type='hidden' value='{{$this_request->uuid}}' name='uuid'>

                    {{--modal--}}
                    <div class="modal fade" id="Modal1" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="ModalLabel">確認</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    承認しますか？
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル
                                    </button>
                                    <input type='submit' class="btn btn-primary" value='承認'>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- 却下ボタン --}}
                <form action='/admin/request/reject' method='post' class="float-right mr-10px">
                    @csrf

                    @if($this_request->status == 0) {{-- 設定待ち--}}
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal2">却下
                    </button>

                    @elseif($this_request->status == 1) {{-- 承認済み--}}
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal2">却下
                    </button>

                    @elseif($this_request->status == 2) {{-- 却下済み--}}
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal2"
                            disabled>却下
                    </button>

                    @elseif($this_request->status == 3) {{-- 取り消し済み--}}
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal2"
                            disabled>却下
                    </button>

                    @else
                    @endif

                    <input type='hidden' value='{{$this_request->id}}' name='id'>
                    <input type='hidden' value='{{$this_request->uuid}}' name='uuid'>

                    {{--modal--}}
                    <div class="modal fade" id="Modal2" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="ModalLabel">確認</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    却下しますか？
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル
                                    </button>

                                    <input type='submit' class="btn btn-danger" value='却下'>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- 一覧に戻るボタン --}}
                <a href="/admin/request" class="btn btn-secondary float-right mr-10px">一覧に戻る</a>
            </div>
        </div>


        <hr>
        <?php
        // CHECK STATUS
        $statusText = '<span style="color: #E80">●</span> <strong>申請中</strong>';
        /* @var $this_request */
        switch ($this_request->status) {
            case 1:
                $statusText = '<span style="color: #0E0">●</span> <strong>承認</strong>';
                break;
            case 2:
                $statusText = '<span style="color: #E00">●</span> <strong>却下</strong>';
                break;
            case 3:
                $statusText = '<span style="color: #AAA">●</span> <strong>取消</strong>';
                break;
        }
        ?>
        <ul class="list-group">
            <li class="list-group-item"><div><strong>社員ID: </strong>{{$this_request->user->employee_id}}</div></li>

            <li class="list-group-item"><div><strong>社員名: </strong>{{$this_request->user->name}}</div></li>

            @if($this_request -> type == 1)
            <li class="list-group-item"><div><strong>期間：</strong>{{$this_request -> related_request() -> count() +1}}
                    日({{$this_request -> time ?? "記入なし"}})
                </div></li>
            @else
            <li class="list-group-item"><div><strong>期間：</strong>{{$this_request -> related_request() -> count() +1}}日</div></li>
            @endif

            <li class="list-group-item"><div><strong>日付：</strong>{{$this_request['date']}}
                @foreach($related_request as $request)

                    {{' , '.$request['date']}}
                @endforeach
            </div></li>

            <li class="list-group-item"><div><strong>ステータス: </strong>{!! $statusText !!}</div></li>

            <li class="list-group-item"><div><strong>申請種別: </strong>{{$this_request->request_types->name}}</div></li>
            @if($this_request->time != NULL)
            <li class="list-group-item"><div><strong>労働時間: </strong>{{$this_request->time}}</div></li>
            @endif

            @if($this_request->reason != "")
                <li class="list-group-item"><strong>理由: </strong>
                    <div class="overflow-auto">
                        {{$this_request->reason ?? "記入なし"}}
                    </div>
                </li>
            @endif
        </ul>
    </div>
@endsection
