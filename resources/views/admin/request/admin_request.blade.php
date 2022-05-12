@extends('layouts.admin')

@section('content')
        @if (session('error_message'))
        <div class="col-md-12 mt-3">
            <div class="alert alert-danger" role="alert">
                <strong>エラー</strong> {{ session('error_message') }}
            </div>
        </div>
        @endif
        @section('content')
        @if (session('flash_message'))
        <div class="col-md-12 mt-3">
            <div class="alert alert-success" role="alert">
                {{ session('flash_message') }}
            </div>
        </div>
        @endif

        <h2 class="fw-bold">申請一覧</h2>
    
        <hr>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchModal">検索</button>

        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="searchModalLabel">各種申請を検索</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action ="/admin/request" method = 'post'>
                        @csrf
                        <div class="modal-body">    
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="dateInput" class="form-label">社員</label>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-9">
                                <select class="form-select" aria-label="" id = 'user' name="id">
                                    <option value="">指定なし</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{sprintf("%03d", $user->employee_id)}}
                                            / {{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-3">
                            </div>
                            <div class="col-sm-12">
                                <label for="dateInput" class="form-label">日付</label>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-9">
                                <input type="date" class="form-control" id="dateInput" name="dateInput" placeholder="XXXX-XX-XX">
                            </div>
                            <div class="mb-3 col-sm-12 col-md-3">
                                <button type="button" class="btn btn-secondary" onclick="clearDate()">
                                    クリア
                                </button>
                            </div>
                            <div class="col-sm-12">
                                <label for="status" class="form-label">状態</label>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-9">
                                <select class="form-select" aria-label="" id="status" name = 'status'>
                                    <option value="" selected>指定しない</option>
                                    <option value="0">承認待ち</option>
                                    <option value="1">承認済み</option>
                                    <option value="2">却下済み</option>
                                    <option value="3">取り消し済み</option>
    
                                </select>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-3">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                        <input type="submit" class="btn btn-primary" value = '検索'>
                    
                    </div>
                </form>
                </div>
            </div>
        </div>

        <a href='' class="btn btn-primary">追加</a>

        
        <table class="table">
            <thead>
            <tr>
            <th scope="col">社員名</th>
            <th scope="col">期間(時間)</th>
            <th scope="col">申請種別</th>
            <th scope="col">理由</th>
            <th scope="col">状態</th>
            <th scope="col">クイックアクション</th>
            </tr>
        </thead>
        <tbody>
            
            
            @foreach($all_requests as $request)
            <tr>
                @if($request -> related_id != NULL)
                    @php
                        $request = $request ->pair_request();
                    @endphp
                @endif
                
                <td>{{$request -> user -> name}}</td>
                @if($request -> type == 1)
                <td>{{$request -> related_request() -> count() +1}}日<br>                
                    ({{$request -> time ?? "記入なし"}})
                @else
                <td>{{$request -> related_request() -> count() +1}}
                日
                @endif
                </td>
                <td>{{$request -> request_types -> name}}</td>
                <td>
                    <div class="overflow-auto" style = "max-height: 48px; max-width: 260px">
                        {{$request -> reason ?? "記入なし"}}
                    </div>
                </td>
                <td>
                    @if($request -> status == 0)
                    <p class="text-primary fw-bold">承認待ち</p>
                    @elseif($request -> status == 1)
                    <p class="text-success">承認済み</p>
                    @elseif($request -> status == 2)
                    <p class="text-danger">却下済み</p>
                    @elseif($request -> status == 3)
                    <p class="text-muted">取り消し</p>
                    @else
                    @endif
                </td>
                <td>    
                <a href="/admin/request/detail?id={{$request ->id}}" class="btn btn-secondary">詳細</a>   

                @if($request->status == 0) {{-- 設定待ち--}}
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal-{{$request->id}}-1" onclick="getModal({{$request ->id}},'{{$request ->uuid}}', 1)">承認</button>
                @elseif($request->status == 1) {{-- 承認済み--}}
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal-{{$request->id}}-1" onclick="getModal({{$request ->id}},'{{$request ->uuid}}', 1)" disabled>承認</button>
                @elseif($request->status == 2) {{-- 却下済み--}}
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal-{{$request->id}}-1" onclick="getModal({{$request ->id}},'{{$request ->uuid}}', 1)" >承認</button>
                @elseif($request->status == 3) {{-- 取り消し済み--}}
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal-{{$request->id}}-1" onclick="getModal({{$request ->id}},'{{$request ->uuid}}', 1)" disabled>承認</button>
                @else
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal-{{$request->id}}-1" onclick="getModal({{$request ->id}},'{{$request ->uuid}}', 1)">承認</button>
                
                @endif

                @if($request->status == 0) {{-- 設定待ち--}}
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal-{{$request->id}}-2" onclick="getModal({{$request ->id}},'{{$request ->uuid}}', 2)">却下</button>
                @elseif($request->status == 1) {{-- 承認済み--}}
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal-{{$request->id}}-2" onclick="getModal({{$request ->id}},'{{$request ->uuid}}', 2)">却下</button>
                @elseif($request->status == 2) {{-- 却下済み--}}
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal-{{$request->id}}-2" onclick="getModal({{$request ->id}},'{{$request ->uuid}}', 2)" disabled>却下</button>
                @elseif($request->status == 3) {{-- 取り消し済み--}}
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal-{{$request->id}}-2" onclick="getModal({{$request ->id}},'{{$request ->uuid}}', 2)" disabled>却下</button>
                @else
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal-{{$request->id}}-2" onclick="getModal({{$request ->id}},'{{$request ->uuid}}', 2)">却下</button>
                @endif

                {{-- モーダル　--}}
                <div class="modal fade" id="Modal-{{$request->id}}-1" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">確認</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            承認しますか？
                        </div>
                        <div class="modal-footer">
                        <form action ="/admin/request/approve" method = 'post'>
                            @csrf
                            <input type = 'hidden' id = 'id-{{$request->id}}-1' name = id>
                            <input type = 'hidden' id ='uuid-{{$request->id}}-1' name = uuid>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                        <input type ='submit' class="btn btn-primary" value = "承認">

                        </form>
                        </div>
                    </div>
                    </div>
                </div>  

                <div class="modal fade" id="Modal-{{$request->id}}-2" aria-labelledby="ModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">確認</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            却下しますか？
                        </div>
                        <div class="modal-footer">
                        <form action ="/admin/request/reject" method = 'post'>
                            @csrf
                            <input type = 'hidden' id = 'id-{{$request->id}}-2' name = id>
                            <input type = 'hidden' id ='uuid-{{$request->id}}-2' name = uuid>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                        <input type ='submit' class="btn btn-danger" value = "却下">

                        </form>
                        </div>
                    </div>
                    </div>
                </div>  

                </td>
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>
    
    <!-- Modal -->
    
            
    <script defer>
    let submitBtn = document.getElementById("submitBtn")
    let user = document.getElementById("user")
    let dateInput = document.getElementById("dateInput")
    let status = document.getElementById("status")
    let userInputFilled = false
    let dateInputFilled = false
    let statusInputFilled = false
    user.onchange = function () {
        userInputFilled = user.value > 0;
        checkData();
    }
    dateInput.onchange = function () {
        dateInputFilled = dateInput.value != null && dateInput.value !== "";
        checkData();
    }
    status.onchange = function () {
        statusInputFilled = status.value >= 0;
        checkData();
    }
    function checkData() {
        if (userInputFilled || dateInputFilled || statusInputFilled) {
            submitBtn.removeAttribute("disabled")
            return true
        } else {
            submitBtn.setAttribute("disabled", "")
            return false
        }
    }
    function clearDate() {
        dateInput.value = ""
        dateInputFilled = false
    }
    function searchAttendData() {
        const _sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));
        if (!checkData()) {
            console.log("Failed")
            return
        }
        let keywords = []
        if (user.value > 0) {
            keywords.push('user=' + user.value)
        }
        if (dateInput.value != null && dateInput.value !== "") {
            keywords.push('date=' + dateInput.value)
        }
        if (status.value >= 0) {
            keywords.push('status=' + status.value)
        }
        console.log("URL: " + "/admin/request/search?" + keywords.join("&"))
        jump("/admin/request/search?" + keywords.join("&"))
    }

    function getModal(id,uuid, mode) {
        let idElement = document.getElementById('id-'+id+'-'+mode)
        let uuidElement = document.getElementById('uuid-'+id+'-'+mode)
        idElement.value = id
        uuidElement.value = uuid
    }
    </script>
@endsection