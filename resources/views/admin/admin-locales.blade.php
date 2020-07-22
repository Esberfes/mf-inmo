@extends('admin.admin-layout')

@section('content')
    <section>
        <form class="form-search" action="{{ url('/admin/locales') }}" method="POST">
            <div class="row">
                <div class="col form-search-input-wrapper">
                    <input name="busqueda" value="{{ Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->busqueda }}" type="search" class="form-control" placeholder="Busqueda">
                </div>
                <div class="col form-search-input-wrapper">
                    <select name="sector" class="custom-select">
                        <option value="none">Sector (sin filtro)</option>
                        @if(!empty($sectores))
                            @foreach($sectores as $sector)
                                @if(Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->sector == $sector->id)
                                <option selected value="{{ $sector->id }}">{{ $sector->titulo }}</option>
                                @else
                                <option value="{{ $sector->id }}">{{ $sector->titulo }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col form-search-input-wrapper">
                    <select name="poblacion" class="custom-select">
                        <option value="none">Población (sin filtro)</option>
                        @if(!empty($poblaciones))
                            @foreach($poblaciones as $poblacion)
                            @if(Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->poblacion == $poblacion->id)
                                <option selected value="{{ $poblacion->id }}">{{ $poblacion->nombre }}</option>
                                @else
                                <option value="{{ $poblacion->id }}">{{ $poblacion->nombre }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col form-search-button-wrapper">
                    <button>Encontrar</button>
                </div>
                <input name="action" value="search" type="hidden">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
        </form>
        <form class="form-order-by" action="{{ url('/admin/locales') }}" method="POST">
            <button name="relevancia" value="{{ Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->order_direction == 'asc' && Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->order == 'relevancia'? 'desc' : 'asc' }}">
                Relevancia
                @if(Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->order == 'relevancia')
                    @if(Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->order_direction == 'asc')
                    <i class="fas fa-arrow-up"></i>
                    @else
                    <i class="fas fa-arrow-down"></i>
                    @endif
                @endif
            </button>
            <button name="precio" value="{{ Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->order_direction == 'asc' && Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->order == 'precio' ? 'desc' : 'asc' }}">
                Precio
                @if(Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->order == 'precio')
                    @if(Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->order_direction == 'asc')
                    <i class="fas fa-arrow-up"></i>
                    @else
                    <i class="fas fa-arrow-down"></i>
                    @endif
                @endif
            </button>
            <button name="superficie" value="{{ Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->order_direction == 'asc' && Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->order == 'superficie' ? 'desc' : 'asc' }}">
                Superficie
                @if(Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->order == 'superficie')
                    @if(Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->order_direction == 'asc')
                    <i class="fas fa-arrow-up"></i>
                    @else
                    <i class="fas fa-arrow-down"></i>
                    @endif
                @endif
            </button>
            <input name="action" value="order" type="hidden">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
    </section>
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
