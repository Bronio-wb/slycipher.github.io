@extends('layouts.app')
@section('title','Revisiones pendientes')
@section('content')
<div class="container py-4">
    <h3>Revisiones pendientes</h3>

    <h5 class="mt-4">Desafíos propuestos</h5>
    @foreach($desafios as $d)
        <div class="card mb-2 p-3">
            <strong>{{ $d->titulo ?? '–' }}</strong>
            <p class="mb-1">{{ Str::limit($d->descripcion,200) }}</p>
            <form method="POST" action="{{ route('admin.reviews.desafio.approve', $d->id) }}" style="display:inline-block;">
                @csrf
                <button class="btn btn-success rounded-action-btn">Aprobar</button>
            </form>
        </div>
    @endforeach

    <h5 class="mt-4">Lecciones propuestas</h5>
    @foreach($lecciones as $l)
        <div class="card mb-2 p-3">
            <strong>{{ $l->titulo ?? '–' }}</strong>
            <p class="mb-1">{{ Str::limit($l->descripcion,200) }}</p>
            <form method="POST" action="{{ route('admin.reviews.leccion.approve', $l->id) }}" style="display:inline-block;">
                @csrf
                <button class="btn btn-success rounded-action-btn">Aprobar</button>
            </form>
        </div>
    @endforeach
</div>
@endsection
