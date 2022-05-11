@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="fw-bold">詳細</h2>
        <div class = "">
            <a href ="/admin/request" class = "btn btn-secondary">一覧に戻る</a>
            
            <form action ='/admin/request/approve' method = 'post'>
                @csrf
                @if($this_request->status == 0) {{-- 設定待ち--}}
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal1">承認</button>
                @elseif($this_request->status == 1) {{-- 承認済み--}}
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal1" disabled>承認</button>
                @elseif($this_request->status == 2) {{-- 却下済み--}}
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal1">承認</button>
                @elseif($this_request->status == 3) {{-- 取り消し済み--}}
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal1" disabled>承認</button>
                @else
                @endif
            <input type = 'hidden' value ={{$this_request->id}} name = id>
            <input type = 'hidden' value ={{$this_request->uuid}} name = uuid>



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
                    <input type ='submit' class="btn btn-primary" value = '承認'>
                    </div>
                </div>
                </div>
            </div>                
            </form>
            

            <form action ='/admin/request/reject' method = 'post'>
            @csrf
                
            @if($this_request->status == 0) {{-- 設定待ち--}}
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal2">却下</button>

            @elseif($this_request->status == 1) {{-- 承認済み--}}
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal2">却下</button>

            @elseif($this_request->status == 2) {{-- 却下済み--}}
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal2" disabled>却下</button>

            @elseif($this_request->status == 3) {{-- 取り消し済み--}}
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal2" disabled>却下</button>

            @else
            @endif

            <input type = 'hidden' value ={{$this_request->id}} name = id>
            <input type = 'hidden' value ={{$this_request->uuid}} name = uuid>

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

                    <input type='submit' class="btn btn-danger" value = '却下'>

                    </div>
                </div>
                </div>
            </div>
            </form>
        <hr>

            <ul>
                <li>名前：{{$this_request -> user -> name}}/社員番号({{$this_request ->id}})</li>
    
                @if($this_request -> type == 1)
                <li>期間：{{$this_request -> related_request() -> count() +1}}日({{$this_request -> time ?? "記入なし"}})</li>
                @else
                <li>期間：{{$this_request -> related_request() -> count() +1}}日</li>
                @endif
                <li>種別：{{$this_request -> request_types -> name}}</li>
                <li>状態：
                    @if($this_request -> status == 0)
                    <p class="text-primary">承認待ち</p>
                    @elseif($this_request -> status == 1)
                    <p class="text-success">承認済み</p>
                    @elseif($this_request -> status == 2)
                    <p class="text-danger">却下済み</p>
                    @elseif($this_request -> status == 3)
                    <p class="text-muted">取り消し</p>
                    @else
                    @endif
                </li>
                <div class = "reason">
                    <li>理由：{{$this_request -> reason ?? "記入なし"}}</li>
                </div>
                
            </div>
            </ul>
        </tbody>
        </table>
    </div>
@endsection
