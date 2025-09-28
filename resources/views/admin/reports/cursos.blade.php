<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Cursos - SLYCIPHER</title>
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
        .badge-activo { background-color: #28a745; color: white; }
        .badge-inactivo { background-color: #6c757d; color: white; }
        .badge-info { background-color: #17a2b8; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE CURSOS</h1>
        <div class="fecha">Plataforma SLYCIPHER - {{ date('d/m/Y H:i:s') }}</div>
    </div>

    <div class="estadisticas">
        <div class="stat-box">
            <div class="stat-number">{{ $estadisticas['total_cursos'] }}</div>
            <div class="stat-label">Total Cursos</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $estadisticas['activos'] }}</div>
            <div class="stat-label">Cursos Activos</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $estadisticas['total_lecciones'] }}</div>
            <div class="stat-label">Total Lecciones</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $estadisticas['cursos_con_progreso'] }}</div>
            <div class="stat-label">Con Progreso</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Categoría</th>
                <th>Lenguaje</th>
                <th>Creador</th>
                <th>Lecciones</th>
                <th>Estudiantes</th>
                <th>Estado</th>
                <th>Creación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cursos as $curso)
            <tr>
                <td>{{ $curso->course_id }}</td>
                <td>{{ $curso->titulo }}</td>
                <td>{{ $curso->categoria->nombre ?? 'N/A' }}</td>
                <td>
                    <span class="badge badge-info">{{ $curso->lenguaje->nombre ?? 'N/A' }}</span>
                </td>
                <td>{{ $curso->creador->nombre ?? 'N/A' }}</td>
                <td>{{ $curso->lecciones->count() }}</td>
                <td>{{ $curso->progresos->count() }}</td>
                <td>
                    <span class="badge badge-{{ $curso->estado }}">{{ ucfirst($curso->estado) }}</span>
                </td>
                <td>{{ $curso->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: center; color: #666; font-size: 10px;">
        Generado automáticamente por SLYCIPHER - {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>