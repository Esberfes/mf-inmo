@extends('layout')

@section('content')
<section>

    <article class="local-article">

        @if($local->imagen_principal != null)
        <img class="local-article-main-image" src="{{ url($local->imagen_principal->ruta) }}">
        @endif

        <h1>{{ $local->titulo }}</h1>

        <div class="local-article-descripcion mt-3">
            <h2>Comentario del anunciante</h2>
            {{ $local->descripcion }}
        </div>

        <div class="row">
            <div class="col-lg-6 col-12 mt-3">
                @if(!empty($local->caracteristicas))
                <div class="local-article-caracteristicas">
                    <h2>Caracteristicas</h2>
                    <ul>
                    @foreach($local->caracteristicas as $caracteristica)
                    <li>{{ $caracteristica->valor }}</li>
                    @endforeach
                    </ul>
                </div>
                @endif
            </div>
            <div class="col-lg-6 col-12">
                @if(!empty($local->edificios))
                <div class="local-article-edifico mt-3">
                    <h2>Edificio</h2>
                    <ul>
                    @foreach($local->edificios as $edificio)
                    <li>{{ $edificio->valor }}</li>
                    @endforeach
                    </ul>
                </div>
                @endif

                @if(!empty($local->equipamientos))
                <div class="local-article-edifico mt-3">
                    <h2>Equipamiento</h2>
                    <ul>
                    @foreach($local->equipamientos as $equipamiento)
                    <li>{{ $equipamiento->valor }}</li>
                    @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </article>
</section>
@endsection

@section('sidebar')
<section>
    <form class="form-solicitud mt-5" method="POST" action="">
        <h2>Preguntar al anunciante</h2>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" name="nombre">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" class="form-control" name="telefono">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="comentario">Comentario</label>
                    <textarea class="form-control" rows="3" name="comentario"></textarea>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                  <button>Conectar</button>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection
