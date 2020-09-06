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

@if(!empty($locales))
<section class="admin-table-section">
    <div class="card mb-3 admin-table-wrapper">
        <div class="admin-table-wrapper-header">
            <div class="admin-table-wrapper-header-title">
                Locales
            </div>
            <div class="admin-table-wrapper-header-info">
                @if(!empty($paginacion))
                Mostrando desde {{ $paginacion['offset'] }} a {{ ($paginacion['offset'] + $locales->count()) }} de
                {{ $paginacion['total'] }}
                @endif
            </div>
        </div>
        <div class="admin-table-wrapper-body">
            <form class="admin-table-wrapper-filters" action="{{ url('/admin/locales') }}" method="POST">
                <div class="admin-table-wrapper-filters-group">
                    <label for="poblacion">Población</label>
                    <select name="poblacion" class="custom-select">
                        <option value="none">--Sin filtro--</option>
                        @if(!empty($poblaciones))
                        @foreach($poblaciones as $poblacion)
                        @if(Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->poblacion ==
                        $poblacion->id)
                        <option selected value="{{ $poblacion->id }}">{{ $poblacion->nombre }}</option>
                        @else
                        <option value="{{ $poblacion->id }}">{{ $poblacion->nombre }}</option>
                        @endif
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="admin-table-wrapper-filters-group">
                    <label for="sector">Sector</label>
                    <select name="sector" class="custom-select">
                        <option value="none">--Sin filtro--</option>
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
                <div class="admin-table-wrapper-filters-group ">
                    <label for="busqueda">Destacados</label>
                    <select name="relevante" class="custom-select mr-3">
                        <option
                            {{ Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->relevante == -1 ? 'selected' : '' }}
                            value="-1">Todo</option>
                        <option
                            {{ Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->relevante == 0 ? 'selected' : ''}}
                            value="0">No destacado</option>
                        <option
                            {{ Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->relevante  == 1 ? 'selected' : ''}}
                            value="1">Destacados</option>
                    </select>
                </div>
                <div class="admin-table-wrapper-filters-group ">
                    <label for="busqueda">Activos</label>
                    <select name="activo" class="custom-select mr-3">
                        <option
                            {{ Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->activo == -1 ? 'selected' : '' }}
                            value="-1">Todo</option>
                        <option
                            {{ Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->activo == 0 ? 'selected' : ''}}
                            value="0">No activos</option>
                         <option
                            {{ Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->activo  == 1 ? 'selected' : ''}}
                            value="1">Activos</option>
                    </select>
                </div>
                <div class="admin-table-wrapper-filters-group">
                    <label for="busqueda">Busqueda global</label>
                    <input name="busqueda"
                        value="{{ Session::get(\App\Constants\SessionConstants::ADMIN_LOCALES_FILTER)->busqueda }}"
                        type="search" class="form-control" placeholder="--Sin filtro--">
                </div>
                <div class="admin-table-wrapper-filters-group">
                    <button class="btn btn-sm btn-outline-primary">Buscar</button>
                </div>

                <input name="actionSearch" value="search" type="hidden">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
            <table class="admin-table table-responsive">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Titulo</th>
                        <th scope="col">Sector</th>
                        <th scope="col">Población</th>
                        <th scope="col">Fecha creación</th>
                        <th scope="col">Fecha modificación</th>
                        <th scope="col">Banner</th>
                        <th scope="col">Destacado</th>
                        <th scope="col">Activo</th>
                        <th scope="col">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locales as $local)
                    <tr>
                        <td scope="row">{{ $local->id }}</th>
                        <td>{{ $local->titulo }}</td>
                        <td>{{ $local->sector->titulo}}</td>
                        <td>{{ $local->poblacion->nombre}}</td>
                        <td>{{ \Carbon\Carbon::parse($local->creado_en)->format('d/m/Y H:i:s') }}</td>
                        <td>{{ \Carbon\Carbon::parse($local->actualizado_en)->format('d/m/Y H:i:s') }}</td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                 <input type="checkbox" data-id="{{ $local->id }}" data-toggle="toggle"  class="banner-check" {{ $local->banner_activo == 1  ? 'checked' : '' }} {{ $local->banner == null ? 'disabled' : '' }} >
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                 <input type="checkbox" data-id="{{ $local->id }}" data-toggle="toggle"  class="relevante-check" {{ $local->relevante ? 'checked' : '' }} >
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                 <input type="checkbox" data-id="{{ $local->id }}" data-toggle="toggle"  class="activo-check" {{ $local->activo ? 'checked' : '' }} >
                            </div>
                        </td>
                        <td>
                            <div class="admin-table-actions-col-wrapper">
                                <a class="btn btn-sm btn-outline-primary"
                                    href="{{ url('/admin/locales/editar/'.$local->id) }}">Editar</a>
                                <form action="{{ url('/admin/locales/eliminar/'.$local->id) }}" method="post">
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
            <div class="text-center">
                <ul class="pagination justify-content-center">
                    <li class="page-item"><a class="page-link"
                            href="{{ url('/admin/locales/'.$paginacion['pagina_anterior']) }}">Anterior</a></li>
                    @foreach($paginacion['paginas'] as $pagina)
                    @if($pagina == $paginacion['pagina'])
                    <li class="page-item active"><a class="page-link"
                            href="{{ url('/admin/locales/'.$pagina) }}">{{ $pagina }}</a>
                    </li>
                    @else
                    <li class="page-item"><a class="page-link"
                            href="{{ url('/admin/locales/'.$pagina) }}">{{ $pagina }}</a></li>
                    @endif
                    @endforeach
                    <li class="page-item"><a class="page-link"
                            href="{{ url('/admin/locales/'.$paginacion['pagina_siguiente']) }}">Siguiente</a></li>
                </ul>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

@endsection

@section('scripts')
    <script>

$(".relevante-check").each(function(e){
    var input = $(this);
    var toggle = $( this ).bootstrapToggle();

    input.change(function() {
      var checked = $(this).prop('checked');
      var id = $(this).attr('data-id');

      $.ajax({
            dataType: "json",
            type : 'post',
            url: '/admin/locales/relevante/' + id,
            data: {
                checked: checked ? 1 : 0
            },
            success: function(data) {
                console.log(data)
            },
            error: function(data) {
                console.log(data)
            },
            beforeSend: function( xhr ) {
                //$("#encontrar").prop("disabled",true);
            }
        });

    });
});

$(".banner-check").each(function(e){
    var input = $(this);
    var toggle = $( this ).bootstrapToggle();

    input.change(function() {
      var checked = $(this).prop('checked');
      var id = $(this).attr('data-id');
      console.log(id)
      $.ajax({
            dataType: "json",
            type : 'post',
            url: '/admin/locales/banner/' + id,
            data: {
                checked: checked ? 1 : 0
            },
            success: function(data) {
                console.log(data)
            },
            error: function(data) {
                console.log(data)
            },
            beforeSend: function( xhr ) {
                //$("#encontrar").prop("disabled",true);
            }
        });

    });
});

$(".activo-check").each(function(e){
    var input = $(this);
    var toggle = $( this ).bootstrapToggle();

    input.change(function() {
      var checked = $(this).prop('checked');
      var id = $(this).attr('data-id');
      console.log(id)
      $.ajax({
            dataType: "json",
            type : 'post',
            url: '/admin/locales/activo/' + id,
            data: {
                checked: checked ? 1 : 0
            },
            success: function(data) {
                console.log(data)
            },
            error: function(data) {
                console.log(data)
            },
            beforeSend: function( xhr ) {
                //$("#encontrar").prop("disabled",true);
            }
        });

    });
});
    </script>
@endsection
