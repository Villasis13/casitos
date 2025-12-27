<div>

    <style>
        .etapa-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .etapa-1 { background: #e3f2fd; color: #1976d2; }
        .etapa-2 { background: #fff3e0; color: #f57c00; }
        .etapa-3 { background: #e8f5e9; color: #388e3c; }

        .alternativa-box {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: #f8f9fa;
        }

        .letra-badge {
            display: inline-block;
            width: 35px;
            height: 35px;
            background: #1976d2;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 35px;
            font-weight: bold;
            margin-right: 10px;
        }

        .pregunta-section {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .selector-alternativa {
            border: 2px solid #2196f3;
            background: #e3f2fd;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
    </style>

    {{--    MODAL CREAR / EDITAR REGISTRO--}}
    <x-modal-general  wire:ignore.self >
        <x-slot name="id_modal">modal_casos</x-slot>
        <x-slot name="titleModal">Gestionar Caso</x-slot>
        <x-slot name="modalContent">
            <form wire:submit.prevent="save_casos">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        @if (session()->has('error_modal'))
                            <div class="alert alert-danger alert-dismissible show fade">
                                {{ session('error_modal') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>

                    <!-- CONTEXTO -->
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Título <span class="text-danger">(*)</span></label>
                            <input type="text" class="form-control" id="caso_titulo" wire:model="caso_titulo">
                            @error('caso_titulo') <span class="message-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Objetivo <span class="text-danger">(*)</span></label>
                            <input type="text" class="form-control" id="caso_objetivo" wire:model="caso_objetivo">
                            @error('caso_objetivo') <span class="message-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 mb-3"
                             x-data="{ val: @entangle('caso_contexto').live }">

                            <label for="caso_contexto" class="form-label">Contexto <b class="text-danger">(*)</b></label>

                            <textarea class="form-control" id="caso_contexto" name="caso_contexto" rows="3" placeholder="Ingrese contexto..." maxlength="2000"
                                      x-model="val"
                                      @input="if (val && val.length > 2000) val = val.slice(0, 2000)"
                            ></textarea>

                            <div class="d-flex justify-content-end mt-1">
                                <small class="text-muted">
                                    <span x-text="(val ? val.length : 0)"></span>/2000
                                </small>
                            </div>

                            @error('caso_contexto')<span class="message-error">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 mt-3 text-end">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Cerrar</button>
                        <button type="submit" class="btn btn-success text-white">Guardar Caso</button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal-general>
    {{--    MODAL CREAR / EDITAR REGISTRO--}}

    {{--    MODAL DELETE REGISTROS--}}
    <x-modal-delete  wire:ignore.self >
        <x-slot name="id_modal">modal_delete_caso</x-slot>
        <x-slot name="modalContentDelete">
            <form wire:submit.prevent="disable_caso">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h2 class="deleteTitle">{{$messageDelete}}</h2>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        @error('id_caso') <span class="message-error">{{ $message }}</span> @enderror

                        @error('caso_estado') <span class="message-error">{{ $message }}</span> @enderror

                        @if (session()->has('error_delete'))
                            <div class="alert alert-danger alert-dismissible show fade">
                                {{ session('error_delete') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 mt-3 text-center">
                        <button type="submit" class="btn btn-primary text-white btnDelete">SI</button>
                        <button type="button" data-bs-dismiss="modal" class="btn btn-danger btnDelete">No</button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal-delete>
    {{--    MODAL DELETE REGISTROS--}}

    {{--    MODAL CREAR / EDITAR ANÁLISIS--}}
    <x-modal-general wire:ignore.self>
        <x-slot name="id_modal">modal_crear_analisis</x-slot>
        <x-slot name="titleModal">Gestionar Análisis del Caso</x-slot>
        <x-slot name="tama">modal-lg</x-slot>
        <x-slot name="modalContent">
            <form wire:submit.prevent="save_analisis">
                <div class="row">
                    {{-- ERRORES --}}
                    <div class="col-12 mb-3">
                        @if (session()->has('error_modal_analisis'))
                            <div class="alert alert-danger alert-dismissible show fade">
                                {{ session('error_modal_analisis') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>

                    {{-- ================= ANALISIS ================= --}}
                    <div class="col-lg-8 col-md-8 col-sm-12 mb-3">
                        <h6 class="fw-bold mb-2">Análisis académico del caso</h6>
                        <textarea class="form-control" rows="15" id="analisis_contenido" wire:model.defer="analisis_contenido" placeholder="Redacta el análisis completo del caso. Usa Enter para separar párrafos."></textarea>
                    </div>

                    {{-- ================= CONCEPTOS Y FRASES ================= --}}
                    <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                        <div class="row">

                            {{-- CONCEPTOS --}}
                            <div class="col-12 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0">Conceptos</h6>
                                    <button type="button" class="btn btn-sm btn-primary" wire:click="agregar_concepto">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>

                                @foreach($conceptos as $index => $concepto)
                                    @if(!($concepto['deleted'] ?? false))
                                        <div class="border rounded p-2 mb-2">
                                            <input type="text" class="form-control mb-2" placeholder="Título" wire:model.defer="conceptos.{{ $index }}.titulo">

                                            <textarea class="form-control mb-2" rows="3" placeholder="Descripción" wire:model.defer="conceptos.{{ $index }}.descripcion"></textarea>

                                            <button type="button" class="btn btn-sm btn-danger w-100" wire:click="eliminar_concepto({{ $index }})">
                                                Eliminar concepto
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <hr>

                            {{-- FRASES --}}
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0">Frases</h6>
                                    <button type="button" class="btn btn-sm btn-success" wire:click="agregar_frase">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>

                                @foreach($frases as $index => $frase)
                                    @if(!($frase['deleted'] ?? false))
                                        <div class="border rounded p-2 mb-2">
                                            <textarea class="form-control mb-2" rows="2" placeholder="Frase" wire:model.defer="frases.{{ $index }}.texto"></textarea>

                                            <button type="button" class="btn btn-sm btn-danger w-100" wire:click="eliminar_frese({{ $index }})">
                                                Eliminar frase
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- BOTONES --}}
                    <div class="col-12 mt-3 text-end">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Cerrar</button>
                        <button type="submit" class="btn btn-success text-white">Guardar Análisis</button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal-general>
    {{--    MODAL CREAR / EDITAR ANÁLISIS--}}


    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 d-flex align-items-center mb-2">
            <input type="text" class="form-control w-50 me-4"  wire:model.live="search_caso" placeholder="Buscar">
            <x-select-filter wire:model.live="pagination_caso" />
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 text-end">
            <x-btn-export wire:click="clear_form" class="bg-success text-white" data-bs-toggle="modal" data-bs-target="#modal_casos" >
                <x-slot name="icons">
                    fa-solid fa-plus
                </x-slot>
                Agregar Caso
            </x-btn-export>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible show fade mt-2">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible show fade mt-2">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <x-card-general-view>
        <x-slot name="content">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <x-table-general>
                        <x-slot name="thead">
                            <tr>
                                <th>N°</th>
                                <th>Título</th>
                                <th>Objetivo</th>
                                <th>Contexto</th>
                                <th>Gestionar Etapa</th>
                                <th>Análisis</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </x-slot>

                        <x-slot name="tbody">
                            @if(count($listar_casos) > 0)
                                @php $conteo = 1; @endphp
                                @foreach($listar_casos as $me)
                                    <tr>
                                        <td>{{$conteo}}</td>
                                        <td>{{$me->caso_titulo}}</td>
                                        <td>{{$me->caso_objetivo}}</td>
                                        <td>{{$me->caso_contexto}}</td>
                                        <td>
                                            <a class="btn bg-info text-white mt-3" href="{{route('Admin.etapas',['id_caso'=>base64_encode($me->id_caso)])}}">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </td>
                                        <td>
                                            @php
                                                $analisis = \App\Models\Analisis::where('id_caso', $me->id_caso)
                                                    ->where('analisis_estado', 1)
                                                    ->first();
                                            @endphp

                                            @if(!$analisis)
                                                {{-- BTN CREAR ANÁLISIS --}}
                                                <a class="btn btn-sm btn-success" wire:click="btn_crear_analisis('{{ base64_encode($me->id_caso) }}')" data-bs-toggle="modal" data-bs-target="#modal_crear_analisis">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                            @else
                                                {{-- BTN EDITAR ANÁLISIS --}}
                                                <a class="btn btn-sm btn-primary" wire:click="btn_editar_analisis('{{ base64_encode($analisis->id_analisis) }}')" data-bs-toggle="modal" data-bs-target="#modal_crear_analisis">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="font-bold badge {{$me->caso_estado == 1 ? 'bg-label-success ' : 'bg-label-danger'}}">
                                                {{$me->caso_estado == 1 ? 'Habilitado ' : 'Deshabilitado'}}
                                            </span>
                                        </td>
                                        <td>
                                            <x-btn-accion class=" text-primary"  wire:click="edit_data('{{ base64_encode($me->id_caso) }}')" data-bs-toggle="modal" data-bs-target="#modal_casos">
                                                <x-slot name="message">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </x-slot>
                                            </x-btn-accion>

                                            @if($me->caso_estado == 1)
                                                <x-btn-accion class=" text-danger" wire:click="btn_disable('{{ base64_encode($me->id_caso) }}',0)" data-bs-toggle="modal" data-bs-target="#modal_delete_caso">
                                                    <x-slot name="message">
                                                        <i class="fa-solid fa-ban"></i>
                                                    </x-slot>
                                                </x-btn-accion>
                                            @else
                                                <x-btn-accion class=" text-success" wire:click="btn_disable('{{ base64_encode($me->id_caso) }}',1)" data-bs-toggle="modal" data-bs-target="#modal_delete_caso">
                                                    <x-slot name="message">
                                                        <i class="fa-solid fa-check"></i>
                                                    </x-slot>
                                                </x-btn-accion>
                                            @endif
                                        </td>
                                    </tr>
                                    @php $conteo++; @endphp
                                @endforeach
                            @else
                                <tr class="odd">
                                    <td valign="top" colspan="9" class="dataTables_empty text-center">
                                        No se han encontrado resultados.
                                    </td>
                                </tr>
                            @endif
                        </x-slot>
                    </x-table-general>
                </div>
            </div>
        </x-slot>
    </x-card-general-view>
    {{ $listar_casos->links(data: ['scrollTo' => false]) }}
</div>

@script
<script>
    $wire.on('hide_modal_casos', () => {
        $('#modal_casos').modal('hide');
    });

    $wire.on('hide_modal_delete_caso', () => {
        $('#modal_delete_caso').modal('hide');
    });

    $wire.on('hide_modal_etapa_dos', () => {
        $('#modal_etapa_dos').modal('hide');
    });

    $wire.on('hide_modal_crear_analisis', () => {
        $('#modal_crear_analisis').modal('hide');
    });
</script>
@endscript
