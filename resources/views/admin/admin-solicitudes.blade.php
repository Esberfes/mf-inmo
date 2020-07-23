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

@if(!empty($solicitudes))
<section>
    <div class="card mb-3 admin-table-wrapper">
        <div class="admin-table-wrapper-header">
            <div class="admin-table-wrapper-header-title">
                Sectores
            </div>
            <div class="admin-table-wrapper-header-info">
                @if(!empty($paginacion))
                Mostrando desde {{ $paginacion['offset'] }} a {{ ($paginacion['offset'] + $solicitudes->count()) }} de
                {{ $paginacion['total'] }}
                @endif
            </div>
        </div>
        <div class="admin-table-wrapper-body">
            <form class="admin-table-wrapper-filters" action=" {{ url('/admin/solicitudes') }}" method="POST">

                <div class="admin-table-wrapper-filters-group ">
                <label for="busqueda">Atendidas</label>
                    <select name="mostrar_atendidos" class="custom-select mr-3">
                        <option
                            {{ Session::get(\App\Constants\SessionConstants::ADMIN_SOLICITUDES_FILTER)->mostrar_atendidos == -1 ? 'selected' : '' }}
                            value="-1">Todas</option>
                        <option
                            {{ Session::get(\App\Constants\SessionConstants::ADMIN_SOLICITUDES_FILTER)->mostrar_atendidos == 0 ? 'selected' : ''}}
                            value="0">Atendidas</option>
                        <option
                            {{ Session::get(\App\Constants\SessionConstants::ADMIN_SOLICITUDES_FILTER)->mostrar_atendidos == 1 ? 'selected' : ''}}
                            value="1">Sin atender</option>
                    </select>
                </div>
                <div class="admin-table-wrapper-filters-group ">
                    <label for="busqueda">Busqueda global</label>
                    <input name="busqueda"
                        value="{{ Session::get(\App\Constants\SessionConstants::ADMIN_SOLICITUDES_FILTER)->busqueda }}"
                        type="search" class="form-control" placeholder="--Sin filtro--">
                </div>

                <div class="admin-table-wrapper-filters-group">
                    <button class="btn btn-sm btn-outline-primary">Buscar</button>
                </div>

                <input name="action" value="search" type="hidden">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Local</th>
                        <th scope="col">Sector</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Email</th>
                        <th scope="col">Telefono</th>
                        <th scope="col">Atendido en</th>
                        <th scope="col">Fecha creación</th>
                        <th scope="col">Atender</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($solicitudes as $solicitud)
                    <tr>
                        <td scope="row">{{ $solicitud->id }}</td>
                        <td>{{ $solicitud->local->titulo }}</td>
                        <td>{{ $solicitud->local->sector->titulo }}</td>
                        <td>{{ $solicitud->nombre }}</td>
                        <td>{{ $solicitud->email }}</td>
                        <td>{{ $solicitud->telefono }}</td>
                        <td>{{ $solicitud->atendido_en ? \Carbon\Carbon::parse($solicitud->atendido_en)->format('d/m/Y H:i:s') : '' }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($solicitud->creado_en)->format('d/m/Y H:i:s') }}</td>
                        <td>
                            <form action="{{ url('/admin/solicitudes/atender/'.$solicitud->id) }}" method="post">
                                <button {{ $solicitud->atendido_en ? 'disabled' : '' }}
                                    class="btn btn-primary">Atender</button>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="admin-table-wrapper-footer">
            @if(!empty($paginacion))
            <div class="text-center">
                <ul class="pagination justify-content-center">
                    <li class="page-item"><a class="page-link"
                            href="{{ url('/admin/solicitudes/'.$paginacion['pagina_anterior']) }}">Anterior</a></li>
                    @foreach($paginacion['paginas'] as $pagina)
                    @if($pagina == $paginacion['pagina'])
                    <li class="page-item active"><a class="page-link"
                            href="{{ url('/admin/solicitudes/'.$pagina) }}">{{ $pagina }}</a>
                    </li>
                    @else
                    <li class="page-item"><a class="page-link"
                            href="{{ url('/admin/solicitudes/'.$pagina) }}">{{ $pagina }}</a></li>
                    @endif
                    @endforeach
                    <li class="page-item"><a class="page-link"
                            href="{{ url('/admin/solicitudes/'.$paginacion['pagina_siguiente']) }}">Siguiente</a></li>
                </ul>
            </div>
            @endif
        </div>
    </div>
</section>

@endif
@endsection
