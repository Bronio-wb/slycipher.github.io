@extends('layouts.app')

@section('title', 'Detalles del Logro')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-trophy me-2"></i>Detalles del Logro
                    </h4>
                    <div class="btn-group">
                        <a href="{{ route('logros.edit', $logro->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i>Editar
                        </a>
                        <a href="{{ route('logros.index') }}" class="btn btn-outline-dark btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Información principal del logro -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    @php
                                        $tipoEmojis = [
                                            'progreso' => '📈',
                                            'desafio' => '🎯',
                                            'tiempo' => '⏱️',
                                            'especial' => '⭐'
                                        ];
                                        $emoji = $tipoEmojis[$logro->tipo] ?? '🏆';
                                    @endphp
                                    <div class="display-4">{{ $emoji }}</div>
                                </div>
                                <div>
                                    <h2 class="text-warning mb-1">{{ $logro->nombre }}</h2>
                                    <span class="badge bg-{{ $logro->tipo == 'progreso' ? 'primary' : ($logro->tipo == 'desafio' ? 'warning' : ($logro->tipo == 'tiempo' ? 'info' : 'success')) }} fs-6">
                                        {{ ucfirst($logro->tipo) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <strong><i class="fas fa-file-text me-2"></i>Descripción:</strong>
                                <div class="bg-light p-3 rounded mt-2">
                                    {{ $logro->descripcion }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-muted">INFORMACIÓN DEL LOGRO</h6>
                                    
                                    <div class="mb-3">
                                        <div class="display-6 text-primary">{{ $logro->puntos_requeridos }}</div>
                                        <small class="text-muted">Puntos Requeridos</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        @php
                                            $estadoColors = [
                                                'activo' => ['text-success', '✅'],
                                                'inactivo' => ['text-danger', '❌'],
                                                'borrador' => ['text-warning', '📝']
                                            ];
                                            $colorClass = $estadoColors[$logro->estado][0] ?? 'text-secondary';
                                            $emoji = $estadoColors[$logro->estado][1] ?? '❓';
                                        @endphp
                                        <div class="{{ $colorClass }} fw-bold">
                                            {{ $emoji }} {{ ucfirst($logro->estado) }}
                                        </div>
                                        <small class="text-muted">Estado Actual</small>
                                    </div>
                                    
                                    <hr>
                                    <div class="small text-muted">
                                        <div><strong>Creado:</strong><br>{{ $logro->created_at->format('d/m/Y H:i') }}</div>
                                        <div class="mt-2"><strong>Actualizado:</strong><br>{{ $logro->updated_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas del logro -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="text-info mb-3">
                                <i class="fas fa-chart-bar me-2"></i>Estadísticas del Logro
                            </h5>
                            <div class="row">
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center bg-primary text-white">
                                        <div class="card-body">
                                            <div class="display-6">{{ $logro->usuariosLogros->count() }}</div>
                                            <small>Usuarios que lo Obtuvieron</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center bg-success text-white">
                                        <div class="card-body">
                                            <div class="display-6">{{ $logro->usuariosLogros->where('desbloqueado_en', '>=', now()->subDays(30))->count() }}</div>
                                            <small>Últimos 30 Días</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center bg-info text-white">
                                        <div class="card-body">
                                            <div class="display-6">{{ $logro->usuariosLogros->where('desbloqueado_en', '>=', now()->subDays(7))->count() }}</div>
                                            <small>Última Semana</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center bg-warning text-white">
                                        <div class="card-body">
                                            @php
                                                $totalUsuarios = \App\Models\User::count();
                                                $usuariosConLogro = $logro->usuariosLogros->count();
                                                $porcentaje = $totalUsuarios > 0 ? round(($usuariosConLogro / $totalUsuarios) * 100, 1) : 0;
                                            @endphp
                                            <div class="display-6">{{ $porcentaje }}%</div>
                                            <small>% de Usuarios</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de usuarios que han obtenido el logro -->
                    @if($logro->usuariosLogros->count() > 0)
                    <div class="mb-4">
                        <h5 class="text-success mb-3">
                            <i class="fas fa-users me-2"></i>Usuarios que Obtuvieron este Logro
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="fas fa-user me-1"></i>Usuario</th>
                                        <th><i class="fas fa-envelope me-1"></i>Email</th>
                                        <th><i class="fas fa-user-tag me-1"></i>Rol</th>
                                        <th><i class="fas fa-calendar me-1"></i>Fecha Obtenido</th>
                                        <th><i class="fas fa-star me-1"></i>Puntos Usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logro->usuariosLogros->sortByDesc('desbloqueado_en') as $usuarioLogro)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    @if($usuarioLogro->usuario)
                                                        <span class="badge bg-secondary">{{ strtoupper(substr($usuarioLogro->usuario->nombre, 0, 1)) }}{{ strtoupper(substr($usuarioLogro->usuario->apellido ?? '', 0, 1)) }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">??</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>{{ $usuarioLogro->usuario->nombre ?? 'N/A' }} {{ $usuarioLogro->usuario->apellido ?? '' }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $usuarioLogro->usuario->email ?? 'N/A' }}</td>
                                        <td>
                                            @if($usuarioLogro->usuario)
                                                <span class="badge bg-{{ $usuarioLogro->usuario->rol == 'admin' ? 'danger' : ($usuarioLogro->usuario->rol == 'developer' ? 'primary' : 'success') }}">
                                                    {{ ucfirst($usuarioLogro->usuario->rol ?? 'estudiante') }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($usuarioLogro->desbloqueado_en)
                                                <span class="text-muted">{{ $usuarioLogro->desbloqueado_en->format('d/m/Y H:i') }}</span>
                                                <br>
                                                <small class="text-success">{{ $usuarioLogro->desbloqueado_en->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">Sin fecha</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold text-warning">{{ $usuarioLogro->usuario->puntos_totales ?? 0 }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Sin usuarios</strong><br>
                        Aún ningún usuario ha obtenido este logro.
                    </div>
                    @endif

                    <!-- Acciones -->
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('logros.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-list me-1"></i>Volver a Lista
                                </a>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('logros.edit', $logro->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i>Editar
                                </a>
                                <button type="button" class="btn btn-danger" 
                                        data-logro-id="{{ $logro->id }}" 
                                        data-usuarios-count="{{ $logro->usuariosLogros->count() }}"
                                        onclick="confirmarEliminacion(this.dataset.logroId, this.dataset.usuariosCount)">
                                    <i class="fas fa-trash me-1"></i>Eliminar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario oculto para eliminación -->
                    <form id="delete-form-{{ $logro->id }}" action="{{ route('logros.destroy', $logro->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmarEliminacion(id, usuariosConLogro) {
    let mensaje = '¿Estás seguro de que deseas eliminar este logro?';
    
    if (usuariosConLogro > 0) {
        mensaje += '\n\nATENCIÓN: ' + usuariosConLogro + ' usuarios han obtenido este logro. Al eliminarlo, se perderán todos estos registros.';
    }
    
    mensaje += '\n\nEsta acción no se puede deshacer.';
    
    if (confirm(mensaje)) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush