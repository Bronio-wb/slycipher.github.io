@extends('layouts.app')

@section('title', 'Práctica y Desafíos')
@section('page-title', 'Práctica y Desafíos')

@push('styles')
<style>
/* Estilo inspirado en Codedx */
.practice-container {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    min-height: 100vh;
    color: white;
}

.practice-header {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(255,255,255,0.2);
}

.challenge-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.challenge-card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 1.5rem;
    border: 1px solid rgba(255,255,255,0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.challenge-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.challenge-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    border-color: rgba(255,255,255,0.3);
}

.challenge-card:hover::before {
    opacity: 1;
}

.challenge-card.completed {
    background: rgba(76, 175, 80, 0.1);
    border-color: rgba(76, 175, 80, 0.3);
}

.challenge-card.completed::before {
    background: linear-gradient(90deg, #4caf50, #8bc34a);
    opacity: 1;
}

.difficulty-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: bold;
}

.difficulty-facil {
    background: rgba(76, 175, 80, 0.2);
    color: #4caf50;
    border: 1px solid rgba(76, 175, 80, 0.3);
}

.difficulty-medio {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.difficulty-dificil {
    background: rgba(244, 67, 54, 0.2);
    color: #f44336;
    border: 1px solid rgba(244, 67, 54, 0.3);
}

.challenge-title {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #fff;
}

.challenge-description {
    color: rgba(255,255,255,0.8);
    font-size: 0.9rem;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.challenge-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    font-size: 0.8rem;
    color: rgba(255,255,255,0.6);
}

.challenge-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-practice {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.btn-practice:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    color: white;
    transform: translateY(-1px);
}

.btn-practice.completed {
    background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    border: 1px solid rgba(255,255,255,0.1);
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
    color: #4ecdc4;
}

.stat-label {
    color: rgba(255,255,255,0.8);
    font-size: 0.9rem;
    margin-top: 0.5rem;
}

.progress-ring {
    width: 60px;
    height: 60px;
    margin: 0 auto 1rem;
}

.progress-ring circle {
    fill: none;
    stroke-width: 4;
    r: 26;
    cx: 30;
    cy: 30;
}

.progress-ring .background {
    stroke: rgba(255,255,255,0.1);
}

.progress-ring .progress {
    stroke: #4ecdc4;
    stroke-linecap: round;
    transition: stroke-dasharray 0.5s ease;
}

.section-title {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.filter-tab {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: rgba(255,255,255,0.8);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.filter-tab.active,
.filter-tab:hover {
    background: rgba(255,255,255,0.2);
    color: #fff;
    border-color: rgba(255,255,255,0.4);
}

@media (max-width: 768px) {
    .challenge-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .practice-header {
        padding: 1.5rem;
    }
}
</style>
@endpush

@section('content')
<div class="practice-container">
    <div class="container-fluid px-4 py-3">
        <!-- Header con estadísticas principales -->
        <div class="practice-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">
                        <i class="fas fa-code text-warning me-2"></i>
                        Práctica y Desafíos
                    </h1>
                    <p class="mb-0 opacity-75">
                        Mejora tus habilidades resolviendo desafíos de programación
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex align-items-center justify-content-md-end">
                        <div class="progress-ring">
                            <svg>
                                <circle class="background" />
                                <circle class="progress" 
                                        style="stroke-dasharray: {{ $stats['total_desafios'] > 0 ? ($stats['completados'] / $stats['total_desafios']) * 164 : 0 }} 164" />
                            </svg>
                        </div>
                        <div class="ms-3">
                            <div class="stat-value">{{ $stats['completados'] }}/{{ $stats['total_desafios'] }}</div>
                            <div class="stat-label">Completados</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas detalladas -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-trophy text-warning fs-2 mb-2"></i>
                <div class="stat-value">{{ $stats['puntos_ganados'] }}</div>
                <div class="stat-label">Puntos Ganados</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-fire text-danger fs-2 mb-2"></i>
                <div class="stat-value">{{ $stats['racha_actual'] }}</div>
                <div class="stat-label">Días de Racha</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-chart-line text-success fs-2 mb-2"></i>
                <div class="stat-value">{{ $stats['total_desafios'] > 0 ? round(($stats['completados'] / $stats['total_desafios']) * 100) : 0 }}%</div>
                <div class="stat-label">Progreso Total</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-clock text-info fs-2 mb-2"></i>
                <div class="stat-value">{{ $stats['total_desafios'] - $stats['completados'] }}</div>
                <div class="stat-label">Por Resolver</div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="section-title">
            <i class="fas fa-puzzle-piece"></i>
            Desafíos Disponibles
        </div>
        
        <div class="filter-tabs">
            <div class="filter-tab active" data-filter="all">Todos</div>
            <div class="filter-tab" data-filter="facil">Fácil</div>
            <div class="filter-tab" data-filter="medio">Medio</div>
            <div class="filter-tab" data-filter="dificil">Difícil</div>
            <div class="filter-tab" data-filter="completed">Completados</div>
            <div class="filter-tab" data-filter="pending">Pendientes</div>
        </div>

        <!-- Grid de desafíos -->
        <div class="challenge-grid" id="challengeGrid">
            @foreach($desafios as $desafio)
                @php
                    $isCompleted = in_array($desafio->challenge_id, $progresosCompletados);
                    $difficultyClass = 'difficulty-' . strtolower($desafio->dificultad);
                @endphp
                
                <div class="challenge-card {{ $isCompleted ? 'completed' : '' }}" 
                     data-difficulty="{{ strtolower($desafio->dificultad) }}"
                     data-status="{{ $isCompleted ? 'completed' : 'pending' }}">
                    
                    <div class="difficulty-badge {{ $difficultyClass }}">
                        {{ ucfirst($desafio->dificultad) }}
                    </div>
                    
                    @if($isCompleted)
                        <div class="position-absolute top-0 start-0 p-2">
                            <i class="fas fa-check-circle text-success fs-4"></i>
                        </div>
                    @endif
                    
                    <div class="challenge-title">{{ $desafio->titulo }}</div>
                    <div class="challenge-description">{{ Str::limit($desafio->descripcion, 120) }}</div>
                    
                    <div class="challenge-meta">
                        <span>
                            <i class="fas fa-book me-1"></i>
                            {{ $desafio->curso->titulo }}
                        </span>
                        <span>
                            <i class="fas fa-star me-1"></i>
                            {{ ucfirst($desafio->dificultad) }}
                        </span>
                    </div>
                    
                    <div class="challenge-actions">
                        <a href="{{ route('student.practica.show', $desafio->challenge_id) }}" 
                           class="btn-practice {{ $isCompleted ? 'completed' : '' }}">
                            <i class="fas fa-{{ $isCompleted ? 'eye' : 'play' }} me-1"></i>
                            {{ $isCompleted ? 'Ver Solución' : 'Resolver' }}
                        </a>
                        
                        @if($desafio->curso)
                            <a href="{{ route('cursos.show', $desafio->curso->course_id) }}" 
                               class="btn btn-outline-light btn-sm">
                                <i class="fas fa-book-open me-1"></i>
                                Curso
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($desafios->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-code fa-4x text-muted mb-3"></i>
                <h4>No hay desafíos disponibles</h4>
                <p class="text-muted">Los desafíos aparecerán aquí cuando estén disponibles.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sistema de filtros
    const filterTabs = document.querySelectorAll('.filter-tab');
    const challengeCards = document.querySelectorAll('.challenge-card');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Actualizar tabs activos
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            
            challengeCards.forEach(card => {
                const difficulty = card.dataset.difficulty;
                const status = card.dataset.status;
                
                let show = false;
                
                switch(filter) {
                    case 'all':
                        show = true;
                        break;
                    case 'facil':
                    case 'medio':
                    case 'dificil':
                        show = difficulty === filter;
                        break;
                    case 'completed':
                        show = status === 'completed';
                        break;
                    case 'pending':
                        show = status === 'pending';
                        break;
                }
                
                if (show) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeIn 0.3s ease';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    
    // Animación de entrada
    challengeCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// CSS para animaciones
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);
</script>
@endpush