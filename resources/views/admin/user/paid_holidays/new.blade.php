@extends('layouts.admin')

@section('pageTitle', "社員情報管理")

@section('content')
    <div class="container">
        <form>
            <div class="row">
                <div class="col-md-4">
                    <h2 class="fw-bold">有給追加</h2>
                </div>
                <div class="col-md-8">
                    <button type="button" onclick="createHolidayData()" class="btn btn-primary float-right width-150"
                            id="saveBtn">
                        追加
                    </button>
                    <a href="/admin/attends/holidays/{{$user_id}}"
                       class="btn btn-secondary float-right mr-10px">有給一覧に戻る</a>
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
                    <input type="number" class="form-control" id="amountInput" placeholder="日数を入力してください" min="0">
                </div>
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="createInput" class="form-label">付与日</label>
                    <input type="date" class="form-control" id="createInput" placeholder="付与日を入力してください">
                </div>
            </div>
        </form>
    </div>

    <script>

        function createHolidayData() {
            const _sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

            let saveBtn = document.getElementById("saveBtn")
            let textArea = document.getElementById("textArea")

            let alert = document.getElementById("alert")
            let amountInput = document.getElementById("amountInput")
            let createInput = document.getElementById("createInput")

            saveBtn.setAttribute("disabled", "")
            saveBtn.innerText = "追加しています"

            alert.innerHTML = ""

            axios
                .post("/admin/attends/holidays/{{$user_id}}/new", {
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
                            '<strong>成功</strong> - 有給を追加しました。編集ページに移動しています...' +
                            '</div>'
                        await _sleep(1000)
                        location = "/admin/attends/holidays/{{$user_id}}/edit/" + res.data.id
                        return
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
                        saveBtn.innerText = "追加失敗"
                        await _sleep(2000)
                    }
                    saveBtn.removeAttribute("disabled")
                    saveBtn.className = "btn btn-primary"
                    saveBtn.innerText = "追加"
                })
        }
    </script>
@endsection
