@extends('admin.admin-layout')

@section('content')

@if(!empty($poblacion))

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
        <div class="card-header mb-3">Editar población</div>
        <div class="card-body">
            <form action="{{ url('/admin/poblaciones/editar/' . $poblacion->id) }}" method="POST">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="titulo">Titulo</label>
                            <input type="text" class="form-control" name="nombre" value="{{ $poblacion->nombre }}">
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
@endif
@endsection

@section('scripts')

@endsection
