@extends('layouts.app')

@section('title','Crear Lección')
@section('content')
<div class="container py-4">
    <h3>Proponer nueva Lección</h3>

    @if(session('status')) <div class="alert alert-success">{{ session('status') }}</div> @endif

    <form action="{{ route('developer.lecciones.store') }}" method="POST" class="mt-3">
        @csrf

        <div class="mb-3">
            <label class="form-label">Título</label>
            <input name="titulo" class="form-control" required value="{{ old('titulo') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4">{{ old('descripcion') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Curso</label>
            <select name="course_id" class="form-select" required>
                <option value="">-- seleccionar curso --</option>
                @foreach($cursos ?? [] as $c)
                    <option value="{{ $c->course_id ?? $c->id }}">{{ $c->titulo ?? $c->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Duración (ej. 10m)</label>
            <input name="duracion" class="form-control" value="{{ old('duracion') }}">
        </div>

        <button class="btn btn-primary rounded-action-btn">Proponer Lección</button>
    </form>
</div>
@endsection