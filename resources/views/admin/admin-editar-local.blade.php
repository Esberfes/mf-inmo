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

  <form class="mb-3" action="{{ url('/admin/locales/editar/' . $local->id) }}" method="POST">
    <h2 class="mb-3">Datos básicos</h2>
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
                <label for="extracto">Descripción</label>
                <textarea class="form-control" rows="6" name="descripcion">{{ $local->descripcion }}</textarea>
            </div>
        </div>

        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </div>
  </form>

    <h2 class="mt-3">Caracteristicas</h2>
    @if(!empty($local->caracteristicas))
    @foreach($local->caracteristicas as $caracteristica)
    <form action="{{ url('/admin/locales/editar/' . $local->id . '/caracteristica/' . $caracteristica->id) }}" method="POST">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <textarea required class="form-control" rows="3" name="caracteristica">{{ $caracteristica->valor }}</textarea>
                </div>
            </div>
            <div class="col d-flex align-items-center">
                <button type="submit" name="guardar" class="btn btn-primary m-3">Guardar</button>
                <button type="submit" name="eliminar" class="btn btn-danger m-3">Eliminar</button>
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
                    <textarea required class="form-control" rows="3" name="caracteristica"></textarea>
                </div>
            </div>
            <div class="col d-flex align-items-center">
                <button type="submit" name="guardar" class="btn btn-primary m-3">Guardar</button>
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
    <!-- End Caracteristicas -->


    <h2 class="mt-3">Edificio</h2>
    @if(!empty($local->edificios))
    @foreach($local->edificios as $edificio)
    <form action="{{ url('/admin/locales/editar/' . $local->id . '/edificio/' . $edificio->id) }}" method="POST">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <textarea required class="form-control" rows="3" name="edificio">{{ $edificio->valor }}</textarea>
                </div>
            </div>
            <div class="col d-flex align-items-center">
                <button type="submit" name="guardar" class="btn btn-primary m-3">Guardar</button>
                <button type="submit" name="eliminar" class="btn btn-danger m-3">Eliminar</button>
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
                    <textarea required class="form-control" rows="3" name="edificio"></textarea>
                </div>
            </div>
            <div class="col d-flex align-items-center">
                <button type="submit" name="guardar" class="btn btn-primary m-3">Guardar</button>
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
    <!-- End Edificio -->


    <h2 class="mt-3">Equipamiento</h2>
    @if(!empty($local->equipamientos))
    @foreach($local->equipamientos as $equipamiento)
    <form action="{{ url('/admin/locales/editar/' . $local->id . '/equipamiento/' . $equipamiento->id) }}" method="POST">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <textarea required class="form-control" rows="3" name="equipamiento">{{ $equipamiento->valor }}</textarea>
                </div>
            </div>
            <div class="col d-flex align-items-center">
                <button type="submit" name="guardar" class="btn btn-primary m-3">Guardar</button>
                <button type="submit" name="eliminar" class="btn btn-danger m-3">Eliminar</button>
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
                    <textarea required class="form-control" rows="3" name="equipamiento"></textarea>
                </div>
            </div>
            <div class="col d-flex align-items-center">
                <button type="submit" name="guardar" class="btn btn-primary m-3">Guardar</button>
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>

@endif
@endsection
