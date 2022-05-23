@extends('layouts.app')

@section('basement')
    <div class="row basement-row">
        <div class="col-lg-6 col-md-12 text-white height-100vh bg-gray2">
        </div>
        <div class="col-lg-6 bg-light text-white d-md-none d-sm-none d-none d-lg-inline height-100vh">
        </div>
    </div>
    @yield('data')
@endsection
