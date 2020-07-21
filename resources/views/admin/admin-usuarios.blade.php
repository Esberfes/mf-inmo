@extends('admin.admin-layout')

@section('content')

@if(!empty($usuarios))
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Email</th>
                <th scope="col">Telefono</th>
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
            <td>{{ $usuario->creado_en}}</td>
            <td><button type="button" class="btn btn-primary">Editar</button></td>
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
