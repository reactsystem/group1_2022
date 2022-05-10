@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="fw-bold">詳細</h2>
        <div class = "">
            <a href ="/admin/request" class = "btn btn-secondary">一覧に戻る</a>
            
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal1">
                承認
            </button>

            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal2">
                却下
            </button>
        <hr>

        <ul>
                <li>名前：{{$this_request -> user -> name}}/社員番号({{$this_request ->id}})</li>
    
                @if($this_request -> type == 1)
                <li>期間：{{$this_request -> related_request() -> count() +1}}日({{$this_request -> time ?? "記入なし"}})</li>
                @else
                <li>期間：{{$this_request -> related_request() -> count() +1}}日</li>
                @endif
                <li>種別：{{$this_request -> request_types -> name}}</li>
                
                <div class = "reason">
                    <li>理由：{{$this_request -> reason ?? "記入なし"}}</li>
                </div>
                
            </div>
            </ul>
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

        {{--submitにしないと動かない--}}
        <a herf ='/admin/request/approve?id={{$request->id}}' type='botton' class="btn btn-primary">承認</a>
        
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

            {{--submitにしないと動かない--}}
    <a herf ='/admin/request/reject?id={{$request->id}}' type='botton' class="btn btn-danger">却下</a>
        </div>
    </div>
    </div>
</div>