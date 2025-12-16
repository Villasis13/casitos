@extends('layouts.dashboard_template')
@section('title','Especialidades')
@section('content')

    <div class="page-heading">
{{--        <x-navegation-view text="" />--}}

        @livewire('portalusuarios.especialidadesusers',['id_carrera'=>$informacion_carrera->id_carrera])
    </div>

    <script src="{{asset('js/domain.js')}}"></script>

@endsection
