<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Matrícula</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 18px;
            margin: 5px 0;
        }
        .header h2 {
            font-size: 14px;
            font-weight: normal;
            margin: 5px 0;
        }
        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 40px 0;
            text-decoration: underline;
        }
        .content {
            text-align: justify;
            margin: 20px 0;
        }
        .content p {
            margin: 15px 0;
        }
        .data-table {
            margin: 20px 0;
            width: 100%;
        }
        .data-table td {
            padding: 5px 0;
        }
        .data-table td:first-child {
            font-weight: bold;
            width: 200px;
        }
        .footer {
            margin-top: 60px;
            text-align: center;
        }
        .signature {
            margin-top: 80px;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin: 0 auto;
            padding-top: 5px;
        }
        @media print {
            body { margin: 20px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">
            Imprimir Constancia
        </button>
        <button onclick="window.history.back()" style="padding: 10px 20px; cursor: pointer; margin-left: 10px;">
            Volver
        </button>
    </div>

    <div class="header">
        <h1><?= $institucion->nombre ?? 'Institución Educativa' ?></h1>
        <h2><?= $institucion->direccion ?? '' ?></h2>
        <h2>UGEL <?= $institucion->ugel ?? '' ?></h2>
    </div>

    <div class="title">CONSTANCIA DE MATRÍCULA</div>

    <div class="content">
        <p>El Director de la <?= $institucion->nombre ?? 'Institución Educativa' ?>, con código modular N° <?= $institucion->codigo_modular ?? '' ?>:</p>

        <p><strong>HACE CONSTAR:</strong></p>

        <p>Que, <?= $estudiante->nombres ?? '' ?> <?= $estudiante->apellido_paterno ?? '' ?> <?= $estudiante->apellido_materno ?? '' ?>, identificado(a) con DNI N° <?= $estudiante->dni ?? 'Sin DNI' ?>, se encuentra debidamente matriculado(a) en esta Institución Educativa para el año escolar <?= $anioActivo->anio ?? date('Y') ?>, según el siguiente detalle:</p>

        <table class="data-table">
            <tr>
                <td>Código del Estudiante:</td>
                <td><?= $estudiante->codigo ?? '' ?></td>
            </tr>
            <tr>
                <td>Nivel:</td>
                <td><?= $matricula->nivel_nombre ?? '' ?></td>
            </tr>
            <tr>
                <td>Grado:</td>
                <td><?= $matricula->grado_nombre ?? '' ?></td>
            </tr>
            <tr>
                <td>Sección:</td>
                <td>"<?= $matricula->seccion_nombre ?? '' ?>"</td>
            </tr>
            <tr>
                <td>Fecha de Matrícula:</td>
                <td><?= $matricula ? date('d/m/Y', strtotime($matricula->fecha_matricula)) : '' ?></td>
            </tr>
            <tr>
                <td>Estado:</td>
                <td><?= $matricula->estado ?? '' ?></td>
            </tr>
        </table>

        <p>Se expide la presente constancia a solicitud del interesado(a) para los fines que estime conveniente.</p>
    </div>

    <div class="footer">
        <p><?= $institucion->direccion ?? '' ?>, <?= date('d') ?> de <?= strftime('%B', time()) ?> de <?= date('Y') ?></p>
    </div>

    <div class="signature">
        <div class="signature-line">
            Director(a)
        </div>
    </div>
</body>
</html>
