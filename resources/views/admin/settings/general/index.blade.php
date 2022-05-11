@extends('layouts.admin')

@section('styles')
    <style>
        .attends-row {
            transition-duration: 0.2s;
            cursor: pointer;
        }

        .attends-row:hover {
            transition-duration: 0.05s;
            box-shadow: 0 0 10px #999;
            background-color: #0b5ed7;
            color: #fff;
        }
    </style>
@endsection
@section('pageTitle', "システム設定")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">各種情報確認</h2>
            </div>
            <div class="col-md-6">
                <a href="/admin/settings/general/edit" class="btn btn-primary" style="float: right">編集</a>
                <a href="/admin/settings" class="btn btn-secondary"
                   style="float: right; margin-right: 10px">システム設定に戻る</a>
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
        <div class="row">
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="startInput" class="form-label">始業時刻</label>
                <input type="time" class="form-control" id="startInput" placeholder="未設定" value="{{$data->start ?? ""}}"
                       disabled>
            </div>
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="endInput" class="form-label">終業時刻</label>
                <input type="time" class="form-control" id="endInput" placeholder="未設定" value="{{$data->end ?? ""}}"
                       disabled>
            </div>
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="startInput" class="form-label">休憩(標準)</label>
                <input type="time" class="form-control" id="startInput" placeholder="未設定"
                       value="{{$data->rest ?? "00:45"}}"
                       disabled>
            </div>
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="endInput" class="form-label">休憩(残業)</label>
                <input type="time" class="form-control" id="endInput" placeholder="未設定"
                       value="{{$data->rest_over ?? "00:15"}}"
                       disabled>
            </div>
            <div class="mb-3 col-md-12">
                <div class="card" style="width: 100%;">
                    <div class="card-header">
                        有給設定
                    </div>
                    <div class="card-body" style="height: 400px; overflow: auto">
                        <table class="table">
                            <tr>
                                <th>
                                    経過年数
                                </th>
                                <th>
                                    付与日数
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    0.5年
                                </td>
                                <td>
                                    10日
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.5年
                                </td>
                                <td>
                                    12日
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    0.5年
                                </td>
                                <td>
                                    10日
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.5年
                                </td>
                                <td>
                                    12日
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    0.5年
                                </td>
                                <td>
                                    10日
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.5年
                                </td>
                                <td>
                                    12日
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    0.5年
                                </td>
                                <td>
                                    10日
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.5年
                                </td>
                                <td>
                                    12日
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    0.5年
                                </td>
                                <td>
                                    10日
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.5年
                                </td>
                                <td>
                                    12日
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    0.5年
                                </td>
                                <td>
                                    10日
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.5年
                                </td>
                                <td>
                                    12日
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
