@extends('layouts.admin')

@section('pageTitle', "社員情報管理")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fw-bold">社員一覧</h2>
            </div>
            <div class="col-md-6">
                <a href='/admin/attends/new' class="btn btn-primary float-right mr-10px">新規登録</a>
                <button type="button" class="btn btn-secondary float-right mr-10px" data-bs-toggle="modal"
                        data-bs-target="#searchModal">
                    検索
                </button>
                <a href='/admin/attends/notify' class="btn btn-success float-right mr-10px">メッセージ送信</a>
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
        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="searchModalLabel">社員を検索</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/admin/attends" method='post'>

                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="dateInput" class="form-label">社員番号</label>
                                </div>
                                <div class="mb-3 col-sm-12 col-md-9">
                                    <input type='number' class="form-control" value='' name='id'>
                                </div>

                                <div class="col-sm-12">
                                    <label for="dateInput" class="form-label">社員名</label>
                                </div>
                                <div class="mb-3 col-sm-12 col-md-9">
                                    <input type='text' class="form-control" value='' name='name'>
                                </div>


                                <div class="col-sm-12">
                                    <label for="status" class="form-label">部署</label>
                                </div>
                                <div class="mb-3 col-sm-12 col-md-9">
                                    <select class="form-select" aria-label="部署" name='department'>
                                        <option selected value="">ここから選択</option>
                                        @foreach($departments as $item)
                                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                            <input type="submit" class="btn btn-primary" value='検索'>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <table class="table table-striped" id="sort_table">
            <tr>
                <th class="pointer-cursor" scope="col">社員番号</th>
                <th class="pointer-cursor" scope="col">社員名</th>
                <th class="pointer-cursor" scope="col">部署</th>
                <th class="pointer-cursor" scope="col">最終出勤</th>
                <th class="pointer-cursor" scope="col">月報確定</th>
                <th scope="col">操作</th>
            </tr>

            <?php $tempDate1 = new DateTime();
            $tempDate = new DateTime($tempDate1->format('Y-m-1 0:00:00'));
            $tempDate = $tempDate->modify("-1 months")?>

            @foreach($users as $user)

                @if($user->left_date != null)
                    <tr class="bg-gray2">
                @else
                    <tr>
                        @endif
                        <td>{{$user -> employee_id}}</td>
                        <td>{{$user -> name}}</td>
                        <td>{{$user -> departments -> name}}</td>
                        <td>{{$user -> latestAttemdance->date ?? ""}}</td>
                        <?php
                        /* @var $user */
                        $monthly = \App\Models\MonthlyReport::where('user_id', $user->id)->where('status', '>', 0)->orderByDesc('date')->first();
                        if($monthly != null){
                        $dateInt = intval(preg_replace("/-/", "", $monthly->date));
                        $allowInt = intval(preg_replace("/-/", "", $tempDate->format('Y-m')));

                        if($dateInt > $allowInt){ ?>
                        <td class="fw-bold text-white"
                            style="background-color: #006bb9;@if($monthly->status == 2) outline: 3px solid rgba(255, 255, 255, 0.4); outline-offset: -6px; @endif">
                            {{$monthly->date}}
                        </td>
                        <?php }else if($dateInt >= $allowInt){ ?>
                        <td class="fw-bold text-white"
                            style="background-color: #084;@if($monthly->status == 2) outline: 3px solid rgba(255, 255, 255, 0.4); outline-offset: -6px; @endif">
                            {{$monthly->date}}
                        </td>
                        <?php }else{ ?>
                        <td class="fw-bold text-white"
                            style="background-color: #c40024;@if($monthly->status == 2) outline: 3px solid rgba(255, 255, 255, 0.4); outline-offset: -6px; @endif">
                            {{$monthly->date}}
                        </td>
                        <?php }

                        } else { ?>
                        <td class="fw-bold bg-green text-white" style="background-color: #565656">
                            ---
                        </td>
                        <?php } ?>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    操作
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item"
                                           href="/admin/attends/view?id={{$user -> id}}">社員情報確認・編集</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/admin/attend-manage/calender/{{$user->id}}">勤怠情報確認</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                           href="/admin/attends/holidays/{{$user->id}}">有給データ確認・編集</a>
                                    </li>
                                    <li>
                                        <form action="/admin/request" method="post">
                                            @csrf
                                            <input type='hidden' value='{{$user -> id}}' name='id'>
                                            <button class="dropdown-item" type="submit">社員申請確認</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
        </table>
        {{$users->appends($parameters)->links()}}
    </div>

    <script>
        let column_no = 0; //今回クリックされた列番号
        let column_no_prev = 0; //前回クリックされた列番号
        window.addEventListener('load', function () {
            document.querySelectorAll('#sort_table th').forEach(elm => {
                elm.onclick = function () {
                    column_no = this.cellIndex; //クリックされた列番号
                    let table = this.parentNode.parentNode.parentNode;
                    let sortType = 0; //0:数値 1:文字
                    let sortArray = new Array; //クリックした列のデータを全て格納する配列
                    for (let r = 1; r < table.rows.length; r++) {
                        //行番号と値を配列に格納
                        let column = new Object;
                        column.row = table.rows[r];
                        column.value = table.rows[r].cells[column_no].textContent;
                        sortArray.push(column);
                        //数値判定
                        if (isNaN(Number(column.value))) {
                            sortType = 1; //値が数値変換できなかった場合は文字列ソート
                        }
                    }
                    if (sortType == 0) { //数値ソート
                        if (column_no_prev == column_no) { //同じ列が2回クリックされた場合は降順ソート
                            sortArray.sort(compareNumberDesc);
                        } else {
                            sortArray.sort(compareNumber);
                        }
                    } else { //文字列ソート
                        if (column_no_prev == column_no) { //同じ列が2回クリックされた場合は降順ソート
                            sortArray.sort(compareStringDesc);
                        } else {
                            sortArray.sort(compareString);
                        }
                    }
                    //ソート後のTRオブジェクトを順番にtbodyへ追加（移動）
                    let tbody = this.parentNode.parentNode;
                    for (let i = 0; i < sortArray.length; i++) {
                        tbody.appendChild(sortArray[i].row);
                    }
                    //昇順／降順ソート切り替えのために列番号を保存
                    if (column_no_prev == column_no) {
                        column_no_prev = -1; //降順ソート
                    } else {
                        column_no_prev = column_no;
                    }
                };
            });
        });

        //数値ソート（昇順）
        function compareNumber(a, b) {
            return a.value - b.value;
        }

        //数値ソート（降順）
        function compareNumberDesc(a, b) {
            return b.value - a.value;
        }

        //文字列ソート（昇順）
        function compareString(a, b) {
            if (a.value < b.value) {
                return -1;
            } else {
                return 1;
            }
            return 0;
        }

        //文字列ソート（降順）
        function compareStringDesc(a, b) {
            if (a.value > b.value) {
                return -1;
            } else {
                return 1;
            }
            return 0;
        }
    </script>
@endsection

