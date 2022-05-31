@extends('layouts.admin')

@section('pageTitle', "システム設定")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">各種情報確認</h2>
            </div>
            <div class="col-md-6">
                <a href="/admin/settings/general/edit" class="btn btn-primary float-right">編集</a>
                <a href="/admin/settings/general/update" class="btn btn-success float-right mr-10px">祝日データ更新</a>
                <a href="/admin/settings" class="btn btn-secondary float-right mr-10px">システム設定に戻る</a>
            </div>
            <div class="col-md-12">
                {!! $holidaysUpdate !!}
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
                <label for="startInput" class="form-label">休憩時間初期値</label>
                <input type="time" class="form-control" id="startInput" placeholder="未設定"
                       value="{{$data->rest ?? "00:45"}}"
                       disabled>
            </div>
            {{--
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="endInput" class="form-label">休憩(残業)</label>
                <input type="time" class="form-control" id="endInput" placeholder="未設定"
                       value="{{$data->rest_over ?? "00:15"}}"
                       disabled>
            </div>
            --}}
            <div class="mb-3 col-md-12">
                <div class="card width-100pct">
                    <div class="card-header">
                        有給設定
                    </div>
                    <div class="card-body overflow-auto height-400">
                        @if(count($configArray ?? []) != 0)
                            <table class="table table-striped">
                                <tr>
                                    <th>
                                        経過月数
                                    </th>
                                    <th>
                                        付与日数
                                    </th>
                                </tr>
                                @foreach($configArray as $index => $item)
                                    @if($item == "")
                                        @continue
                                    @endif
                                    <?php
                                    /* @var $item */
                                    $dat = preg_split("/,/", $item);?>
                                    @if($index != 0)
                                        <tr>
                                            <td>
                                                {{$dat[0]}}ヵ月
                                            </td>
                                            <td>
                                                {{$dat[1]}}日
                                            </td>
                                        </tr>
                                        @endif
                                @endforeach
                            </table>
                        @else
                            <p>有給設定ファイルがインポートされていません。<br>編集画面でデフォルト設定ファイルを再ダウンロードできます。</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="mb-3 col-md-12">
                <div class="card width-100pct">
                    <div class="card-header">
                        祝日データ
                    </div>
                    <div class="card-body overflow-auto height-400">
                        @if(count($configArray ?? []) != 0)
                            <table class="table table-striped">
                                <tr>
                                    <th>
                                        日時
                                    </th>
                                    <th>
                                        名称
                                    </th>
                                </tr>
                                @foreach($holidaysArray as $index => $item)
                                    @if($item == "")
                                        @continue
                                    @endif
                                    <?php
                                    /* @var $item */
                                    $dat = preg_split("/,/", $item);?>
                                    @if($index != 0)
                                        <tr>
                                            <td>
                                                {{$dat[0]}}
                                            </td>
                                            <td>
                                                {{$dat[1]}}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        @else
                            <p>祝日データがありません。<br>祝日データ更新ボタンを押してください。</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
