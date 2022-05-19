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
@section('pageTitle', "社員情報管理")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h2 class="fw-bold">有給データ編集</h2>
            </div>
            <div class="col-md-8">
                <button type="button" onclick="saveData()" class="btn btn-primary"
                        style="float: right; width: 150px;"
                        id="saveBtn">
                    保存
                </button>
                {{--
                <button class="btn btn-danger"
                        style="float: right; margin-right: 10px;" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    削除
                </button>
                --}}
                <a href="/admin/attends/holidays/{{$user_id}}" class="btn btn-secondary"
                   style="float: right; margin-right: 10px;">有給一覧に戻る</a>
            </div>
            <div class="col-md-12 mt-3" id="alert">
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
                <label for="amountInput" class="form-label">残日数</label>
                <input type="number" class="form-control" id="amountInput" placeholder="日数を入力してください" min="0"
                       value="{{$data->amount}}"
                >
            </div>
            <div class="mb-3 col-md-12 col-lg-6">
                <label for="createInput" class="form-label">付与日</label>
                <input type="date" class="form-control" id="createInput" placeholder="付与日を入力してください" min="0"
                       value="{{$data->created_at->format('Y-m-d')}}"
                >
            </div>
        </div>
    </div>
    {{--

        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">削除確認</h5>
                    </div>
                    <div class="modal-body">
                        有給を削除してもよろしいですか?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                        <a href="/admin/settings/department/delete/{{$id}}" type="button" class="btn btn-danger">削除</a>
                    </div>
                </div>
            </div>
        </div>
        --}}

    <script>
        let amountInput = document.getElementById("amountInput")
        let createInput = document.getElementById("createInput")
        let alert = document.getElementById("alert")

        function saveData() {
            const _sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

            let saveBtn = document.getElementById("saveBtn")

            saveBtn.setAttribute("disabled", "")
            saveBtn.innerText = "保存しています"

            alert.innerHTML = ""

            axios
                .post("/admin/attends/holidays/{{$user_id}}/edit/{{$id}}", {
                    amount: amountInput.value,
                    created: createInput.value,
                })
                .then(async (res) => {
                    const resultCode = res.data.code
                    console.log("Result: " + resultCode + " / " + res.data.message)
                    if (resultCode == 0) {
                        saveBtn.className = "btn btn-success"
                        saveBtn.innerText = "保存しました"
                        alert.innerHTML = '<div class="alert alert-success" role="alert">' +
                            '<strong>成功</strong> - 保存が完了しました。' +
                            '</div>'
                        await _sleep(1000)
                    } else {
                        let alertStr = '<div class="alert alert-danger" role="alert">' +
                            '<strong>エラー</strong> - ' +
                            res.data.message + '<br>'
                        if (resultCode === 1) {
                            Object.keys(res.data.errors).forEach(key => {
                                alertStr += res.data.errors[key] + '<br>'
                            });
                        }
                        alertStr += '</div>';
                        alert.innerHTML = alertStr
                        saveBtn.className = "btn btn-danger"
                        saveBtn.innerText = "保存失敗"
                        await _sleep(2000)
                    }
                    saveBtn.removeAttribute("disabled")
                    saveBtn.className = "btn btn-primary"
                    saveBtn.innerText = "保存"
                })
        }
    </script>
@endsection
