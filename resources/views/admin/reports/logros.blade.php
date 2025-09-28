<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Logros - SLYCIPHER</title>
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
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            color: #666;
            margin-top: 5px;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
        }
        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-progreso { background-color: #007bff; color: white; }
        .badge-desafio { background-color: #ffc107; color: black; }
        .badge-tiempo { background-color: #17a2b8; color: white; }
        .badge-especial { background-color: #28a745; color: white; }
        .section-title {
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin: 20px 0 10px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE LOGROS</h1>
        <div class="fecha">Plataforma SLYCIPHER - {{ date('d/m/Y H:i:s') }}</div>
    </div>

    <div class="estadisticas">
        <div class="stat-box">
            <div class="stat-number">{{ $estadisticas['total_logros'] }}</div>
            <div class="stat-label">Total Logros Disponibles</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $estadisticas['logros_desbloqueados'] }}</div>
            <div class="stat-label">Logros Desbloqueados</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $estadisticas['usuarios_con_logros'] }}</div>
            <div class="stat-label">Usuarios con Logros</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ Str::limit($estadisticas['logro_mas_popular'], 15) }}</div>
            <div class="stat-label">Logro Más Popular</div>
        </div>
    </div>

    <div class="section-title">LOGROS DEL SISTEMA</div>
    <table>
        <thead>
            <tr>
                <th>Logro</th>
                <th>Descripción</th>
                <th>Tipo</th>
                <th>Puntos Req.</th>
                <th>Usuarios</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logros as $logro)
            <tr>
                <td>{{ $logro->nombre }}</td>
                <td>{{ Str::limit($logro->descripcion, 40) }}</td>
                <td>
                    <span class="badge badge-{{ $logro->tipo }}">{{ ucfirst($logro->tipo) }}</span>
                </td>
                <td>{{ $logro->puntos_requeridos }}</td>
                <td>{{ $logro->usuariosLogros->count() }}</td>
                <td>{{ ucfirst($logro->estado) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">LOGROS OBTENIDOS POR USUARIOS</div>
    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Email</th>
                <th>Logro Obtenido</th>
                <th>Tipo</th>
                <th>Puntos</th>
                <th>Fecha Obtenido</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logrosUsuarios as $logroUsuario)
            <tr>
                <td>{{ $logroUsuario->usuario->nombre ?? 'N/A' }} {{ $logroUsuario->usuario->apellido ?? '' }}</td>
                <td>{{ $logroUsuario->usuario->email ?? 'N/A' }}</td>
                <td>{{ $logroUsuario->logro->nombre ?? 'N/A' }}</td>
                <td>
                    <span class="badge badge-{{ $logroUsuario->logro->tipo ?? 'progreso' }}">
                        {{ ucfirst($logroUsuario->logro->tipo ?? 'N/A') }}
                    </span>
                </td>
                <td>{{ $logroUsuario->logro->puntos_requeridos ?? 0 }}</td>
                <td>{{ $logroUsuario->desbloqueado_en ? $logroUsuario->desbloqueado_en->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: center; color: #666; font-size: 10px;">
        Generado automáticamente por SLYCIPHER - {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>