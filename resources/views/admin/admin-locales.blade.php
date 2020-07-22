@extends('admin.admin-layout')

@section('content')

@if(!empty($locales))
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Titulo</th>
                <th scope="col">Destacado</th>
                <th scope="col">Sector</th>
                <th scope="col">Población</th>
                <th scope="col">Fecha creación</th>
                <th scope="col">Fecha modificación</th>
                <th scope="col">Editar</th>
            </tr>
        </thead>
        <tbody>
        @foreach($locales as $local)
        <tr>
            <th scope="row">{{ $local->id }}</th>
            <td>{{ $local->titulo }}</td>
            <td>{{ $local->destacado ? 'Si' : 'No'}}</td>
            <td>{{ $local->sector->titulo}}</td>
            <td>{{ $local->poblacion->nombre}}</td>
            <td>{{ $local->creado_en}}</td>
            <td>{{ $local->actualizado_en}}</td>
            <td><a class="btn btn-primary" href="{{ url('/admin/locales/editar/'.$local->id) }}">Editar</a></td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endif
@if(!empty($paginacion))
    <div class="text-center mt-5">
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link" href="{{ url('/admin/locales/'.$paginacion['pagina_anterior']) }}">Anterior</a></li>
            @foreach($paginacion['paginas'] as $pagina)
                @if($pagina == $paginacion['pagina'])
                <li class="page-item active"><a class="page-link" href="{{ url('/admin/locales/'.$pagina) }}">{{ $pagina }}</a></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ url('/admin/locales/'.$pagina) }}">{{ $pagina }}</a></li>
                @endif
            @endforeach
            <li class="page-item"><a class="page-link" href="{{ url('/admin/locales/'.$paginacion['pagina_siguiente']) }}">Siguiente</a></li>
        </ul>
    </div>
@endif
@endsection
