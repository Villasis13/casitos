
@extends('layouts.dashboard_template')
@section('title','Panel Principal')
@section('content')

    @if (session()->has('status'))
        <x-alert text="{{ session('status') }}" type="success" duration="3" />
    @endif
    @if (session()->has('error'))
        <x-alert text="{{ session('error') }}" type="danger" duration="3" />
    @endif
    <div class="page-heading">

    </div>
    <script src="{{asset('assets/vendors/apexcharts/apexcharts.js')}}"></script>
    <script src="{{asset('assets/js/pages/dashboard.js')}}"></script>
@endsection
