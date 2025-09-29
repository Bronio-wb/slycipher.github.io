<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SlyCipher - Autenticación</title>

	<!-- Fuentes y librerías externas -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

	<!-- Estilos exclusivos del welcome -->
	<link rel="stylesheet" href="<?php echo e(asset('css/welcome.css')); ?>">

	<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>
<body>
	<nav class="navbar navbar-expand-lg">
		<div class="container">
			<a class="navbar-brand d-flex align-items-center" href="#">
				<img src="<?php echo e(asset('img/Logo.jpeg')); ?>" alt="SLYCIPHER" class="logo me-2">
				<span class="brand-text">SLYCIPHER</span>
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="nav">
				<div class="ms-auto d-flex align-items-center">
					<?php if(Route::has('login')): ?>
						<?php if(auth()->guard()->guest()): ?>
							<a href="<?php echo e(route('login')); ?>" class="btn btn-primary me-2">Iniciar Sesión</a>
							<?php if(Route::has('register')): ?>
								<a href="<?php echo e(route('register')); ?>" class="btn btn-primary">Registrarse</a>
							<?php endif; ?>
						<?php else: ?>
							<button class="btn btn-primary me-2" type="button" onclick="logoutAndRedirect('<?php echo e(route('login')); ?>')">Iniciar Sesión</button>
							<?php if(Route::has('register')): ?>
								<button class="btn btn-primary" type="button" onclick="logoutAndRedirect('<?php echo e(route('register')); ?>')">Registrarse</button>
							<?php endif; ?>
							<form id="logout-form" method="POST" action="<?php echo e(route('logout')); ?>" class="d-none"><?php echo csrf_field(); ?></form>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</nav>

	<!-- Hero principal -->
	<section class="hero text-center">
		<img src="<?php echo e(asset('img/Logo.jpeg')); ?>" alt="SLYCIPHER" class="logo-hero">
		<h1 class="hero-title">SlyCipher</h1>
		<p class="hero-subtitle">Plataforma de aprendizaje con desafíos interactivos, cursos y comunidad.</p>

		<?php if(auth()->guard()->guest()): ?>
			<div class="mt-4">
				<a href="<?php echo e(route('login')); ?>" class="btn btn-primary me-2">
					<i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
				</a>
				<?php if(Route::has('register')): ?>
					<a href="<?php echo e(route('register')); ?>" class="btn btn-primary">Registrarse</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</section>

	<main class="container features">
		<div class="row text-center">
			<div class="col-md-4">
				<i class="fas fa-brain fa-2x text-primary"></i>
				<h5 class="mt-3">Desafíos</h5>
				<p>Ejercicios prácticos con evaluación automática.</p>
			</div>
			<div class="col-md-4">
				<i class="fas fa-chart-line fa-2x text-primary"></i>
				<h5 class="mt-3">Progreso</h5>
				<p>Estadísticas y seguimiento de tu aprendizaje.</p>
			</div>
			<div class="col-md-4">
				<i class="fas fa-users fa-2x text-primary"></i>
				<h5 class="mt-3">Comunidad</h5>
				<p>Foros y colaboración entre estudiantes.</p>
			</div>
		</div>
	</main>

	<footer class="footer text-center py-4">
		<div class="container">© <?php echo e(date('Y')); ?> SlyCipher</div>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		function logoutAndRedirect(targetPath) {
			const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
			fetch("<?php echo e(route('logout')); ?>", {
				method: 'POST',
				headers: {
					'X-CSRF-TOKEN': token,
					'Accept': 'application/json',
					'Content-Type': 'application/json'
				}
			}).finally(() => window.location.href = targetPath);
		}
	</script>
</body>
</html>

<?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/welcome.blade.php ENDPATH**/ ?>