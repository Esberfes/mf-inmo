@extends('admin.admin-layout')

@section('content')

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
            <td><button type="button" class="btn btn-primary">Editar</button></td>
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