@extends('layouts.dashboard_template')
@section('title','Casos')
@section('content')

    <div class="page-heading">
        <x-navegation-view text="Lista de casos activos registrados en la categoría de {{$informacion_categoria->categoria_nombre}}." />

        @livewire('portalusuarios.casosusers',['id_categoria'=>$informacion_categoria->id_categoria])
    </div>

    <script src="{{asset('js/domain.js')}}"></script>

@endsection
