@extends('layouts.dashboard_template')
@section('title','Carreras')
@section('content')

    <div class="page-heading">
        <x-navegation-view text="Lista de carreras activas registrados en el sistema." />

        @livewire('admin.carreras')
    </div>

    <script src="{{asset('js/domain.js')}}"></script>

@endsection
