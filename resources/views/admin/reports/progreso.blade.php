<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Progreso - SLYCIPHER</title>
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
        .badge-completado { background-color: #28a745; color: white; }
        .badge-progreso { background-color: #ffc107; color: black; }
        .badge-info { background-color: #17a2b8; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE PROGRESO</h1>
        <div class="fecha">Plataforma SLYCIPHER - {{ date('d/m/Y H:i:s') }}</div>
    </div>

    <div class="estadisticas">
        <div class="stat-box">
            <div class="stat-number">{{ $estadisticas['total_progresos'] }}</div>
            <div class="stat-label">Total Progresos</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $estadisticas['completados'] }}</div>
            <div class="stat-label">Completados</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $estadisticas['en_progreso'] }}</div>
            <div class="stat-label">En Progreso</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $estadisticas['usuarios_activos'] }}</div>
            <div class="stat-label">Usuarios Activos</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Curso</th>
                <th>Lección</th>
                <th>Lenguaje</th>
                <th>Progreso</th>
                <th>Estado</th>
                <th>Fecha Inicio</th>
                <th>Completado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($progresos as $progreso)
            <tr>
                <td>{{ $progreso->usuario->nombre ?? 'N/A' }} {{ $progreso->usuario->apellido ?? '' }}</td>
                <td>{{ $progreso->leccion->curso->titulo ?? 'N/A' }}</td>
                <td>{{ $progreso->leccion->titulo ?? 'N/A' }}</td>
                <td>
                    <span class="badge badge-info">{{ $progreso->leccion->curso->lenguaje->nombre ?? 'N/A' }}</span>
                </td>
                <td>{{ $progreso->progreso }}%</td>
                <td>
                    <span class="badge badge-{{ $progreso->completado ? 'completado' : 'progreso' }}">
                        {{ $progreso->completado ? 'Completado' : 'En Progreso' }}
                    </span>
                </td>
                <td>{{ $progreso->fecha_inicio ? $progreso->fecha_inicio->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $progreso->completado_en ? $progreso->completado_en->format('d/m/Y') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: center; color: #666; font-size: 10px;">
        Generado automáticamente por SLYCIPHER - {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>