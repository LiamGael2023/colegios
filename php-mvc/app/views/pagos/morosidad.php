<?php
$totalDeuda = 0;
foreach ($data['morosidad'] as $item) {
    $totalDeuda += $item['totalDeuda'];
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0"><?= count($data['morosidad']) ?> estudiantes con deudas pendientes</p>
    </div>
    <div class="text-end">
        <p class="text-muted mb-0 small">Total Deuda</p>
        <h4 class="text-danger mb-0">S/ <?= number_format($totalDeuda, 2) ?></h4>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Estudiante</th>
                    <th>Grado</th>
                    <th>Apoderado</th>
                    <th>Teléfono</th>
                    <th class="text-center">Meses</th>
                    <th class="text-end">Deuda Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['morosidad'])): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                            <p class="mb-0">No hay estudiantes morosos</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['morosidad'] as $item):
                        $est = $item['estudiante'];
                    ?>
                        <tr>
                            <td>
                                <div class="fw-medium"><?= $est->apellido_paterno ?> <?= $est->apellido_materno ?></div>
                                <small class="text-muted"><?= $est->nombres ?></small>
                            </td>
                            <td><?= $est->grado_nombre ?> "<?= $est->seccion_nombre ?>"</td>
                            <td>
                                <?php if ($est->apoderado_nombres): ?>
                                    <?= $est->apoderado_nombres ?> <?= $est->apoderado_apellidos ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= $est->apoderado_telefono ?: '-' ?></td>
                            <td class="text-center">
                                <?php
                                $mesesDeuda = [];
                                foreach ($item['pagos'] as $p) {
                                    if ($p->mes) $mesesDeuda[] = $p->mes;
                                }
                                echo implode(', ', $mesesDeuda) ?: '-';
                                ?>
                            </td>
                            <td class="text-end fw-bold text-danger">
                                S/ <?= number_format($item['totalDeuda'], 2) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
