<div>

    {{--    MODAL AGREGAR / EDITAR--}}
    <x-modal-general  wire:ignore.self >
        <x-slot name="id_modal">modal_especialidad</x-slot>
        <x-slot name="titleModal">Gestionar Especialidad</x-slot>
        <x-slot name="modalContent">
            <form wire:submit.prevent="save_especialidad">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                        @if (session()->has('error_modal'))
                            <div class="alert alert-danger alert-dismissible show fade">
                                {{ session('error_modal') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                        <label for="especialidad_nombre" class="form-label">Nombre <b class="text-danger">(*)</b></label>
                        <x-input-general  type="text" id="especialidad_nombre" wire:model="especialidad_nombre" placeholder="Ingrese nombre..." />
                        @error('especialidad_nombre')<span class="message-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3"
                         x-data="{ val: @entangle('especialidad_descripcion').live }">

                        <label for="especialidad_descripcion" class="form-label">
                            Descripción <b class="text-danger">(*)</b>
                        </label>

                        <textarea class="form-control" id="especialidad_descripcion" name="especialidad_descripcion" rows="3" placeholder="Ingrese descripción..." maxlength="1500"
                            x-model="val"
                            @input="if (val && val.length > 1500) val = val.slice(0, 1500)"
                        ></textarea>

                        <div class="d-flex justify-content-end mt-1">
                            <small class="text-muted">
                                <span x-text="(val ? val.length : 0)"></span>/1500
                            </small>
                        </div>

                        @error('especialidad_descripcion')
                        <span class="message-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- REFERENCIA DE IMAGEN --}}
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                        <div class="alert alert-info d-flex align-items-start gap-2 alert-dismissible show fade" role="alert" wire:ignore.self>
                            <i class="fa-solid fa-circle-info mt-1"></i>
                            <div>
                                <strong>Recomendación de imagen:</strong><br>
                                De preferencia, utiliza una imagen en tamaño <b>128px</b> para una mejor visualización.<br>
                                Puedes buscar en:
                                <a href="https://www.flaticon.es/" target="_blank" class="fw-bold text-decoration-underline">
                                    REFERENCIA
                                </a>
                            </div>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex align-items-center justify-content-center">
                        <div style="width: 108px;" wire:ignore>
                            <img src="" id="previeImagePer" class="w-100" style="borderradius: 50%;margin-top: 10%;" alt="">
                            <div wire:loading wire:target="especialidad_imagen">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Cargando...
                            </div>
                            <label for="especialidad_imagen" class="iconsPreviewImage">
                                <i class="fa-solid fa-camera "></i>
                            </label>
                        </div>
                        <input type="file" class="d-none" id="especialidad_imagen" name="especialidad_imagen" onchange="previewImage(this,'previeImagePer')" wire:model="especialidad_imagen" >
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
        <x-slot name="id_modal">modal_delete_especialidad</x-slot>
        <x-slot name="modalContentDelete">
            <form wire:submit.prevent="disable_especialidad">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h2 class="deleteTitle">{{$messageDelete}}</h2>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        @error('id_especialidad') <span class="message-error">{{ $message }}</span> @enderror

                        @error('especialidad_estado') <span class="message-error">{{ $message }}</span> @enderror

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
            <input type="text" class="form-control w-50 me-4"  wire:model.live="search_especialidad" placeholder="Buscar">
            <x-select-filter wire:model.live="pagination_especialidad" />
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 text-end">
            <x-btn-export wire:click="clear_form" class="bg-success text-white" data-bs-toggle="modal" data-bs-target="#modal_especialidad" >
                <x-slot name="icons">
                    fa-solid fa-plus
                </x-slot>
                Agregar Especialidad
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
        @if(count($listar_especialidades) > 0)
            @foreach($listar_especialidades as $lv)
                <div class="col-lg-4 col-md-6 col-sm-12 mt-4">
                    <div class="card shadow position-relative">
                        @if($lv->especialidad_estado == 0)
                            <div class="position-absolute top-0 start-0 p-2">
                                <span class="badge text-danger"><b>Especialidad deshabilitado</b></span>
                            </div>
                        @endif

                        <!-- Icono de Opciones -->
                        <div class="position-absolute top-0 end-0 p-2">
                            <div class="dropdown">
                                <button class="btn btn-sm" style="background: #eceff2" type="button" id="dropdownMenu{{ $lv->id_especialidad }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow cursoPointer" aria-labelledby="dropdownMenu{{ $lv->id_especialidad }}">
                                    <li>
                                        <a class="dropdown-item text-primary" wire:click="edit_data('{{ base64_encode($lv->id_especialidad) }}')" data-bs-toggle="modal" data-bs-target="#modal_especialidad">
                                            <i class="fa-solid fa-pen-to-square"></i> <b>Editar</b>
                                        </a>
                                    </li>
                                    <li>
                                        @if($lv->especialidad_estado == 1)
                                            <a class="dropdown-item text-danger" wire:click="btn_disable('{{ base64_encode($lv->id_especialidad) }}',0)" data-bs-toggle="modal" data-bs-target="#modal_delete_especialidad">
                                                <i class="fa-solid fa-ban"></i> <b>Deshabilitar</b>
                                            </a>
                                        @else
                                            <a class="dropdown-item text-success" wire:click="btn_disable('{{ base64_encode($lv->id_especialidad) }}',1)" data-bs-toggle="modal" data-bs-target="#modal_delete_especialidad" >
                                                <i class="fa-solid fa-check"></i> <b>Habilitar</b>
                                            </a>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card-body text-center mt-4">
                            <img src="{{ asset($lv->especialidad_imagen) }}" style="border-radius: 50%; margin-bottom: 5%;">
                            <!-- Información -->
                            <h5>{{ $lv->especialidad_nombre }}</h5>
                            <p class="mb-1">{{ $lv->especialidad_descripcion }}</p>
                            <a class="btn bg-info text-white mt-3" href="{{route('Admin.categorias',['id_especialidad'=>base64_encode($lv->id_especialidad)])}}">
                                Ver Categorías
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
    {{ $listar_especialidades->links(data: ['scrollTo' => false]) }}

</div>

@script
<script>
    $wire.on('hide_modal_especialidad', () => {
        $('#modal_especialidad').modal('hide');
    });

    $wire.on('hide_modal_delete_especialidad', () => {
        $('#modal_delete_especialidad').modal('hide');
    });

    $wire.on('PersonalImg',function(event) {
        const image = document.getElementById('previeImagePer');
        if (image) {
            console.log(event[0].image);
            image.src = event[0].image;
        }
    });
</script>
@endscript
