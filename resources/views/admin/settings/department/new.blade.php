@extends('layouts.admin')

@section('pageTitle', "システム設定")

@section('content')
    <div class="container">
        <form>
            <div class="row">
                <div class="col-md-4">
                    <h2 class="fw-bold">部署追加</h2>
                </div>
                <div class="col-md-8">
                    <button type="button" onclick="createHolidayData()" class="btn btn-primary float-right width-150"
                            id="saveBtn">
                        追加
                    </button>
                    <a href="/admin/settings/department" class="btn btn-secondary float-right mr-10px">部署一覧に戻る</a>
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
            </div>
        </form>
    </div>

    <script>

        function createHolidayData() {
            const _sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

            let saveBtn = document.getElementById("saveBtn")
            let textArea = document.getElementById("textArea")

            let nameInput = document.getElementById("nameInput")

            saveBtn.setAttribute("disabled", "")
            saveBtn.innerText = "追加しています"

            alert.innerHTML = ""

            axios
                .post("/admin/settings/department/new", {
                    name: nameInput.value,
                })
                .then(async (res) => {
                    const resultCode = res.data.code
                    // DISABLED - console.log("Result: " + resultCode + " / " + res.data.message)
                    if (resultCode == 0) {
                        saveBtn.className = "btn btn-success float-right width-150"
                        saveBtn.innerText = "保存しました"
                        alert.innerHTML = '<div class="alert alert-success" role="alert">' +
                            '<strong>成功</strong> - 部署を追加しました。編集ページに移動しています...' +
                            '</div>'
                        await _sleep(1000)
                        location = "/admin/settings/department/edit/" + res.data.id
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
