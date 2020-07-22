@extends('admin.admin-layout')

@section('content')

@if(!empty($usuario))


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
    <div class="card-header mb-3">Editar usuario</div>
    <div class="card-body">
        <form action="{{ url('/admin/usuarios/editar/' . $usuario->id) }}" method="POST">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" value="{{ $usuario->nombre }}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ $usuario->email }}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" value="{{ $usuario->telefono }}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="pass">Contraseña</label>
                        <input type="password" class="form-control" name="pass">
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
