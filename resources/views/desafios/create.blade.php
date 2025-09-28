@extends('layouts.app')

@section('title','Crear Desafío')
@section('content')
<div class="container py-4">
    <h3>Proponer nuevo Desafío</h3>

    @if(session('status')) <div class="alert alert-success">{{ session('status') }}</div> @endif

    <form action="{{ route('developer.desafios.store') }}" method="POST" class="mt-3">
        @csrf

        <div class="mb-3">
            <label class="form-label">Título</label>
            <input name="titulo" class="form-control" required value="{{ old('titulo') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4">{{ old('descripcion') }}</textarea>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Lenguaje</label>
                <select name="language_id" class="form-select">
                    <option value="">-- seleccionar --</option>
                    @foreach($lenguajes as $l)
                        <option value="{{ $l->language_id ?? $l->id }}">{{ $l->nombre ?? $l->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Dificultad</label>
                <select name="dificultad" class="form-select">
                    <option value="principiante">Principiante</option>
                    <option value="intermedio">Intermedio</option>
                    <option value="avanzado">Avanzado</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Puntos</label>
                <input name="puntos" type="number" class="form-control" value="{{ old('puntos',10) }}">
            </div>
        </div>

        <button class="btn btn-primary rounded-action-btn">Proponer Desafío</button>
    </form>
</div>
@endsection