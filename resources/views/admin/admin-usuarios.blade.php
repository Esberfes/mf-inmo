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

@if(!empty($usuarios))
<section class="admin-table-section">
    <div class="card mb-3 admin-table-wrapper">
        <div class="admin-table-wrapper-header">
            <div class="admin-table-wrapper-header-title">
                Usuarios
            </div>
            <div class="admin-table-wrapper-header-info">
                @if(!empty($paginacion))
                Mostrando desde {{ $paginacion['offset'] }} a {{ ($paginacion['offset'] + $usuarios->count()) }} de
                {{ $paginacion['total'] }}
                @endif
            </div>
        </div>
        <div class="admin-table-wrapper-body">
            <form class="admin-table-wrapper-filters" action=" {{ url('/admin/usuarios') }}" method="POST">
                <div class="admin-table-wrapper-filters-group ">
                    <label for="busqueda">Busqueda global</label>
                    <input name="busqueda"
                        value="{{ Session::get(\App\Constants\SessionConstants::ADMIN_USUARIOS_FILTER)->busqueda }}"
                        type="search" class="form-control" placeholder="--Sin filtro--">
                </div>
                <div class="admin-table-wrapper-filters-group">
                    <button class="btn btn-sm btn-outline-primary">Buscar</button>
                </div>

                <input name="action" value="search" type="hidden">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
            <table class="admin-table table-responsive">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Email</th>
                        <th scope="col">Telefono</th>
                        <th scope="col">Ultimo login</th>
                        <th scope="col">Fecha creación</th>
                        <th scope="col">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->telefono }}</td>
                        <td>{{ \Carbon\Carbon::parse($usuario->ultimo_login)->format('d/m/Y H:i:s') }}</td>
                        <td>{{ \Carbon\Carbon::parse($usuario->creado_en)->format('d/m/Y H:i:s') }}</td>
                        <td>
                            <div class="admin-table-actions-col-wrapper">
                                <a class="btn btn-sm btn-outline-primary"
                                    href="{{ url('/admin/usuarios/editar/'.$usuario->id) }}">Editar</a>

                                <form action="{{ url('/admin/usuarios/eliminar/'.$usuario->id) }}" method="post">
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="admin-table-wrapper-footer">
            @if(!empty($paginacion))
            <div class="text-center mt-5">
                <ul class="pagination justify-content-center">
                    <li class="page-item"><a class="page-link"
                            href="{{ url('/admin/usuarios/'.$paginacion['pagina_anterior']) }}">Anterior</a></li>
                    @foreach($paginacion['paginas'] as $pagina)
                    @if($pagina == $paginacion['pagina'])
                    <li class="page-item active"><a class="page-link"
                            href="{{ url('/admin/usuarios/'.$pagina) }}">{{ $pagina }}</a>
                    </li>
                    @else
                    <li class="page-item"><a class="page-link"
                            href="{{ url('/admin/usuarios/'.$pagina) }}">{{ $pagina }}</a></li>
                    @endif
                    @endforeach
                    <li class="page-item"><a class="page-link"
                            href="{{ url('/admin/usuarios/'.$paginacion['pagina_siguiente']) }}">Siguiente</a></li>
                </ul>
            </div>
            @endif
        </div>
    </div>
</section>

@endif
@endsection
