<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Usuarios - SLYCIPHER</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #6366f1;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #6366f1;
            margin: 0;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #6366f1;
            color: white;
            font-weight: bold;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-admin { background-color: #dc3545; color: white; }
        .badge-desarrollador { background-color: #ffc107; color: black; }
        .badge-estudiante { background-color: #007bff; color: white; }
        .badge-activo { background-color: #28a745; color: white; }
        .badge-inactivo { background-color: #6c757d; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SLYCIPHER</h1>
        <h2>Reporte de Usuarios</h2>
        <p>Fecha de generación: {{ $fecha }}</p>
        <p>Total de usuarios: {{ $usuarios->count() }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Nombre Completo</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Último Login</th>
                <th>Racha</th>
                <th>Fecha Registro</th>
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
                    <span class="badge badge-{{ $usuario->rol }}">
                        {{ ucfirst($usuario->rol) }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ $usuario->activo ? 'activo' : 'inactivo' }}">
                        {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td>{{ $usuario->ultimo_login ? $usuario->ultimo_login->format('d/m/Y H:i') : 'Nunca' }}</td>
                <td>{{ $usuario->racha }} días</td>
                <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado automáticamente por SLYCIPHER - Plataforma de Aprendizaje</p>
        <p>© {{ date('Y') }} SLYCIPHER. Todos los derechos reservados.</p>
    </div>
</body>
</html>