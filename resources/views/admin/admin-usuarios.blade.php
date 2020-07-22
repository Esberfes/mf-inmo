@extends('admin.admin-layout')

@section('content')

<section>
    <form class="form-search" action="{{ url('/admin/usuarios') }}" method="POST">
        <div class="row">
            <div class="col d-flex align-items-center justify-content-end">
                <input style="max-width: 400px;" name="busqueda" value="{{ Session::get(\App\Constants\SessionConstants::ADMIN_USUARIOS_FILTER)->busqueda }}" type="search" class="form-control" placeholder="Busqueda">
                <button class="ml-3">Encontrar</button>
            </div>
            <input name="action" value="search" type="hidden">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </div>
    </form>
</section>

@if(!empty($usuarios))
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Email</th>
                <th scope="col">Telefono</th>
                <th scope="col">Ultimo login</th>
                <th scope="col">Fecha creación</th>
                <th scope="col">Editar</th>
            </tr>
        </thead>
        <tbody>
        @foreach($usuarios as $usuario)
        <tr>
            <th scope="row">{{ $usuario->id }}</th>
            <td>{{ $usuario->nombre }}</td>
            <td>{{ $usuario->email }}</td>
            <td>{{ $usuario->telefono }}</td>
            <td>{{ \Carbon\Carbon::parse($usuario->ultimo_login)->format('d/m/Y H:i:s') }}</td>
            <td>{{ \Carbon\Carbon::parse($usuario->creado_en)->format('d/m/Y H:i:s') }}</td>
            <td><a class="btn btn-primary" href="{{ url('/admin/usuarios/editar/'.$usuario->id) }}">Editar</a></td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endif
@if(!empty($paginacion))
    <div class="text-center mt-5">
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link" href="{{ url('/admin/usuarios/'.$paginacion['pagina_anterior']) }}">Anterior</a></li>
            @foreach($paginacion['paginas'] as $pagina)
                @if($pagina == $paginacion['pagina'])
                <li class="page-item active"><a class="page-link" href="{{ url('/admin/usuarios/'.$pagina) }}">{{ $pagina }}</a></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ url('/admin/usuarios/'.$pagina) }}">{{ $pagina }}</a></li>
                @endif
            @endforeach
            <li class="page-item"><a class="page-link" href="{{ url('/admin/usuarios/'.$paginacion['pagina_siguiente']) }}">Siguiente</a></li>
        </ul>
    </div>
@endif
@endsection
