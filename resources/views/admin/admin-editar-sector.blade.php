@extends('admin.admin-layout')

@section('content')

@if(!empty($sector))


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

    <div class="card mb-3">
        <div class="card-header mb-3">Editar sector</div>
        <div class="card-body">

            <form action="{{ url('/admin/sectores/editar/' . $sector->id) }}" method="POST">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="titulo">Titulo</label>
                            <input type="text" class="form-control" name="titulo" value="{{ $sector->titulo }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" rows="6" name="descripcion">{{ $sector->descripcion }}</textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </form>
        </div>
    </div>
@endif
@endsection

@section('scripts')
<script>
    CKEDITOR.replace( 'descripcion' );
</script>
@endsection
