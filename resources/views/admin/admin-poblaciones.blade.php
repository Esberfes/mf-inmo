@extends('admin.admin-layout')

@section('content')
<section>
    <form class="form-search" action="{{ url('/admin/poblaciones') }}" method="POST">
        <div class="row">
            <div class="col d-flex align-items-center justify-content-end">
                <input style="max-width: 400px;" name="busqueda" value="{{ Session::get(\App\Constants\SessionConstants::ADMIN_POBLACIONES_FILTER)->busqueda }}" type="search" class="form-control" placeholder="Busqueda">
                <button class="ml-3">Encontrar</button>
            </div>
            <input name="action" value="search" type="hidden">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </div>
    </form>
</section>

@if(!empty($poblaciones))
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Titulo</th>
                <th scope="col">Fecha creación</th>
                <th scope="col">Fecha modificación</th>
                <th scope="col">Editar</th>
            </tr>
        </thead>
        <tbody>
        @foreach($poblaciones as $poblacion)
        <tr>
            <th scope="row">{{ $poblacion->id }}</th>
            <td>{{ $poblacion->nombre }}</td>
            <td>{{ $poblacion->creado_en}}</td>
            <td>{{ $poblacion->actualizado_en}}</td>
            <td><a class="btn btn-primary" href="{{ url('/admin/poblaciones/editar/'.$poblacion->id) }}">Editar</a></td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endif
@if(!empty($paginacion))
    <div class="text-center mt-5">
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link" href="{{ url('/admin/poblaciones/'.$paginacion['pagina_anterior']) }}">Anterior</a></li>
            @foreach($paginacion['paginas'] as $pagina)
                @if($pagina == $paginacion['pagina'])
                <li class="page-item active"><a class="page-link" href="{{ url('/admin/poblaciones/'.$pagina) }}">{{ $pagina }}</a></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ url('/admin/poblaciones/'.$pagina) }}">{{ $pagina }}</a></li>
                @endif
            @endforeach
            <li class="page-item"><a class="page-link" href="{{ url('/admin/poblaciones/'.$paginacion['pagina_siguiente']) }}">Siguiente</a></li>
        </ul>
    </div>
@endif
@endsection
