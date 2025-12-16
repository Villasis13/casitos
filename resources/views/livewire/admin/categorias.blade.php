<div>

    {{--    MODAL AGREGAR / EDITAR--}}
    <x-modal-general  wire:ignore.self >
        <x-slot name="id_modal">modal_categoria</x-slot>
        <x-slot name="titleModal">Gestionar Categorías</x-slot>
        <x-slot name="modalContent">
            <form wire:submit.prevent="save_categoria">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                        <label for="categoria_nombre" class="form-label">Nombre <b class="text-danger">(*)</b></label>
                        <x-input-general  type="text" id="categoria_nombre" wire:model="categoria_nombre" placeholder="Ingrese nombre..." />
                        @error('categoria_nombre')<span class="message-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3"
                         x-data="{ val: @entangle('categoria_descripcion').live }">

                        <label for="categoria_descripcion" class="form-label">Descripción</label>

                        <textarea class="form-control" id="categoria_descripcion" name="categoria_descripcion" rows="3" placeholder="Ingrese descripción..." maxlength="1500"
                            x-model="val"
                            @input="if (val && val.length > 1500) val = val.slice(0, 1500)"
                        ></textarea>

                        <div class="d-flex justify-content-end mt-1">
                            <small class="text-muted">
                                <span x-text="(val ? val.length : 0)"></span>/1500
                            </small>
                        </div>

                        @error('categoria_descripcion')
                        <span class="message-error">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="col-lg-12 col-md-12 col-sm-12">
                        @if (session()->has('error_modal'))
                            <div class="alert alert-danger alert-dismissible show fade">
                                {{ session('error_modal') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 mt-3 text-end">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Cerrar</button>
                        <button type="submit" class="btn btn-success text-white">Guardar Registros</button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal-general>
    {{--    MODAL AGREGAR / EDITAR--}}

    {{--    MODAL DELETE--}}
    <x-modal-delete  wire:ignore.self >
        <x-slot name="id_modal">modal_delete_categoria</x-slot>
        <x-slot name="modalContentDelete">
            <form wire:submit.prevent="disable_categoria">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h2 class="deleteTitle">{{$messageDelete}}</h2>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        @error('id_categoria') <span class="message-error">{{ $message }}</span> @enderror

                        @error('categoria_estado') <span class="message-error">{{ $message }}</span> @enderror

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
    {{--    MODAL DELETE--}}

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 d-flex align-items-center mb-2">
            <input type="text" class="form-control w-50 me-4"  wire:model.live="search_categoria" placeholder="Buscar">
            <x-select-filter wire:model.live="pagination_categoria" />
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 text-end">
            <x-btn-export wire:click="clear_form" class="bg-success text-white" data-bs-toggle="modal" data-bs-target="#modal_categoria" >
                <x-slot name="icons">
                    fa-solid fa-plus
                </x-slot>
                Agregar Categoría
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

    <div class="row">
        @if(count($listar_categorias) > 0)
            @foreach($listar_categorias as $lv)
                <div class="col-lg-4 col-md-6 col-sm-12 mt-4">
                    <div class="card shadow position-relative">
                        @if($lv->categoria_estado == 0)
                            <div class="position-absolute top-0 start-0 p-2">
                                <span class="badge text-danger"><b>Categoría deshabilitado</b></span>
                            </div>
                        @endif

                        <!-- Icono de Opciones -->
                        <div class="position-absolute top-0 end-0 p-2">
                            <div class="dropdown">
                                <button class="btn btn-sm" style="background: #eceff2" type="button" id="dropdownMenu{{ $lv->id_categoria }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow cursoPointer" aria-labelledby="dropdownMenu{{ $lv->id_categoria }}">
                                    <li>
                                        <a class="dropdown-item text-primary" wire:click="edit_data('{{ base64_encode($lv->id_categoria) }}')" data-bs-toggle="modal" data-bs-target="#modal_categoria">
                                            <i class="fa-solid fa-pen-to-square"></i> <b>Editar</b>
                                        </a>
                                    </li>
                                    <li>
                                        @if($lv->categoria_estado == 1)
                                            <a class="dropdown-item text-danger" wire:click="btn_disable('{{ base64_encode($lv->id_categoria) }}',0)" data-bs-toggle="modal" data-bs-target="#modal_delete_categoria">
                                                <i class="fa-solid fa-ban"></i> <b>Deshabilitar</b>
                                            </a>
                                        @else
                                            <a class="dropdown-item text-success" wire:click="btn_disable('{{ base64_encode($lv->id_categoria) }}',1)" data-bs-toggle="modal" data-bs-target="#modal_delete_categoria" >
                                                <i class="fa-solid fa-check"></i> <b>Habilitar</b>
                                            </a>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card-body text-center mt-4">
                            <!-- Información -->
                            <h5>{{ $lv->categoria_nombre }}</h5>
                            <p class="mb-1">{{ $lv->categoria_descripcion ?? '-' }}</p>
                            <a class="btn bg-info text-white mt-3" href="{{route('Admin.casos',['id_categoria'=>base64_encode($lv->id_categoria)])}}">
                                Ver Casos
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-lg-12 text-center">
                <p class="text-muted mt-3">No se han encontrado resultados.</p>
            </div>
        @endif
    </div>
    {{ $listar_categorias->links(data: ['scrollTo' => false]) }}

</div>

@script
<script>
    $wire.on('hide_modal_categoria', () => {
        $('#modal_categoria').modal('hide');
    });
    $wire.on('hide_modal_delete_categoria', () => {
        $('#modal_delete_categoria').modal('hide');
    });
</script>
@endscript
