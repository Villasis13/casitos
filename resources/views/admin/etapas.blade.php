@extends('layouts.dashboard_template')
@section('title','Etapas')
@section('content')

    <div class="page-heading">
        <x-navegation-view text="Lista de etapas activos registrados en el caso de {{$informacion_caso->caso_titulo}}." />

        @livewire('admin.etapas',['id_caso'=>$informacion_caso->id_caso])
    </div>

    <script src="{{asset('js/domain.js')}}"></script>

@endsection
