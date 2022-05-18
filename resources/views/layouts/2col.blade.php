@extends('layouts.app')

@section('basement')
    <div class="row" style="position: fixed; width: 100%; z-index: -100">
        <div class="col-lg-6 col-md-12 text-white"
             style="height: 100vh; background-color: #BBB;">

        </div>
        <div class="col-lg-6 bg-light text-white d-md-none d-sm-none d-none d-lg-inline" style="height: 100vh">

        </div>
    </div>
    @yield('data')
@endsection
