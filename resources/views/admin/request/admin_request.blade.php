@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="fw-bold">申請一覧</h2>
    
        <hr>
        <a href='' class="btn btn-primary">検索</a>
        <a href='' class="btn btn-primary">追加</a>

        
        <table class="table">
            <thead>
            <tr>
            <th scope="col">社員名</th>
            <th scope="col">期間</th>
            <th scope="col">申請種別</th>
            <th scope="col">理由</th>
            <th scope="col">クイックアクション</th>
            </tr>
        </thead>
        <tbody>
            
        {{--<pre>{{var_dump($requests)}}  </pre>--}}
            
            @foreach($all_requests as $request)
            <tr>
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
                <a href="#?id={{$request ->id}}" class="btn btn-secondary">詳細</a>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal1">
                    承認
                </button>

                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal2">
                    却下
                </button>
                </td>
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>
@endsection

<div class="modal fade" id="Modal1" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
        <button type="submit" class="btn btn-primary">承認</button>
        </div>
    </div>
    </div>
</div>

<div class="modal fade" id="Modal2" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
        <button type="submit" class="btn btn-primary">却下</button>
        </div>
    </div>
    </div>
</div>