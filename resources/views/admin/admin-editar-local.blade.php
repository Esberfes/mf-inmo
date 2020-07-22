@extends('admin.admin-layout')

@section('content')

@if(!empty($local))

    @if ($errors->any())
    <div class="blanco mt-3">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session()->has('success'))
    <div class="alert alert-success mt-3">
        {{ session()->get('success') }}
    </div>
    @endif

    <div class="card mb-3">
        <div class="card-header mb-3">Datos básicos</div>
        <div class="card-body">
            <form action="{{ url('/admin/locales/editar/' . $local->id) }}" method="POST">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="titulo">Titulo</label>
                            <input type="text" class="form-control" name="titulo" value="{{ $local->titulo }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="url_amigable">Url amigable</label>
                            <input readonly type="text" class="form-control" name="url_amigable" value="{{ $local->url_amigable }}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" value="{{ $local->telefono }}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="precio">Precio</label>
                            <input type="number" class="form-control" name="precio" step="0.01" value="{{ $local->precio }}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="metros">Metros</label>
                            <input type="number" class="form-control" name="metros" step="0.01" value="{{ $local->metros }}">
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label for="sector">Sector</label>
                            <select name="sector" class="custom-select">
                                @if(!empty($sectores))
                                    @foreach($sectores as $sector)
                                        @if($local->sector->id == $sector->id)
                                        <option selected value="{{ $sector->id }}">{{ $sector->titulo }}</option>
                                        @else
                                        <option value="{{ $sector->id }}">{{ $sector->titulo }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="poblacion">Población</label>
                            <select name="poblacion" class="custom-select">
                                @if(!empty($poblaciones))
                                    @foreach($poblaciones as $poblacion)
                                        @if($local->poblacion->id == $poblacion->id)
                                        <option selected value="{{ $poblacion->id }}">{{ $poblacion->nombre }}</option>
                                        @else
                                        <option value="{{ $poblacion->id }}">{{ $poblacion->nombre }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="extracto">Extracto</label>
                            <textarea class="form-control" rows="3" name="extracto">{{ $local->extracto }}</textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" rows="6" name="descripcion">{{ $local->descripcion }}</textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header mb-3">Media</div>
        <div class="card-body">
            <form action="{{ url('/admin/locales/editar/' . $local->id . '/media/destacada/') }}" method="POST">
                <div class="file-uploader">
                    <label for="file-upload" class="file-upload">
                        <i class="fas fa-cloud-upload-alt"></i> Destacada
                    </label>

                    <input name="imagen_destacada" class="file-uploader-input" type="file" accept="image/*">
                    <div class="file-uploader-name"></div>

                    <div class="file-uploader-preview">
                        @if($local->imagen_principal)
                        <img style="max-width: 100%" src="{{ url($local->imagen_principal->ruta) }}" alt="">
                        @endif
                    </div>
                </div>

                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header mb-3">Caracteristicas</div>
        <div class="card-body">
            @if(!empty($local->caracteristicas))
            @foreach($local->caracteristicas as $caracteristica)
            <form action="{{ url('/admin/locales/editar/' . $local->id . '/caracteristica/' . $caracteristica->id) }}" method="POST">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <textarea required class="form-control" rows="1" name="caracteristica">{{ $caracteristica->valor }}</textarea>
                        </div>
                    </div>
                    <div class="col d-flex align-items-center">
                        <button type="submit" name="guardar" class="btn btn-primary mr-3 ml-3">Guardar</button>
                        <button type="submit" name="eliminar" class="btn btn-danger mr-3 ml-3">Eliminar</button>
                    </div>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
            @endforeach
            @endif
            <form action="{{ url('/admin/locales/editar/' . $local->id . '/caracteristica') }}" method="post">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <textarea required class="form-control" rows="1" name="caracteristica"></textarea>
                        </div>
                    </div>
                    <div class="col d-flex align-items-center">
                        <button type="submit" name="guardar" class="btn btn-primary mr-3 ml-3">Añadir</button>
                    </div>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
        </div>
    </div>
    <!-- End Caracteristicas -->

    <div class="card mb-3">
        <div class="card-header mb-3">Edificio</div>
        <div class="card-body">
            @if(!empty($local->edificios))
            @foreach($local->edificios as $edificio)
            <form action="{{ url('/admin/locales/editar/' . $local->id . '/edificio/' . $edificio->id) }}" method="POST">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <textarea required class="form-control" rows="1" name="edificio">{{ $edificio->valor }}</textarea>
                        </div>
                    </div>
                    <div class="col d-flex align-items-center">
                        <button type="submit" name="guardar" class="btn btn-primary mr-3 ml-3">Guardar</button>
                        <button type="submit" name="eliminar" class="btn btn-danger mr-3 ml-3">Eliminar</button>
                    </div>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
            @endforeach
            @endif
            <form action="{{ url('/admin/locales/editar/' . $local->id . '/edificio') }}" method="post">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <textarea required class="form-control" rows="1" name="edificio"></textarea>
                        </div>
                    </div>
                    <div class="col d-flex align-items-center">
                        <button type="submit" name="guardar" class="btn btn-primary mr-3 ml-3">Añadir</button>
                    </div>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
        </div>
    </div>
    <!-- End Edificio -->

    <div class="card mb-3">
        <div class="card-header mb-3">Equipamiento </div>
        <div class="card-body">
            @if(!empty($local->equipamientos))
            @foreach($local->equipamientos as $equipamiento)
            <form action="{{ url('/admin/locales/editar/' . $local->id . '/equipamiento/' . $equipamiento->id) }}" method="POST">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <textarea required class="form-control" rows="1" name="equipamiento">{{ $equipamiento->valor }}</textarea>
                        </div>
                    </div>
                    <div class="col d-flex align-items-center">
                        <button type="submit" name="guardar" class="btn btn-primary mr-3 ml-3">Guardar</button>
                        <button type="submit" name="eliminar" class="btn btn-danger mr-3 ml-3">Eliminar</button>
                    </div>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
            @endforeach
            @endif
            <form action="{{ url('/admin/locales/editar/' . $local->id . '/equipamiento') }}" method="post">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <textarea required class="form-control" rows="1" name="equipamiento"></textarea>
                        </div>
                    </div>
                    <div class="col d-flex align-items-center">
                        <button type="submit" name="guardar" class="btn btn-primary mr-3 ml-3">Añadir</button>
                    </div>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
        </div>
    </div>
@endif
@endsection

@section('scripts')
<script>
    CKEDITOR.replace( 'descripcion' );
	CKEDITOR.replace( 'extracto' );
</script>
@endsection
