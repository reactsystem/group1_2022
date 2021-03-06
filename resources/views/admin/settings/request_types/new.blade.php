@extends('layouts.admin')

@section('pageTitle', "システム設定")

@section('content')
    <div class="container">
        <form>
            <div class="row">
                <div class="col-md-4">
                    <h2 class="fw-bold">申請種別追加</h2>
                </div>
                <div class="col-md-8">
                    <button type="button" onclick="createHolidayData()" class="btn btn-primary float-right width-150"
                            id="saveBtn">
                        追加
                    </button>
                    <a href="/admin/settings/request-types" class="btn btn-secondary float-right mr-10px">申請種別一覧に戻る</a>
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
                    <label for="dateInput" class="form-label">名称</label>
                    <input type="text" class="form-control" id="nameInput" placeholder="名称を入力してください"
                    >
                </div>
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="status" class="form-label">タイプ</label>
                    <select class="form-select" aria-label="" id="typeName">
                        <option value="1" selected>時間指定</option>
                        <option value="2">有給消費(理由不要)</option>
                        <option value="0">理由必要</option>
                        <option value="3">理由不要</option>
                        <option value="-1">退勤時刻申請</option>
                    </select>
                </div>
                <div class="mb-3 col-md-12 col-lg-6">
                    <label for="colorName" class="form-label">カラー</label>
                    <input type="color" class="form-control" id="colorName" placeholder="カラーを選択してください"
                    >
                </div>
            </div>
        </form>
    </div>

    <script>

        function createHolidayData() {
            const _sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

            let saveBtn = document.getElementById("saveBtn")
            let textArea = document.getElementById("textArea")

            let nameInput = document.getElementById("nameInput")
            let colorName = document.getElementById("colorName")
            let typeName = document.getElementById("typeName")

            saveBtn.setAttribute("disabled", "")
            saveBtn.innerText = "追加しています"

            alert.innerHTML = ""

            axios
                .post("/admin/settings/request-types/new", {
                    name: nameInput.value,
                    color: colorName.value,
                    type: typeName.value,
                })
                .then(async (res) => {
                    const resultCode = res.data.code
                    // DISABLED - console.log("Result: " + resultCode + " / " + res.data.message)
                    if (resultCode == 0) {
                        saveBtn.className = "btn btn-success float-right width-150"
                        saveBtn.innerText = "保存しました"
                        alert.innerHTML = '<div class="alert alert-success" role="alert">' +
                            '<strong>成功</strong> - 申請種別を追加しました。編集ページに移動しています...' +
                            '</div>'
                        await _sleep(1000)
                        location = "/admin/settings/request-types/edit/" + res.data.id
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
                        saveBtn.className = "btn btn-danger float-right width-150"
                        saveBtn.innerText = "追加失敗"
                        await _sleep(2000)
                    }
                    saveBtn.removeAttribute("disabled")
                    saveBtn.className = "btn btn-primary float-right width-150"
                    saveBtn.innerText = "追加"
                })
        }
    </script>
@endsection
