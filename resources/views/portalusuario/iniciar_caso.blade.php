@extends('layouts.dashboard_template')
@section('title','Iniciar Caso')
@section('content')

    <div class="page-heading">
{{--        <x-navegation-view text="Lista de casos activos registrados en la categoría de {{$informacion_caso->categoria_nombre}}." />--}}

        @livewire('portalusuarios.iniciarcasos',['id_caso'=>$informacion_caso->id_caso])
    </div>

    <script src="{{asset('js/domain.js')}}"></script>

@endsection
