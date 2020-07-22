@extends('admin.admin-layout')

@section('content')

<section>
    <form class="form-search" action="{{ url('/admin/sectores') }}" method="POST">
        <div class="row">
            <div class="col d-flex align-items-center justify-content-end">
                <input style="max-width: 400px;" name="busqueda" value="{{ Session::get(\App\Constants\SessionConstants::ADMIN_SECTORES_FILTER)->busqueda }}" type="search" class="form-control" placeholder="Busqueda">
                <button class="ml-3">Encontrar</button>
            </div>
            <input name="action" value="search" type="hidden">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </div>
    </form>
</section>

@if(!empty($sectores))
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
        @foreach($sectores as $sector)
        <tr>
            <th scope="row">{{ $sector->id }}</th>
            <td>{{ $sector->titulo }}</td>
            <td>{{ $sector->creado_en}}</td>
            <td>{{ $sector->actualizado_en}}</td>
            <td><a class="btn btn-primary" href="{{ url('/admin/sectores/editar/'.$sector->id) }}">Editar</a></td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endif
@if(!empty($paginacion))
    <div class="text-center mt-5">
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link" href="{{ url('/admin/sectores/'.$paginacion['pagina_anterior']) }}">Anterior</a></li>
            @foreach($paginacion['paginas'] as $pagina)
                @if($pagina == $paginacion['pagina'])
                <li class="page-item active"><a class="page-link" href="{{ url('/admin/sectores/'.$pagina) }}">{{ $pagina }}</a></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ url('/admin/sectores/'.$pagina) }}">{{ $pagina }}</a></li>
                @endif
            @endforeach
            <li class="page-item"><a class="page-link" href="{{ url('/admin/sectores/'.$paginacion['pagina_siguiente']) }}">Siguiente</a></li>
        </ul>
    </div>
@endif
@endsection
