<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Usuarios - SLYCIPHER</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #333;
            margin: 0;
        }
        .fecha {
            color: #666;
            margin-top: 10px;
        }
        .estadisticas {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .stat-box {
            display: table-cell;
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-admin { background-color: #dc3545; color: white; }
        .badge-desarrollador { background-color: #ffc107; color: black; }
        .badge-estudiante { background-color: #17a2b8; color: white; }
        .badge-activo { background-color: #28a745; color: white; }
        .badge-inactivo { background-color: #6c757d; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE USUARIOS</h1>
        <div class="fecha">Plataforma SLYCIPHER - {{ date('d/m/Y H:i:s') }}</div>
    </div>

    <div class="estadisticas">
        <div class="stat-box">
            <div class="stat-number">{{ $estadisticas['total_usuarios'] }}</div>
            <div class="stat-label">Total Usuarios</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $estadisticas['administradores'] }}</div>
            <div class="stat-label">Administradores</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $estadisticas['desarrolladores'] }}</div>
            <div class="stat-label">Desarrolladores</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $estadisticas['estudiantes'] }}</div>
            <div class="stat-label">Estudiantes</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $estadisticas['activos'] }}</div>
            <div class="stat-label">Activos</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Nombre Completo</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Registro</th>
                <th>Actividad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->id }}</td>
                <td>{{ $usuario->username }}</td>
                <td>{{ $usuario->nombre }} {{ $usuario->apellido }}</td>
                <td>{{ $usuario->email }}</td>
                <td>
                    <span class="badge badge-{{ $usuario->rol }}">{{ ucfirst($usuario->rol) }}</span>
                </td>
                <td>
                    <span class="badge badge-{{ $usuario->estado }}">{{ ucfirst($usuario->estado) }}</span>
                </td>
                <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                <td>
                    @if($usuario->rol === 'desarrollador')
                        {{ $usuario->cursosCreados->count() }} cursos, {{ $usuario->desafios->count() }} desafíos
                    @elseif($usuario->rol === 'estudiante')
                        {{ $usuario->progresos->count() }} cursos, {{ $usuario->logros->count() }} logros
                    @else
                        Sistema
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: center; color: #666; font-size: 10px;">
        Generado automáticamente por SLYCIPHER - {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>