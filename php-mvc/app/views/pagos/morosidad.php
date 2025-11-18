<?php
$totalDeuda = 0;
foreach ($data['morosidad'] as $item) {
    $totalDeuda += $item['totalDeuda'];
}
?>

<div class="row align-items-center mb-4">
    <div class="col">
        <div class="text-muted"><?= count($data['morosidad']) ?> estudiantes con deudas pendientes</div>
    </div>
    <div class="col-auto">
        <div class="text-end">
            <div class="text-muted small">Total Deuda</div>
            <div class="h2 text-red mb-0">S/ <?= number_format($totalDeuda, 2) ?></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
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
                        <td colspan="6" class="text-center py-5">
                            <div class="empty">
                                <div class="empty-icon">
                                    <i class="ti ti-mood-happy text-green"></i>
                                </div>
                                <p class="empty-title">No hay estudiantes morosos</p>
                                <p class="empty-subtitle text-muted">
                                    Todos los pagos están al día
                                </p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['morosidad'] as $item):
                        $est = $item['estudiante'];
                    ?>
                        <tr>
                            <td>
                                <div class="d-flex py-1 align-items-center">
                                    <span class="avatar avatar-sm bg-red-lt me-2">
                                        <?= strtoupper(substr($est->nombres, 0, 1)) ?>
                                    </span>
                                    <div class="flex-fill">
                                        <div class="font-weight-medium"><?= $est->apellido_paterno ?> <?= $est->apellido_materno ?></div>
                                        <div class="text-muted small"><?= $est->nombres ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-blue-lt"><?= $est->grado_nombre ?> "<?= $est->seccion_nombre ?>"</span>
                            </td>
                            <td>
                                <?php if ($est->apoderado_nombres): ?>
                                    <?= $est->apoderado_nombres ?> <?= $est->apoderado_apellidos ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($est->apoderado_telefono): ?>
                                    <a href="tel:<?= $est->apoderado_telefono ?>"><?= $est->apoderado_telefono ?></a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php
                                $mesesDeuda = [];
                                foreach ($item['pagos'] as $p) {
                                    if ($p->mes) $mesesDeuda[] = $p->mes;
                                }
                                echo implode(', ', $mesesDeuda) ?: '-';
                                ?>
                            </td>
                            <td class="text-end">
                                <span class="text-red fw-bold">S/ <?= number_format($item['totalDeuda'], 2) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
