<div class="row g-2 align-items-center mb-3">
    <div class="col">
        <h3 class="page-title">Asistencia Mensual</h3>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Sección</label>
                <select name="seccion" class="form-select" required>
                    <option value="">Seleccionar sección...</option>
                    <?php foreach ($data['secciones'] as $seccion): ?>
                        <option value="<?= $seccion->id ?>" <?= $data['seccionId'] == $seccion->id ? 'selected' : '' ?>>
                            <?= $seccion->nivel_nombre ?> - <?= $seccion->grado_nombre ?> "<?= $seccion->nombre ?>"
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Mes</label>
                <select name="mes" class="form-select" required>
                    <?php
                    $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                              'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                    for ($i = 1; $i <= 12; $i++):
                    ?>
                        <option value="<?= $i ?>" <?= $data['mes'] == $i ? 'selected' : '' ?>>
                            <?= $meses[$i-1] ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Año</label>
                <select name="anio" class="form-select" required>
                    <?php for ($a = date('Y'); $a >= date('Y') - 2; $a--): ?>
                        <option value="<?= $a ?>" <?= $data['anio'] == $a ? 'selected' : '' ?>><?= $a ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-5 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-search me-1"></i> Generar
                </button>
                <?php if (!empty($data['estudiantes'])): ?>
                    <button type="button" class="btn btn-outline-primary ms-2" onclick="window.print()">
                        <i class="ti ti-printer me-1"></i> Imprimir
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($data['estudiantes']) && !empty($data['diasDelMes'])): ?>
<?php
$meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
          'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <?= $data['seccion']->grado_nombre ?> "<?= $data['seccion']->nombre ?>" - <?= $meses[$data['mes']] ?> <?= $data['anio'] ?>
        </h3>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table table-sm">
            <thead>
                <tr>
                    <th class="w-1">#</th>
                    <th>Estudiante</th>
                    <?php foreach ($data['diasDelMes'] as $dia): ?>
                        <th class="text-center" style="font-size: 0.7rem; padding: 2px;"><?= $dia ?></th>
                    <?php endforeach; ?>
                    <th class="text-center">A</th>
                    <th class="text-center">F</th>
                    <th class="text-center">T</th>
                </tr>
            </thead>
            <tbody>
                <?php $num = 0; foreach ($data['estudiantes'] as $est): $num++;
                    $asistencias = 0;
                    $faltas = 0;
                    $tardanzas = 0;
                ?>
                    <tr>
                        <td class="text-muted"><?= $num ?></td>
                        <td style="font-size: 0.8rem; white-space: nowrap;">
                            <?= $est->apellido_paterno ?> <?= $est->apellido_materno ?>, <?= $est->nombres ?>
                        </td>
                        <?php foreach ($data['diasDelMes'] as $dia):
                            $estado = isset($data['asistencias'][$est->id][$dia]) ? $data['asistencias'][$est->id][$dia] : '';
                            $clase = '';
                            $texto = '';

                            switch ($estado) {
                                case 'PRESENTE':
                                    $clase = 'bg-success-lt';
                                    $texto = 'A';
                                    $asistencias++;
                                    break;
                                case 'AUSENTE':
                                    $clase = 'bg-danger-lt';
                                    $texto = 'F';
                                    $faltas++;
                                    break;
                                case 'TARDANZA':
                                    $clase = 'bg-warning-lt';
                                    $texto = 'T';
                                    $tardanzas++;
                                    break;
                                case 'JUSTIFICADO':
                                    $clase = 'bg-info-lt';
                                    $texto = 'J';
                                    break;
                                default:
                                    $texto = '-';
                            }
                        ?>
                            <td class="text-center <?= $clase ?>" style="font-size: 0.7rem; padding: 2px;"><?= $texto ?></td>
                        <?php endforeach; ?>
                        <td class="text-center fw-bold text-success"><?= $asistencias ?></td>
                        <td class="text-center fw-bold text-danger"><?= $faltas ?></td>
                        <td class="text-center fw-bold text-warning"><?= $tardanzas ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <small class="text-muted">
            <strong>Leyenda:</strong> A = Asistencia, F = Falta, T = Tardanza, J = Justificado
        </small>
    </div>
</div>
<?php elseif ($data['seccionId']): ?>
<div class="alert alert-info">
    <i class="ti ti-info-circle me-2"></i>No hay datos para mostrar
</div>
<?php endif; ?>
