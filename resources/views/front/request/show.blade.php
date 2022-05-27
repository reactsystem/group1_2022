@extends('layouts.main')

@section('styles')
    <style>
        .card-header {
            margin-left: 100px;
            font-weight: bold;
        }
    </style>
@endsection

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
        <?php
        // CHECK STATUS
        $statusClass = 'in-progress';
        /* @var $result */
        switch ($result->status) {
            case 1:
                $statusClass = 'approved';
                break;
            case 2:
                $statusClass = 'declined';
                break;
            case 3:
                $statusClass = 'cancelled';
                break;
        }
        ?>
        <div class="mb-5 card">
            <div class="card-header {{$statusClass}}">
                <div class="row">
                    <div class="col-md-5">
                        {{$result->name}}
                    </div>
                    <div class="col-md-7 d-none d-sm-none d-md-inline-flex text-right">
                        <span class="width-100pct">
                            作成日時: {{$result->created_at}}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive col-md-4 col-lg-4 col-xl-3">
                        <table class="table">
                            <tr>
                                <th>
                                    申請日時 ({{count($related['date'])}}日)
                                </th>
                            </tr>
                            @foreach($related['date'] as $date)
                                <tr>
                                    <td>
                                        {{$date}}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="col-md-8 col-lg-8 col-xl-9">
                        <ul class="padding-0">
                            @if($result->time != NULL)
                                <li class="list-group-item">
                                    @if($result->type_int == -1)
                                        <strong>退勤時刻: </strong>
                                    @else
                                        <strong>労働時間: </strong>
                                    @endif
                                    {{$result->time}}
                                </li>
                            @endif
                            @if($holidays != 0)
                                <li class="list-group-item"><strong>有給消費: </strong>{{$holidays}}
                                    日(残り{{\App\Models\PaidHoliday::getHolidays(Auth::id())}}日)
                                </li>
                            @endif
                        </ul>
                        @if($result->reason != "")
                            <label for="reasonText" class="form-label">
                                <strong>理由</strong>
                            </label>
                            <textarea id="reasonText" class="form-control" readonly>{{$result->reason}}</textarea>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <span class="width-100pct">
                            最終更新: {{$result->updated_at}}
                        </span>
                    </div>
                </div>
            </div>
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
