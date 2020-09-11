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
                    <figure class="position-relative lozad" data-background-image="{{ url($local->imagen_principal->ruta) }} ">
                            @if($local->relevante)
                            <i class="fas fa-medal home-article-element-relevante"></i>
                            @endif
                    </figure>
                    @endif
                </div>

                <div class="col-lg-8 col-12 d-flex flex-column">
                    <div class="home-article-element-body">
                        <div class="home-article-element-body-logo">
                            <img alt="{{ $local->titulo }}" src="{{asset('img/card-mf.jpg')}}" alt="">
                        </div>

                        <div class="home-article-element-title">
                            {{ $local->titulo}}, {{ $local->poblacion->nombre}}
                        </div>
                        <div class="home-article-element-cost">
                        {{ $local->precio}}€
                        </div>
                        <div class="home-article-element-dimensioncost">
                            {{ $local->metros}} m² - {{ $local->sector->titulo}}
                        </div>
                        <div class="home-article-element-categorie">

                        </div>
                        <div class="home-article-element-description">
                        <?= \Illuminate\Support\Str::limit(strip_tags( $local->extracto), $limit = 150, $end = '...');  ?>
                        </div>

                    </div>
                    <div class="home-article-element-footer">
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

