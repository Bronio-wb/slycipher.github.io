@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-users me-2"></i>Gestión de Usuarios
                    </h4>
                    <a href="{{ route('admin.usuarios.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus me-1"></i>Nuevo Usuario
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select" id="filterRol" onchange="filtrarTabla()">
                                <option value="">Todos los roles</option>
                                <option value="admin">Administrador</option>
                                <option value="desarrollador">Desarrollador</option>
                                <option value="estudiante">Estudiante</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterEstado" onchange="filtrarTabla()">
                                <option value="">Todos los estados</option>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput" placeholder="Buscar por nombre, email o username..." onkeyup="filtrarTabla()">
                                <button class="btn btn-outline-secondary" type="button" onclick="limpiarFiltros()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de usuarios -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="usuarioTable">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-user me-1"></i>Usuario</th>
                                    <th><i class="fas fa-envelope me-1"></i>Email</th>
                                    <th><i class="fas fa-user-tag me-1"></i>Rol</th>
                                    <th><i class="fas fa-toggle-on me-1"></i>Estado</th>
                                    <th><i class="fas fa-star me-1"></i>Puntos</th>
                                    <th><i class="fas fa-calendar me-1"></i>Último Login</th>
                                    <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usuarios as $usuario)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <span class="badge bg-secondary rounded-circle" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                    {{ strtoupper(substr($usuario->nombre, 0, 1)) }}{{ strtoupper(substr($usuario->apellido ?? '', 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <strong>{{ $usuario->nombre }} {{ $usuario->apellido ?? '' }}</strong>
                                                <br>
                                                <small class="text-muted">@{{ $usuario->username }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $usuario->rol == 'admin' ? 'danger' : ($usuario->rol == 'desarrollador' ? 'primary' : 'success') }}">
                                            @if($usuario->rol == 'admin')
                                                <i class="fas fa-crown me-1"></i>Administrador
                                            @elseif($usuario->rol == 'desarrollador')
                                                <i class="fas fa-code me-1"></i>Desarrollador
                                            @else
                                                <i class="fas fa-graduation-cap me-1"></i>Estudiante
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $usuario->estado == 'activo' ? 'success' : 'danger' }}">
                                            {{ $usuario->estado == 'activo' ? '✅ Activo' : '❌ Inactivo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-warning">{{ $usuario->puntos_totales ?? 0 }}</span>
                                    </td>
                                    <td>
                                        @if($usuario->ultimo_login)
                                            <span class="text-muted">{{ $usuario->ultimo_login->format('d/m/Y H:i') }}</span>
                                            <br>
                                            <small class="text-info">{{ $usuario->ultimo_login->diffForHumans() }}</small>
                                        @else
                                            <span class="text-muted">Nunca</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.usuarios.show', $usuario->id) }}" class="btn btn-info btn-sm" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($usuario->id !== Auth::id())
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminacion('{{ $usuario->id }}')" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No hay usuarios registrados.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($usuarios->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $usuarios->links() }}
                    </div>
                    @endif

                    <!-- Formulario oculto para eliminación -->
                    <form id="delete-form" action="" method="POST" style="display: none;">
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
function filtrarTabla() {
    const rolFilter = document.getElementById('filterRol').value.toLowerCase();
    const estadoFilter = document.getElementById('filterEstado').value.toLowerCase();
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const table = document.getElementById('usuarioTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        
        if (cells.length === 0) continue; // Skip empty rows
        
        const nombre = cells[0].textContent.toLowerCase();
        const email = cells[1].textContent.toLowerCase();
        const rol = cells[2].textContent.toLowerCase();
        const estado = cells[3].textContent.toLowerCase();

        const matchesRol = !rolFilter || rol.includes(rolFilter);
        const matchesEstado = !estadoFilter || estado.includes(estadoFilter);
        const matchesSearch = !searchTerm || 
            nombre.includes(searchTerm) || 
            email.includes(searchTerm);

        if (matchesRol && matchesEstado && matchesSearch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

function limpiarFiltros() {
    document.getElementById('filterRol').value = '';
    document.getElementById('filterEstado').value = '';
    document.getElementById('searchInput').value = '';
    filtrarTabla();
}

function confirmarEliminacion(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?\n\nEsta acción no se puede deshacer y se eliminarán todos los datos asociados al usuario.')) {
        const form = document.getElementById('delete-form');
        form.action = `{{ route('admin.usuarios.index') }}/${id}`;
        form.submit();
    }
}
</script>
@endpush