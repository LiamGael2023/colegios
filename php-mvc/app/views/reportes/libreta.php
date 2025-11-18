<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Libreta de Notas - <?= $estudiante->codigo ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 3px 0;
        }
        .info-box .label {
            color: #666;
            width: 120px;
        }
        table.notas {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.notas th, table.notas td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: center;
        }
        table.notas th {
            background: #f5f5f5;
        }
        table.notas .area {
            background: #e9ecef;
            font-weight: bold;
            text-align: left;
        }
        table.notas .curso {
            text-align: left;
            padding-left: 20px;
        }
        .asistencia {
            margin-top: 20px;
        }
        .asistencia span {
            display: inline-block;
            margin-right: 20px;
            padding: 5px 10px;
            border-radius: 3px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #999;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">
            Imprimir Libreta
        </button>
        <a href="<?= APP_URL ?>/estudiantes/ver/<?= $estudiante->id ?>" style="margin-left: 10px;">
            Volver
        </a>
    </div>

    <div class="header">
        <h1><?= $institucion->nombre ?? 'Institución Educativa' ?></h1>
        <p>LIBRETA DE NOTAS - <?= $anioActivo->anio ?></p>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td class="label">Estudiante:</td>
                <td><strong><?= $estudiante->apellido_paterno ?> <?= $estudiante->apellido_materno ?>, <?= $estudiante->nombres ?></strong></td>
            </tr>
            <tr>
                <td class="label">Código:</td>
                <td><?= $estudiante->codigo ?></td>
            </tr>
            <tr>
                <td class="label">DNI:</td>
                <td><?= $estudiante->dni ?: '-' ?></td>
            </tr>
            <tr>
                <td class="label">Nivel / Grado:</td>
                <td><?= $estudiante->nivel_nombre ?> - <?= $estudiante->grado_nombre ?> "<?= $estudiante->seccion_nombre ?>"</td>
            </tr>
        </table>
    </div>

    <table class="notas">
        <thead>
            <tr>
                <th style="text-align: left;">Área / Curso</th>
                <?php foreach ($periodos as $p): ?>
                    <th><?= substr($p->nombre, 0, 3) ?></th>
                <?php endforeach; ?>
                <th>Prom</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($notasPorArea)): ?>
                <tr>
                    <td colspan="<?= count($periodos) + 2 ?>" style="text-align: center; color: #999;">
                        No hay notas registradas
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($notasPorArea as $area => $cursos): ?>
                    <tr>
                        <td class="area" colspan="<?= count($periodos) + 2 ?>"><?= $area ?></td>
                    </tr>
                    <?php foreach ($cursos as $curso => $notas): ?>
                        <tr>
                            <td class="curso"><?= $curso ?></td>
                            <?php foreach ($periodos as $p): ?>
                                <td><?= $notas[$p->numero] ?? '-' ?></td>
                            <?php endforeach; ?>
                            <td><strong>-</strong></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($asistencia): ?>
        <div class="asistencia">
            <strong>Resumen de Asistencia:</strong><br><br>
            <span style="background: #d4edda;">Presente: <?= $asistencia->presente ?: 0 ?></span>
            <span style="background: #f8d7da;">Ausente: <?= $asistencia->ausente ?: 0 ?></span>
            <span style="background: #fff3cd;">Tardanza: <?= $asistencia->tardanza ?: 0 ?></span>
            <span style="background: #d1ecf1;">Justificado: <?= $asistencia->justificado ?: 0 ?></span>
        </div>
    <?php endif; ?>

    <div class="footer">
        Generado el <?= date('d/m/Y H:i') ?>
    </div>
</body>
</html>
