<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Libreta de Notas - <?= $estudiante->codigo ?></title>
    <!-- Tabler Core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .card { border: none !important; box-shadow: none !important; }
        }
        .print-header {
            text-align: center;
            padding: 1rem 0;
            border-bottom: 2px solid #206bc4;
            margin-bottom: 1.5rem;
        }
        table.notas {
            width: 100%;
            border-collapse: collapse;
        }
        table.notas th, table.notas td {
            border: 1px solid #e6e8e9;
            padding: 0.5rem;
            text-align: center;
        }
        table.notas th {
            background: #f8fafc;
            font-weight: 600;
        }
        table.notas .area {
            background: #e7f1ff;
            font-weight: 600;
            text-align: left;
        }
        table.notas .curso {
            text-align: left;
            padding-left: 1.5rem;
        }
    </style>
</head>
<body class="bg-white">
    <div class="container-xl py-4">
        <div class="no-print mb-4">
            <div class="btn-list">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="ti ti-printer me-1"></i> Imprimir Libreta
                </button>
                <a href="<?= APP_URL ?>/estudiantes/ver/<?= $estudiante->id ?>" class="btn">
                    <i class="ti ti-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="print-header">
                    <h2 class="mb-1"><?= $institucion->nombre ?? 'Institución Educativa' ?></h2>
                    <p class="text-muted mb-0">LIBRETA DE NOTAS - <?= $anioActivo->anio ?></p>
                </div>

                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Estudiante</div>
                                <div class="datagrid-content fw-bold">
                                    <?= $estudiante->apellido_paterno ?> <?= $estudiante->apellido_materno ?>, <?= $estudiante->nombres ?>
                                </div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Código</div>
                                <div class="datagrid-content"><?= $estudiante->codigo ?></div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">DNI</div>
                                <div class="datagrid-content"><?= $estudiante->dni ?: '-' ?></div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Nivel / Grado</div>
                                <div class="datagrid-content">
                                    <span class="badge bg-blue-lt">
                                        <?= $estudiante->nivel_nombre ?> - <?= $estudiante->grado_nombre ?> "<?= $estudiante->seccion_nombre ?>"
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="notas mb-4">
                    <thead>
                        <tr>
                            <th style="text-align: left; width: 40%;">Área / Curso</th>
                            <?php foreach ($periodos as $p): ?>
                                <th style="width: 10%;"><?= substr($p->nombre, 0, 3) ?></th>
                            <?php endforeach; ?>
                            <th style="width: 10%;">Prom</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($notasPorArea)): ?>
                            <tr>
                                <td colspan="<?= count($periodos) + 2 ?>" class="text-center text-muted py-4">
                                    <i class="ti ti-notebook-off ti-lg mb-2"></i><br>
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
                    <div class="mb-4">
                        <h4 class="subheader">Resumen de Asistencia</h4>
                        <div class="row row-cards">
                            <div class="col-sm-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <span class="bg-green text-white avatar me-2">
                                                <i class="ti ti-check"></i>
                                            </span>
                                            <div>
                                                <div class="fw-bold"><?= $asistencia->presente ?: 0 ?></div>
                                                <div class="text-muted small">Presente</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <span class="bg-red text-white avatar me-2">
                                                <i class="ti ti-x"></i>
                                            </span>
                                            <div>
                                                <div class="fw-bold"><?= $asistencia->ausente ?: 0 ?></div>
                                                <div class="text-muted small">Ausente</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <span class="bg-yellow text-white avatar me-2">
                                                <i class="ti ti-clock"></i>
                                            </span>
                                            <div>
                                                <div class="fw-bold"><?= $asistencia->tardanza ?: 0 ?></div>
                                                <div class="text-muted small">Tardanza</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <span class="bg-azure text-white avatar me-2">
                                                <i class="ti ti-file-check"></i>
                                            </span>
                                            <div>
                                                <div class="fw-bold"><?= $asistencia->justificado ?: 0 ?></div>
                                                <div class="text-muted small">Justificado</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="text-end text-muted small">
                    Generado el <?= date('d/m/Y H:i') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabler Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
</body>
</html>
