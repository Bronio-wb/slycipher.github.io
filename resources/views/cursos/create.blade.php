@extends('layouts.app')

@section('title','Proponer Curso')
@section('content')
<div class="container py-4">
	<h3>Proponer nuevo curso</h3>

	@if(session('status'))
		<div class="alert alert-success">{{ session('status') }}</div>
	@endif

	<form action="{{ $storeRoute }}" method="POST" class="mt-3">
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
			<div class="col-md-6">
				<label class="form-label">Categoría</label>
				<select name="category_id" class="form-select">
					<option value="">-- seleccionar --</option>
					@foreach($categorias as $c)
						<option value="{{ $c->category_id ?? $c->id }}">{{ $c->nombre ?? $c->name }}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="row mb-3">
			<div class="col-md-6">
				<label class="form-label">Nivel</label>
				<select name="nivel" class="form-select">
					<option value="principiante">Principiante</option>
					<option value="intermedio">Intermedio</option>
					<option value="avanzado">Avanzado</option>
				</select>
			</div>
			<div class="col-md-6">
				<label class="form-label">Estado (opcional)</label>
				<select name="estado" class="form-select">
					<option value="">Automático: pendiente</option>
					<option value="pendiente">Pendiente</option>
					<option value="activo">Activo</option>
				</select>
			</div>
		</div>

		<button class="btn btn-primary rounded-action-btn">Proponer Curso</button>
	</form>
</div>
@endsection