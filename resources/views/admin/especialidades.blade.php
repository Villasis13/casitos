@extends('layouts.dashboard_template')
@section('title','Especialidades')
@section('content')

    <div class="page-heading">
        <x-navegation-view text="Lista de especialidades activos registrados en la carrera de {{$informacion_carrera->carrera_nombre}}." />

        @livewire('admin.especialidades',['id_carrera'=>$informacion_carrera->id_carrera])
    </div>

    <script src="{{asset('js/domain.js')}}"></script>

@endsection
