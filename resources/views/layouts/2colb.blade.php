@extends('layouts.app')

@section('basement')
    <div class="row" style="position: fixed; width: 100%; z-index: -100">
        <div class="col-md-6 text-white" style="height: 100vh; background-color: #222;">

        </div>
        <div class="col-md-6 bg-light text-white" style="height: 100vh">

        </div>
    </div>
    @yield('data')
@endsection
