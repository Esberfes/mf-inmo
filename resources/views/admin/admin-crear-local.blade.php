@extends('admin.admin-layout')

@section('content')



@if ($errors->any())
    <div class="alert alert-danger mt-3">
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
        <form action="{{ url('/admin/locales/crear/') }}" method="POST">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="titulo">Titulo</label>
                        <input type="text" class="form-control" name="titulo" value="{{ old('titulo') }}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="url_amigable">Url amigable</label>
                        <input readonly disabled type="text" class="form-control" name="url_amigable">
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" value="{{ old('telefono') }}">
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input type="number" class="form-control" name="precio" step="0.01" value="{{ old('precio') }}">
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="metros">Metros</label>
                        <input type="number" class="form-control" name="metros" step="0.01" value="{{ old('metros') }}">
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="sector">Sector</label>
                        <select name="sector" class="custom-select">
                            @if(!empty($sectores))
                                @foreach($sectores as $sector)
                                    @if(old('sector') == $sector->id)
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
                                    @if(old('poblacion') == $poblacion->id)
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
                        <textarea class="form-control" rows="3" name="extracto">{{ old('extracto') }}</textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" rows="6" name="descripcion">{{ old('descripcion') }}</textarea>
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

@endsection

@section('scripts')
<script>
    CKEDITOR.replace( 'descripcion' );
	CKEDITOR.replace( 'extracto' );
</script>
@endsection
