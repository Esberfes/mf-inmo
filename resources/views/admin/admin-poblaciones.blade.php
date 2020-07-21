@extends('admin.admin-layout')

@section('content')

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
            <td><button type="button" class="btn btn-primary">Editar</button></td>
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
