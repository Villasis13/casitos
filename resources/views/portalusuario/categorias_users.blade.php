@extends('layouts.dashboard_template')
@section('title','Categorías')
@section('content')

    <div class="page-heading">
{{--        <x-navegation-view text="Lista de categorías activos registrados en la carrera de {{$informacion_especialidad->especialidad_nombre}}." />--}}

        @livewire('portalusuarios.categoriasusers',['id_especialidad'=>$informacion_especialidad->id_especialidad])
    </div>

    <script src="{{asset('js/domain.js')}}"></script>

@endsection
