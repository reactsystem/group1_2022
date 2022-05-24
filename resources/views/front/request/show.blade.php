@extends('layouts.main')

@section('pageTitle', "各種申請")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">申請確認</h2>
            </div>
            <div class="col-md-6">
                @if($result->status === 0)
                    <button class="btn btn-danger float-right" type="button" data-bs-toggle="modal"
                            data-bs-target="#cancelModal">申請取消
                    </button>
                @else
                    <button class="btn btn-danger disabled float-right">申請取消</button>
                @endif
                <a class="btn btn-outline-secondary float-right mr-10px" href="/request">申請一覧に戻る</a>
            </div>
        </div>
        <hr>
        <div>
            <?php
            // CHECK STATUS
            $statusText = '<span style="color: #E80">●</span> <strong>申請中</strong>';
            /* @var $result */
            switch ($result->status) {
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
                <li class="list-group-item"><strong>日時: </strong>{{implode(", ", $related['date'])}}</li>
                <li class="list-group-item"><strong>ステータス: </strong>{!! $statusText !!}</li>
                <li class="list-group-item"><strong>申請種別: </strong>{{$result->name}}</li>
                @if($result->time != NULL)
                    <li class="list-group-item"><strong>労働時間: </strong>{{$result->time}}</li>
                @endif
                @if($holidays != 0)
                    <li class="list-group-item"><strong>有給消費: </strong>{{$holidays}}
                        日(残り{{\App\Models\PaidHoliday::getHolidays(Auth::id())}}日)
                    </li>
                @endif
                @if($result->reason != "")
                    <li class="list-group-item"><strong>理由: </strong>{{$result->reason}}</li>
                @endif
            </ul>
        </div>
    </div>
    @if($result->status == 0)
        <!-- Modal -->
        <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelModalLabel">申請取消</h5>
                    </div>
                    <div class="modal-body text-center">
                        この申請を取り消します。<br>よろしいですか?
                    </div>
                    <div class="modal-footer">
                        <div class="margin-0-auto">
                            <a type="button" class="btn btn-danger" href="/request/{{$result->id}}/cancel">取消実行</a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
