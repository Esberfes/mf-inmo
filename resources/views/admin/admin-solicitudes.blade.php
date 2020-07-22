@extends('admin.admin-layout')

@section('content')

@if(!empty($solicitudes))
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Local</th>
                <th scope="col">Nombre</th>
                <th scope="col">Email</th>
                <th scope="col">Telefono</th>
                <th scope="col">Fecha creación</th>
                <th scope="col">Detalle</th>
            </tr>
        </thead>
        <tbody>
        @foreach($solicitudes as $solicitud)
        <tr>
            <th scope="row">{{ $solicitud->id }}</th>
            <td>{{ $solicitud->local->titulo }}</td>
            <td>{{ $solicitud->nombre }}</td>
            <td>{{ $solicitud->email }}</td>
            <td>{{ $solicitud->telefono }}</td>
            <td>{{ \Carbon\Carbon::parse($solicitud->creado_en)->format('d/m/Y H:i:s') }}</td>
            <td><button type="button" class="btn btn-primary">Detalle</button></td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endif
@if(!empty($paginacion))
    <div class="text-center mt-5">
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link" href="{{ url('/admin/solicitudes/'.$paginacion['pagina_anterior']) }}">Anterior</a></li>
            @foreach($paginacion['paginas'] as $pagina)
                @if($pagina == $paginacion['pagina'])
                <li class="page-item active"><a class="page-link" href="{{ url('/admin/solicitudes/'.$pagina) }}">{{ $pagina }}</a></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ url('/admin/solicitudes/'.$pagina) }}">{{ $pagina }}</a></li>
                @endif
            @endforeach
            <li class="page-item"><a class="page-link" href="{{ url('/admin/solicitudes/'.$paginacion['pagina_siguiente']) }}">Siguiente</a></li>
        </ul>
    </div>
@endif
@endsection
