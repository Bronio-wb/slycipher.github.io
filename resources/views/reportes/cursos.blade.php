<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Cursos - SLYCIPHER</title>
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
        .badge-principiante { background-color: #28a745; color: white; }
        .badge-intermedio { background-color: #ffc107; color: black; }
        .badge-avanzado { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SLYCIPHER</h1>
        <h2>Reporte de Cursos</h2>
        <p>Fecha de generación: {{ $fecha }}</p>
        <p>Total de cursos: {{ $cursos->count() }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Lenguaje</th>
                <th>Categoría</th>
                <th>Nivel</th>
                <th>Creador</th>
                <th>Lecciones</th>
                <th>Desafíos</th>
                <th>Fecha Creación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cursos as $curso)
            <tr>
                <td>{{ $curso->course_id }}</td>
                <td>{{ $curso->titulo }}</td>
                <td>{{ $curso->lenguaje->nombre }}</td>
                <td>{{ $curso->categoria->nombre }}</td>
                <td>
                    <span class="badge badge-{{ $curso->nivel }}">
                        {{ ucfirst($curso->nivel) }}
                    </span>
                </td>
                <td>{{ $curso->creador->nombre }} {{ $curso->creador->apellido }}</td>
                <td>{{ $curso->lecciones->count() }}</td>
                <td>{{ $curso->desafios->count() }}</td>
                <td>{{ $curso->created_at->format('d/m/Y') }}</td>
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