<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SLYCIPHER</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link rel="stylesheet" href="<?php echo e(asset('css/auth.css')); ?>">

	<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

	<style>
		/* Ajustes de diseño */
		body {
			font-family: 'Inter', sans-serif;
			background: #ffffff; /* Fondo blanco */
			color: #333;
		}
		.navbar {
			background-color: rgba(0, 0, 0, 0.8); /* Fondo oscuro translúcido */
		}
		.auth-container {
			padding: 60px 0;
			text-align: center;
			color: #333;
		}
		.auth-container .logo {
			width: 150px; /* Tamaño del logo */
			height: auto;
			margin-bottom: 20px;
		}
		.auth-title {
			font-size: 2.5rem;
			font-weight: 700;
			color: #007bff; /* Azul principal */
		}
		.lead {
			font-size: 1.25rem;
			color: #555;
		}
		.btn-primary {
			background-color: #007bff; /* Azul más suave */
			border-color: #007bff;
			color: #fff;
			transition: background-color 0.3s, box-shadow 0.3s;
		}
		.btn-primary:hover {
			background-color: #0056b3; /* Azul más oscuro */
			box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
		}
		.hero-visual {
			font-size: 8rem;
			color: rgba(0, 0, 0, 0.05);
		}
		footer {
			background-color: #f8f9fa;
			color: #6c757d;
		}
		/* Ajustes menores: no sobreescribir colores de auth.css */
		*{box-sizing:border-box;margin:0;padding:0}
		html,body{height:100%}
		body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial;margin:0;}
		.container-main{padding:40px 15px;}
		.lead{opacity:.95}
		@media (max-width:768px){ .hero-visual{display:none} }
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark">
		<div class="container">
			<a class="navbar-brand d-flex align-items-center" href="#">
				<img src="<?php echo e(asset('img/Logo.jpeg')); ?>" alt="SLYCIPHER" class="logo me-2" onerror="this.style.display='none'">
				<span style="color:#ffffff; font-weight:700;">SLYCIPHER</span>
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="nav">
				<div class="ms-auto d-flex align-items-center">
					<?php if(Route::has('login')): ?>
						<?php if(auth()->guard()->guest()): ?>
							<a id="btn-login-nav" href="<?php echo e(route('login')); ?>" class="btn btn-primary me-2">Iniciar Sesión</a>
							<?php if(Route::has('register')): ?>
								<a id="btn-register-nav" href="<?php echo e(route('register')); ?>" class="btn btn-primary">Registrarse</a>
							<?php endif; ?>
						<?php else: ?>
							<!-- Usuario autenticado: los botones hacen logout y luego redirigen -->
							<button id="btn-login-nav" class="btn btn-primary me-2" type="button" onclick="logoutAndRedirect('<?php echo e(route('login')); ?>')">Iniciar Sesión</button>
							<?php if(Route::has('register')): ?>
								<button id="btn-register-nav" class="btn btn-primary" type="button" onclick="logoutAndRedirect('<?php echo e(route('register')); ?>')">Registrarse</button>
							<?php endif; ?>
							<form id="logout-form" method="POST" action="<?php echo e(route('logout')); ?>" class="d-none"><?php echo csrf_field(); ?></form>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</nav>

	<section class="auth-container">
		<div class="container">
			<img src="<?php echo e(asset('img/Logo.jpeg')); ?>" alt="SLYCIPHER" class="logo">
			<h1 class="auth-title">SLYCIPHER</h1>
			<p class="lead">Plataforma de aprendizaje con desafíos interactivos, cursos y comunidad.</p>
			<?php if(auth()->guard()->guest()): ?>
				<div class="d-flex justify-content-center gap-2 mt-3">
					<a id="btn-login-hero" href="<?php echo e(route('login')); ?>" class="btn btn-primary">
						<i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
					</a>
					<?php if(Route::has('register')): ?>
						<a id="btn-register-hero" href="<?php echo e(route('register')); ?>" class="btn btn-primary">Registrarse</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<!-- Resto del contenido (sin cambios en estructura) -->
	<main class="container-main">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<h2 class="mb-3">Qué ofrecemos</h2>
					<p class="lead">Ejercicios prácticos, seguimiento de progreso y una comunidad activa.</p>

					<?php if(auth()->guard()->guest()): ?>
						<div class="mt-4">
							<a id="btn-login-main" href="<?php echo e(route('login')); ?>" class="btn btn-primary me-2">
								<i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
							</a>
							<?php if(Route::has('register')): ?>
								<a id="btn-register-main" href="<?php echo e(route('register')); ?>" class="btn btn-primary">Registrarse</a>
							<?php endif; ?>
						</div>
					<?php else: ?>
						<div class="mt-4">
							<button id="btn-login-main" class="btn btn-primary me-2" type="button" onclick="logoutAndRedirect('<?php echo e(route('login')); ?>')">
								<i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
							</button>
							<?php if(Route::has('register')): ?>
								<button id="btn-register-main" class="btn btn-primary" type="button" onclick="logoutAndRedirect('<?php echo e(route('register')); ?>')">
									Registrarse
								</button>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="col-lg-6 text-center">
					<i class="fas fa-code" style="font-size:8rem;opacity:.12"></i>
				</div>
			</div>
		</div>
	</main>

	<section class="features">
		<div class="container">
			<div class="row g-4">
				<div class="col-md-4">
					<div class="feature-card">
						<i class="fas fa-brain fa-2x text-primary"></i>
						<h5 class="mt-3">Desafíos</h5>
						<p class="small">Ejercicios prácticos con evaluación automática.</p>
					</div>
				</div>
				<div class="col-md-4">
					<div class="feature-card">
						<i class="fas fa-chart-line fa-2x text-primary"></i>
						<h5 class="mt-3">Progreso</h5>
						<p class="small">Estadísticas y seguimiento de tu aprendizaje.</p>
					</div>
				</div>
				<div class="col-md-4">
					<div class="feature-card">
						<i class="fas fa-users fa-2x text-primary"></i>
						<h5 class="mt-3">Comunidad</h5>
						<p class="small">Foros y colaboración entre estudiantes.</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Panel de Acciones Rápidas: visible para usuarios autenticados -->
	<?php if(auth()->guard()->check()): ?>
		<div class="quick-actions" role="navigation" aria-label="Acciones rápidas">
			<?php $role = strtolower(auth()->user()->rol ?? ''); ?>

			
			<?php if($role === 'desarrollador' || $role === 'developer'): ?>
				<?php if(Route::has('developer.desafios.create')): ?>
					<a href="<?php echo e(route('developer.desafios.create')); ?>" class="qa-btn qa-create" title="Proponer nuevo desafío">
						<i class="fas fa-plus-circle"></i> Proponer Desafío
					</a>
				<?php else: ?>
					<button class="qa-btn qa-create disabled" disabled title="Ruta no disponible"> <i class="fas fa-plus-circle"></i> Proponer Desafío</button>
				<?php endif; ?>

				<?php if(Route::has('developer.lecciones.create')): ?>
					<a href="<?php echo e(route('developer.lecciones.create')); ?>" class="qa-btn qa-lesson" title="Proponer nueva lección">
						<i class="fas fa-book-medical"></i> Proponer Lección
					</a>
				<?php else: ?>
					<button class="qa-btn qa-lesson disabled" disabled title="Ruta no disponible"> <i class="fas fa-book-medical"></i> Proponer Lección</button>
				<?php endif; ?>
			<?php endif; ?>

			
			<?php if($role === 'admin' || $role === 'administrador'): ?>
				<?php if(Route::has('admin.reviews.index')): ?>
					<a href="<?php echo e(route('admin.reviews.index')); ?>" class="qa-btn qa-review" title="Revisar propuestas">
						<i class="fas fa-check-circle"></i> Revisiones
					</a>
				<?php else: ?>
					<button class="qa-btn qa-review disabled" disabled title="Ruta no disponible"><i class="fas fa-check-circle"></i> Revisiones</button>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<footer class="py-4 text-center text-muted" style="background:rgb(252, 248, 248)">
		<div class="container">© <?php echo e(date('Y')); ?> SLYCIPHER</div>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<script>
		// Envía POST a logout y redirige al path indicado. Funciona para usuarios autenticados.
		function logoutAndRedirect(targetPath) {
			const tokenMeta = document.querySelector('meta[name="csrf-token"]');
			const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
			// Hacemos POST con fetch para cerrar sesión y luego redirigir.
			fetch("<?php echo e(route('logout')); ?>", {
				method: 'POST',
				headers: {
					'X-CSRF-TOKEN': token,
					'Accept': 'application/json',
					'X-Requested-With': 'XMLHttpRequest',
					'Content-Type': 'application/json'
				},
				credentials: 'same-origin'
			}).finally(() => {
				// Siempre redirigimos al target (login o register)
				window.location.href = targetPath;
			});
		}
	</script>
</body>
</html>
			});
		}
	</script>
</body>
</html>
<?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/welcome.blade.php ENDPATH**/ ?>