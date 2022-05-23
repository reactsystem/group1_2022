@extends('layouts.admin')

@section('pageTitle', "システム設定")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h2 class="fw-bold">部署編集</h2>
            </div>
            <div class="col-md-8">
                <button type="button" onclick="saveData()" class="btn btn-primary float-right width-150"
                        id="saveBtn">
                    保存
                </button>
                <button class="btn btn-danger float-right mr-10px" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    削除
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
                <label for="departmentName" class="form-label">名称</label>
                <input type="text" class="form-control" id="departmentName" placeholder="名称を入力してください"
                       value="{{$data->name}}"
                >
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">削除確認</h5>
                </div>
                <div class="modal-body">
                    部署を削除してもよろしいですか?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <a href="/admin/settings/department/delete/{{$id}}" type="button" class="btn btn-danger">削除</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        let departmentName = document.getElementById("departmentName")
        let alert = document.getElementById("alert")

        function saveData() {
            const _sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

            let saveBtn = document.getElementById("saveBtn")

            saveBtn.setAttribute("disabled", "")
            saveBtn.innerText = "保存しています"

            alert.innerHTML = ""

            axios
                .post("/admin/settings/department/edit/{{$id}}", {
                    name: departmentName.value,
                })
                .then(async (res) => {
                    const resultCode = res.data.code
                    console.log("Result: " + resultCode + " / " + res.data.message)
                    if (resultCode == 0) {
                        saveBtn.className = "btn btn-success float-right width-150"
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
                        saveBtn.className = "btn btn-danger float-right width-150"
                        saveBtn.innerText = "保存失敗"
                        await _sleep(2000)
                    }
                    saveBtn.removeAttribute("disabled")
                    saveBtn.className = "btn btn-primary float-right width-150"
                    saveBtn.innerText = "保存"
                })
        }
    </script>
@endsection
