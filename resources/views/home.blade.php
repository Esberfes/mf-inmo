@extends('layout')


@section('nosidebar')

@endsection

@section('content')
<section>

@if(!empty($locales))
    @foreach($locales as $local)
    <article class="home-article-element">
        <a href='{{ url("/directorio/{$local->url_amigable}") }}'>
            <div class="row no-gutters">
                <div class="col-lg-4 col-12">
                    @if($local->imagen_principal != null)
                    <img class="home-article-element-mainimg" src="{{ url($local->imagen_principal->ruta) }}" class="card-img" alt="...">
                    @endif
                </div>

                <div class="col-lg-8 col-12">
                    <div class="home-article-element-body">
                        <img class="home-article-element-body-logo" src="{{asset('img/card-mf.jpg')}}" alt="">
                        <div class="home-article-element-cost">
                        {{ $local->precio}}€
                        </div>
                        <div class="home-article-element-categorie">
                        Sector: {{ $local->sector->titulo}}
                        </div>
                        <div class="home-article-element-location">
                        Poblacion: {{ $local->poblacion->nombre}}
                        </div>
                        <div class="home-article-element-relevante">
                        Relevante: {{ $local->relevante}}
                        </div>
                        <div class="home-article-element-dimensioncost">
                            {{ $local->metros}} m²
                        </div>
                        <div class="home-article-element-title">
                            {{ $local->titulo}}
                        </div>
                        <div class="home-article-element-description">
                            {{ $local->extracto}}
                        </div>
                        <div class="home-article-element-contact">
                            <div>
                                <i class="fas fa-phone"></i>{{ $local->telefono}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </article>
    @endforeach
@endif
@if(!empty($paginacion))
    <div class="text-center mt-5">
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link" href="{{ url('/'.$paginacion['pagina_anterior']) }}">Anterior</a></li>
            @foreach($paginacion['paginas'] as $pagina)
                @if($pagina == $paginacion['pagina'])
                <li class="page-item active"><a class="page-link" href="{{ url('/'.$pagina) }}">{{ $pagina }}</a></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ url('/'.$pagina) }}">{{ $pagina }}</a></li>
                @endif
            @endforeach
            <li class="page-item"><a class="page-link" href="{{ url('/'.$paginacion['pagina_siguiente']) }}">Siguiente</a></li>
        </ul>
    </div>
@endif
</section>

@endsection

