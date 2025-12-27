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
        <x-slot name="id_modal">modal_etapa</x-slot>
        <x-slot name="titleModal">Gestionar Etapa</x-slot>
        <x-slot name="modalContent">
            <form wire:submit.prevent="save_etapa">
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
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label class="form-label">Título <span class="text-danger">(*)</span></label>
                            <input type="text" class="form-control" id="etapa_titulo" wire:model="etapa_titulo">
                            @error('etapa_titulo') <span class="message-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-lg-6 col-sm-12 mt-4 mb-3 d-flex justify-content-center align-items-center">
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" wire:model.live="etapa_final" id="etapa_final">
                                <label class="form-check-label" for="etapa_final">
                                    Etapa final
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-12 col-sm-12 mb-3"
                             x-data="{ val: @entangle('etapa_problema').live }">

                            <label for="etapa_problema" class="form-label">Problema <b class="text-danger">(*)</b></label>

                            <textarea class="form-control" id="etapa_problema" name="etapa_problema" rows="3" placeholder="Ingrese problema..." maxlength="998"
                                      x-model="val"
                                      @input="if (val && val.length > 998) val = val.slice(0, 998)"
                            ></textarea>

                            <div class="d-flex justify-content-end mt-1">
                                <small class="text-muted">
                                    <span x-text="(val ? val.length : 0)"></span>/998
                                </small>
                            </div>

                            @error('etapa_problema')<span class="message-error">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 mt-3 text-end">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Cerrar</button>
                        <button type="submit" class="btn btn-success text-white">Guardar Etapa</button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal-general>
    {{--    MODAL CREAR / EDITAR REGISTRO--}}

{{--    MODAL AGREGAR ALTERNATIVAS--}}
    <x-modal-general  wire:ignore.self >
        <x-slot name="id_modal">modal_agregar_alternativa</x-slot>
        <x-slot name="titleModal">Gestionar Alternativas</x-slot>
        <x-slot name="modalContent">
            <form wire:submit.prevent="save_alternativas">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        @if (session()->has('error_modal_alter'))
                            <div class="alert alert-danger alert-dismissible show fade">
                                {{ session('error_modal_alter') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>

                    <!-- CONTEXTO -->
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label class="form-label">Título <span class="text-danger">(*)</span></label>
                            <input type="text" class="form-control" id="etapa_titulo" wire:model="etapa_titulo">
                            @error('etapa_titulo') <span class="message-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-lg-6 col-sm-12 mb-3">
                            <div class="col-lg-6 col-sm-12 mt-4 mb-3 d-flex justify-content-center align-items-center">
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox" wire:model.live="etapa_pregunta_doctrinal" id="etapa_pregunta_doctrinal">
                                    <label class="form-check-label" for="etapa_pregunta_doctrinal">
                                        Pregunta Doctrinal
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-sm-12 mb-3"
                             x-data="{ val: @entangle('etapa_problema').live }">

                            <label for="etapa_problema" class="form-label">Problema <b class="text-danger">(*)</b></label>

                            <textarea class="form-control" id="etapa_problema" name="etapa_problema" rows="3" placeholder="Ingrese problema..." maxlength="998"
                                      x-model="val"
                                      @input="if (val && val.length > 998) val = val.slice(0, 998)"
                            ></textarea>

                            <div class="d-flex justify-content-end mt-1">
                                <small class="text-muted">
                                    <span x-text="(val ? val.length : 0)"></span>/998
                                </small>
                            </div>

                            @error('etapa_problema')<span class="message-error">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <!-- ALTERNATIVA -->
                    <div class="row">
                        <!-- A -->
                        <div class="col-lg-12 col-sm-12 mb-3">
                            <div class="alternativa-box">
                                <div class="d-flex align-items-start mb-2">
                                    <span class="letra-badge">A</span>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between mb-2">
                                            <input type="text" class="form-control form-control-sm mb-2" onkeyup="validar_numeros(this.id)" id="alt_a_puntos" wire:model="alt_a_puntos" placeholder="Puntos" style="width: 100px;">

                                            <select class="form-control form-control-sm w-50" id="id_destino_a" wire:model.live="id_destino_a">
                                                <option value="">Seleccionar destino...</option>
                                                <option value="1">Ir a etapa</option>
                                                <option value="3">Finalizar caso</option>
                                            </select>
                                        </div>

                                        <textarea class="form-control mb-3" id="alt_a_texto" wire:model="alt_a_texto" rows="2" placeholder="Texto alternativa A..."></textarea>

                                        @if($id_destino_a == 1)
                                            <select class="form-control form-control-sm" id="id_etapa_a" wire:model="id_etapa_a">
                                                <option value="">Seleccionar etapa...</option>
                                                @foreach($listar_opc_etapas as $loe)
                                                    <option value="{{ $loe->id_etapa }}">{{ $loe->etapa_titulo }}</option>
                                                @endforeach
                                            </select>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- B -->
                        <div class="col-lg-12 col-sm-12 mb-3">
                            <div class="alternativa-box">
                                <div class="d-flex align-items-start mb-2">
                                    <span class="letra-badge">B</span>
                                    <div class="flex-grow-1">

                                        <div class="d-flex justify-content-between mb-2">
                                            <input type="text" class="form-control form-control-sm mb-2" onkeyup="validar_numeros(this.id)" id="alt_b_puntos" wire:model="alt_b_puntos" placeholder="Puntos" style="width: 100px;">

                                            <select class="form-control form-control-sm w-50" id="id_destino_b" wire:model.live="id_destino_b">
                                                <option value="">Seleccionar destino...</option>
                                                <option value="1">Ir a etapa</option>
                                                <option value="3">Finalizar caso</option>
                                            </select>
                                        </div>

                                        <textarea class="form-control mb-3" id="alt_b_texto" wire:model="alt_b_texto" rows="2" placeholder="Texto alternativa B..."></textarea>

                                        @if($id_destino_b == 1)
                                            <select class="form-control form-control-sm" id="id_etapa_b" wire:model="id_etapa_b">
                                                <option value="">Seleccionar etapa...</option>
                                                @foreach($listar_opc_etapas as $loe)
                                                    <option value="{{ $loe->id_etapa }}">{{ $loe->etapa_titulo }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- C -->
                        <div class="col-lg-12 col-sm-12 mb-3">
                            <div class="alternativa-box">
                                <div class="d-flex align-items-start mb-2">
                                    <span class="letra-badge">C</span>
                                    <div class="flex-grow-1">

                                        <div class="d-flex justify-content-between mb-2">
                                            <input type="text" class="form-control form-control-sm mb-2" onkeyup="validar_numeros(this.id)" id="alt_c_puntos" wire:model="alt_c_puntos" placeholder="Puntos" style="width: 100px;">

                                            <select class="form-control form-control-sm w-50" id="id_destino_c" wire:model.live="id_destino_c">
                                                <option value="">Seleccionar destino...</option>
                                                <option value="1">Ir a etapa</option>
                                                <option value="3">Finalizar caso</option>
                                            </select>
                                        </div>

                                        <textarea class="form-control mb-3" id="alt_c_texto" wire:model="alt_c_texto" rows="2" placeholder="Texto alternativa C..."></textarea>

                                        @if($id_destino_c == 1)
                                            <select class="form-control form-control-sm" id="id_etapa_c" wire:model="id_etapa_c">
                                                <option value="">Seleccionar etapa...</option>
                                                @foreach($listar_opc_etapas as $loe)
                                                    <option value="{{ $loe->id_etapa }}">{{ $loe->etapa_titulo }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($etapa_pregunta_doctrinal)
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 mb-3">
                                <div class="pregunta-section">
                                    <label class="form-label fw-bold"> Pregunta Doctrinal</label>

                                    <div class="mb-2">
                                        <label class="form-label">Descripción</label>
                                        <input type="text" class="form-control form-control-sm" id="pregunta_descripcion" wire:model="pregunta_descripcion" placeholder="Contexto de la pregunta...">
                                    </div>

                                    <textarea class="form-control mb-2" id="pregunta_texto" wire:model="pregunta_texto" rows="2" placeholder="¿Qué herramienta...?"></textarea>

                                    <div class="row">
                                        <div class="col-md-8">
                                            <label class="form-label">Palabra Clave</label>
                                            <input type="text" class="form-control" id="pregunta_clave" wire:model="pregunta_clave" placeholder="Ej: logs">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Puntos</label>
                                            <input type="text" class="form-control" onkeyup="validar_numeros(this.id)" id="pregunta_puntos" wire:model="pregunta_puntos">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-lg-12 col-md-12 col-sm-12 mt-3 text-end">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Cerrar</button>
                        <button type="submit" class="btn btn-success text-white">Guardar Etapa</button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal-general>
{{--    MODAL AGREGAR ALTERNATIVAS--}}

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 d-flex align-items-center mb-2">
            <input type="text" class="form-control w-50 me-4"  wire:model.live="search_etapa" placeholder="Buscar">
            <x-select-filter wire:model.live="pagination_etapa" />
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 text-end">
            <x-btn-export wire:click="clear_form" class="bg-success text-white" data-bs-toggle="modal" data-bs-target="#modal_etapa" >
                <x-slot name="icons">
                    fa-solid fa-plus
                </x-slot>
                Agregar Etapa
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
                                <th>Problema</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </x-slot>

                        <x-slot name="tbody">
                            @if(count($listar_etapa) > 0)
                                @php $conteo = 1; @endphp
                                @foreach($listar_etapa as $me)
                                    <tr>
                                        <td>{{$conteo}}</td>
                                        <td>{{$me->etapa_titulo}}</td>
                                        <td>{{$me->etapa_problema}}</td>
                                        <td>
                                            <span class="font-bold badge {{$me->etapa_estado == 1 ? 'bg-label-success ' : 'bg-label-danger'}}">
                                                {{$me->etapa_estado == 1 ? 'Habilitado ' : 'Deshabilitado'}}
                                            </span>
                                        </td>
                                        <td>
                                            <x-btn-accion class=" text-primary"  wire:click="edit_etapa('{{ base64_encode($me->id_etapa) }}')" data-bs-toggle="modal" data-bs-target="#modal_agregar_alternativa">
                                                <x-slot name="message">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </x-slot>
                                            </x-btn-accion>
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
    {{ $listar_etapa->links(data: ['scrollTo' => false]) }}

</div>

@script
<script>
    $wire.on('hide_modal_etapa', () => {
        $('#modal_etapa').modal('hide');
    });

    $wire.on('hide_modal_agregar_alternativa', () => {
        $('#modal_agregar_alternativa').modal('hide');
    });
</script>
@endscript
