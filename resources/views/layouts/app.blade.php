<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SLYCIPHER') }} - @yield('title', 'Plataforma de Aprendizaje')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Analyzer Fixes CSS -->
        <link rel="stylesheet" href="{{ asset('css/analyzer-fixes.css') }}">

        <!-- Custom CSS -->
        <style>
            .navbar-brand {
                font-weight: bold;
                color: #6366f1 !important;
            }
            .sidebar {
                min-height: 100vh;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .sidebar .nav-link {
                color: rgba(255,255,255,0.8);
                padding: 0.75rem 1rem;
                margin-bottom: 0.25rem;
                border-radius: 0.5rem;
            }
            .sidebar .nav-link:hover, .sidebar .nav-link.active {
                color: white;
                background-color: rgba(255,255,255,0.1);
            }
            .main-content {
                background-color: #f8f9fa;
                min-height: 100vh;
            }
            .card {
                border: none;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
            }
            .btn-primary:hover {
                background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                @auth
                <!-- Sidebar -->
                <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                    <div class="position-sticky pt-3">
                        <div class="text-center mb-4">
                            <h4 class="text-white">SLYCIPHER</h4>
                            <small class="text-white-50">{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}</small>
                            <br>
                            <span class="badge bg-light text-dark">{{ ucfirst(Auth::user()->rol) }}</span>
                        </div>
                        
                        <ul class="nav flex-column">
                            @if(Auth::user()->rol === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.usuarios.index') }}">
                                        <i class="fas fa-users me-2"></i>Usuarios
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('cursos.index') }}">
                                        <i class="fas fa-book me-2"></i>Cursos
                                    </a>
                                </li>
                                <div class="dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-file-pdf me-2"></i>Reportes
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.reportes.usuarios') }}">Usuarios</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.reportes.cursos') }}">Cursos</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.reportes.progreso') }}">Progreso</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.reportes.logros') }}">Logros</a></li>
                                    </ul>
                                </div>
                            @elseif(Auth::user()->rol === 'desarrollador')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('developer.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('cursos.index') }}">
                                        <i class="fas fa-book me-2"></i>Mis Cursos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('lecciones.index') }}">
                                        <i class="fas fa-list me-2"></i>Lecciones
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('desafios.index') }}">
                                        <i class="fas fa-puzzle-piece me-2"></i>Desafíos
                                    </a>
                                </li>
                            @elseif(Auth::user()->rol === 'estudiante')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('student.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('student.practica.index') }}">
                                        <i class="fas fa-code me-2"></i>Práctica
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('cursos.index') }}">
                                        <i class="fas fa-book me-2"></i>Cursos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('logros.index') }}">
                                        <i class="fas fa-trophy me-2"></i>Logros
                                    </a>
                                </li>
                            @endif
                            
                            <hr class="text-white-50">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user me-2"></i>Perfil
                                </a>
                            </li>
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="nav-link" href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </div>
                </nav>
                @endauth

                <!-- Main content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                    @auth
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    @endauth

                    <!-- Alerts -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Page Content -->
                    @yield('content')
                </main>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        @stack('scripts')
    </body>
</html>
