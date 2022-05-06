@extends('layouts.main')

@section('pageTitle', "申請確認")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">申請確認</h2>
            </div>
            <div class="col-md-6">
                @if($result->status === 0)
                    <button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#cancelModal"
                            style="float: right; ">申請取消
                    </button>
                @else
                    <button class="btn btn-danger disabled" style="float: right;">申請取消</button>
                @endif
                <a class="btn btn-outline-secondary" href="/request"
                   style="float: right; margin-right: 10px">申請一覧に戻る</a>
            </div>
        </div>
        <hr>
        <div style="font-size: 14pt">
            <?php
            // CHECK STATUS
            $statusText = '<span style="color: #E80">●</span> 申請中';
            switch ($result->status) {
                case 1:
                    $statusText = '<span style="color: #0E0">●</span> 承認';
                    break;
                case 2:
                    $statusText = '<span style="color: #E00">●</span> 却下';
                    break;
                case 3:
                    $statusText = '<span style="color: #AAA">●</span> 取消';
                    break;
            }
            ?>
            <div><strong>日時: </strong>{{implode(", ", $related['date'])}}</div>
            <div><strong>ステータス: </strong>{!! $statusText !!}</div>
            <div><strong>申請種別: </strong>{{$result->name}}</div>
            @if($holidays != 0)
                <div><strong>有給消費: </strong>{{$holidays}}日(残り{{Auth::user()->paid_holiday}}日)</div>
            @endif
            @if($result->reason != "")
                <div><strong>理由: </strong>{{$result->reason}}</div>
            @endif
        </div>
    </div>
    @if($result->status == 0)
        <!-- Modal -->
        <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelModalLabel">申請取り消し</h5>
                    </div>
                    <div class="modal-body text-center">
                        この申請を取り消します。<br>よろしいですか?
                    </div>
                    <div class="modal-footer">
                        <div style="margin: 0 auto;">
                            <a type="button" class="btn btn-danger" href="/request/{{$result->id}}/cancel">取り消し</a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
