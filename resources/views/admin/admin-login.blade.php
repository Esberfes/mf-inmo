@extends('admin.admin-layout')

@section('nonav')
@endsection

@section('content')
<div class="m-5 d-flex justify-content-center">
    <div class="card">
        <div class="card-header">Acceso a área privada</div>
        <div class="card-body">
            <form action="{{ url('/admin/login') }}" method="POST">
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
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email" value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label for="pass">Contraseña</label>
                    <input type="password" class="form-control" name="pass" value="{{ old('pass') }}">
                </div>

                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
</div>


@endsection
