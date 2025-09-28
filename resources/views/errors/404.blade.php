@extends('layouts.app')

@section('title', 'Página no encontrada')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center align-items-center" style="min-height: 60vh;">
        <div class="col-lg-6 text-center">
            <div class="card shadow">
                <div class="card-body py-5">
                    <div class="text-center">
                        <i class="fas fa-search fa-5x text-gray-300 mb-4"></i>
                        <h1 class="h1 text-gray-900 mb-4">404 - Página no encontrada</h1>
                        <p class="text-gray-500 mb-4">
                            Lo sentimos, la página que estás buscando no existe o ha sido movida.
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Volver
                            </a>
                            @auth
                                @if(Auth::user()->rol === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                                        <i class="fas fa-home me-2"></i>Ir al Dashboard
                                    </a>
                                @elseif(Auth::user()->rol === 'desarrollador')
                                    <a href="{{ route('developer.dashboard') }}" class="btn btn-primary">
                                        <i class="fas fa-home me-2"></i>Ir al Dashboard
                                    </a>
                                @elseif(Auth::user()->rol === 'estudiante')
                                    <a href="{{ route('student.dashboard') }}" class="btn btn-primary">
                                        <i class="fas fa-home me-2"></i>Ir al Dashboard
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-gray-900 {
    color: #3a3b45 !important;
}
.text-gray-500 {
    color: #858796 !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
</style>
@endsection