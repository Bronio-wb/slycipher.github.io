

<?php $__env->startSection('title', 'Lista de Cursos'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-book me-2"></i>Lista de Cursos
                    </h4>
                    <div class="btn-group">
                        <?php $role = strtolower(auth()->user()->rol ?? ''); ?>

                        <?php if($role === 'admin'): ?>
                            <?php if(Route::has('admin.cursos.create')): ?>
                                <a href="<?php echo e(route('admin.cursos.create')); ?>" class="btn btn-light btn-sm">
                                    <i class="fas fa-plus me-1"></i>Nuevo Curso
                                </a>
                            <?php else: ?>
                                <button class="btn btn-light btn-sm" disabled title="Ruta admin.cursos.create no disponible">
                                    <i class="fas fa-plus me-1"></i>Nuevo Curso
                                </button>
                            <?php endif; ?>
                        <?php elseif($role === 'desarrollador' || $role === 'developer'): ?>
                            <?php if(Route::has('developer.cursos.create')): ?>
                                <a href="<?php echo e(route('developer.cursos.create')); ?>" class="btn btn-light btn-sm">
                                    <i class="fas fa-plus me-1"></i>Nuevo Curso
                                </a>
                            <?php else: ?>
                                <button class="btn btn-light btn-sm" disabled title="Ruta developer.cursos.create no disponible">
                                    <i class="fas fa-plus me-1"></i>Nuevo Curso
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar cursos...">
                        </div>
                        <div class="col-md-3">
                            <select id="categoriaFilter" class="form-select">
                                <option value="">Todas las categorías</option>
                                <?php $__currentLoopData = \App\Models\Categoria::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($categoria->nombre); ?>"><?php echo e($categoria->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="lenguajeFilter" class="form-select">
                                <option value="">Todos los lenguajes</option>
                                <?php $__currentLoopData = \App\Models\Lenguaje::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lenguaje): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($lenguaje->nombre); ?>"><?php echo e($lenguaje->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                <i class="fas fa-eraser me-1"></i>Limpiar
                            </button>
                        </div>
                    </div>

                    <!-- Lista de cursos -->
                    <?php if($cursos->count() > 0): ?>
                    <div class="row" id="cursosContainer">
                        <?php $__currentLoopData = $cursos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $curso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 col-lg-4 mb-4 curso-item" 
                             data-titulo="<?php echo e(strtolower($curso->titulo)); ?>"
                             data-categoria="<?php echo e(strtolower($curso->categoria->nombre ?? '')); ?>"
                             data-lenguaje="<?php echo e(strtolower($curso->lenguaje->nombre ?? '')); ?>">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h6 class="card-title mb-0"><?php echo e($curso->titulo); ?></h6>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="<?php echo e(route('cursos.show', $curso->course_id)); ?>">
                                                        <i class="fas fa-eye me-1"></i>Ver detalles
                                                    </a>
                                                </li>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $curso)): ?>
                                                <li>
                                                    <a class="dropdown-item" href="<?php echo e(auth()->user()->rol === 'admin' ? route('admin.cursos.edit', $curso->course_id) : route('developer.cursos.edit', $curso->course_id)); ?>">
                                                        <i class="fas fa-edit me-1"></i>Editar
                                                    </a>
                                                </li>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $curso)): ?>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button class="dropdown-item text-danger" onclick="eliminarCurso('<?php echo e($curso->course_id); ?>')">
                                                        <i class="fas fa-trash me-1"></i>Eliminar
                                                    </button>
                                                </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="card-text small text-muted mb-2"><?php echo e(Str::limit($curso->descripcion, 100)); ?></p>
                                    
                                    <div class="row text-center mb-3">
                                        <div class="col-4">
                                            <div class="text-primary">
                                                <i class="fas fa-code"></i>
                                                <div class="small"><?php echo e($curso->lenguaje->nombre ?? 'N/A'); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-success">
                                                <i class="fas fa-folder"></i>
                                                <div class="small"><?php echo e($curso->categoria->nombre ?? 'N/A'); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-info">
                                                <i class="fas fa-star"></i>
                                                <div class="small"><?php echo e($curso->dificultad); ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row text-center small text-muted">
                                        <div class="col-6">
                                            <i class="fas fa-list me-1"></i><?php echo e($curso->lecciones->count()); ?> lecciones
                                        </div>
                                        <div class="col-6">
                                            <i class="fas fa-user me-1"></i><?php echo e($curso->creador->nombre ?? 'Sin autor'); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-<?php echo e($curso->estado === 'activo' ? 'success' : ($curso->estado === 'borrador' ? 'warning' : 'secondary')); ?>">
                                            <?php echo e(ucfirst($curso->estado)); ?>

                                        </span>
                                        <small class="text-muted"><?php echo e($curso->created_at->format('d/m/Y')); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center">
                        <?php echo e($cursos->links()); ?>

                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-book fa-4x text-gray-300 mb-3"></i>
                        <h5 class="text-muted">No se encontraron cursos</h5>
                        <p class="text-muted">No hay cursos disponibles en este momento.</p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Curso::class)): ?>
                        <?php if(auth()->user()->rol === 'admin'): ?>
                            <a href="<?php echo e(route('admin.cursos.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Crear primer curso
                            </a>
                        <?php elseif(auth()->user()->rol === 'desarrollador'): ?>
                            <a href="<?php echo e(route('developer.cursos.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Crear primer curso
                            </a>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Formularios ocultos para eliminación -->
<?php $__currentLoopData = $cursos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $curso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<form id="delete-form-<?php echo e($curso->course_id); ?>" action="<?php echo e(auth()->user()->rol === 'admin' ? route('admin.cursos.destroy', $curso->course_id) : route('developer.cursos.destroy', $curso->course_id)); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Funcionalidad de búsqueda y filtrado
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoriaFilter = document.getElementById('categoriaFilter');
    const lenguajeFilter = document.getElementById('lenguajeFilter');
    const cursosContainer = document.getElementById('cursosContainer');
    const cursoItems = document.querySelectorAll('.curso-item');

    function filterCursos() {
        const searchTerm = searchInput.value.toLowerCase();
        const categoriaSelected = categoriaFilter.value.toLowerCase();
        const lenguajeSelected = lenguajeFilter.value.toLowerCase();

        cursoItems.forEach(item => {
            const titulo = item.dataset.titulo;
            const categoria = item.dataset.categoria;
            const lenguaje = item.dataset.lenguaje;

            const matchesSearch = titulo.includes(searchTerm);
            const matchesCategoria = !categoriaSelected || categoria.includes(categoriaSelected);
            const matchesLenguaje = !lenguajeSelected || lenguaje.includes(lenguajeSelected);

            if (matchesSearch && matchesCategoria && matchesLenguaje) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterCursos);
    categoriaFilter.addEventListener('change', filterCursos);
    lenguajeFilter.addEventListener('change', filterCursos);
});

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('categoriaFilter').value = '';
    document.getElementById('lenguajeFilter').value = '';
    
    // Mostrar todos los elementos
    document.querySelectorAll('.curso-item').forEach(item => {
        item.style.display = 'block';
    });
}

function eliminarCurso(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este curso?\n\nEsta acción eliminará también todas las lecciones asociadas y no se puede deshacer.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/cursos/index.blade.php ENDPATH**/ ?>