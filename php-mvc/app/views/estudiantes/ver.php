<?php $est = $data['estudiante']; ?>

<div class="mb-4">
    <a href="<?= APP_URL ?>/estudiantes" class="text-decoration-none">
        <i class="bi bi-arrow-left"></i> Volver a Estudiantes
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h4><?= $est->apellido_paterno ?> <?= $est->apellido_materno ?>, <?= $est->nombres ?></h4>
                <p class="text-muted mb-1">Código: <?= $est->codigo ?></p>
                <?php if ($est->nivel_nombre): ?>
                    <span class="badge bg-primary">
                        <?= $est->nivel_nombre ?> - <?= $est->grado_nombre ?> "<?= $est->seccion_nombre ?>"
                    </span>
                <?php else: ?>
                    <span class="badge bg-warning">Sin matrícula</span>
                    <a href="<?= APP_URL ?>/estudiantes/matricular/<?= $est->id ?>" class="btn btn-sm btn-outline-primary ms-2">
                        Matricular
                    </a>
                <?php endif; ?>
            </div>
            <div>
                <a href="<?= APP_URL ?>/estudiantes/editar/<?= $est->id ?>" class="btn btn-outline-primary">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                <?php if ($est->nivel_id): ?>
                    <a href="<?= APP_URL ?>/reportes/libreta/<?= $est->id ?>" class="btn btn-outline-success" target="_blank">
                        <i class="bi bi-file-text"></i> Ver Libreta
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#info">Información</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#apoderados">Apoderados</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#pagos">Pagos</a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="info">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Datos Personales</h6>
                        <table class="table table-sm">
                            <tr><td class="text-muted">DNI:</td><td><?= $est->dni ?: '-' ?></td></tr>
                            <tr><td class="text-muted">Fecha Nacimiento:</td><td><?= date('d/m/Y', strtotime($est->fecha_nacimiento)) ?></td></tr>
                            <tr><td class="text-muted">Género:</td><td><?= $est->genero ?></td></tr>
                            <tr><td class="text-muted">Nacionalidad:</td><td><?= $est->nacionalidad ?></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Contacto</h6>
                        <table class="table table-sm">
                            <tr><td class="text-muted">Dirección:</td><td><?= $est->direccion ?: '-' ?></td></tr>
                            <tr><td class="text-muted">Teléfono:</td><td><?= $est->telefono ?: '-' ?></td></tr>
                            <tr><td class="text-muted">Email:</td><td><?= $est->email ?: '-' ?></td></tr>
                        </table>
                    </div>
                </div>
                <?php if ($data['asistencia']): ?>
                    <h6 class="mt-3">Resumen de Asistencia</h6>
                    <div class="row g-2">
                        <div class="col"><span class="badge bg-success">Presente: <?= $data['asistencia']->presente ?: 0 ?></span></div>
                        <div class="col"><span class="badge bg-danger">Ausente: <?= $data['asistencia']->ausente ?: 0 ?></span></div>
                        <div class="col"><span class="badge bg-warning">Tardanza: <?= $data['asistencia']->tardanza ?: 0 ?></span></div>
                        <div class="col"><span class="badge bg-info">Justificado: <?= $data['asistencia']->justificado ?: 0 ?></span></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="apoderados">
        <div class="card">
            <div class="card-body">
                <?php if (empty($data['apoderados'])): ?>
                    <p class="text-muted text-center">No hay apoderados registrados</p>
                <?php else: ?>
                    <?php foreach ($data['apoderados'] as $apo): ?>
                        <div class="border rounded p-3 mb-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong><?= $apo->nombres ?> <?= $apo->apellidos ?></strong>
                                    <?php if ($apo->es_principal): ?>
                                        <span class="badge bg-primary ms-2">Principal</span>
                                    <?php endif; ?>
                                    <p class="text-muted mb-0 small"><?= $apo->parentesco ?></p>
                                </div>
                            </div>
                            <div class="mt-2 small">
                                <span class="me-3"><i class="bi bi-telephone"></i> <?= $apo->telefono ?></span>
                                <?php if ($apo->email): ?>
                                    <span><i class="bi bi-envelope"></i> <?= $apo->email ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="pagos">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Estado de Pagos</span>
                <a href="<?= APP_URL ?>/pagos/generar/<?= $est->id ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus"></i> Generar Cuotas
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Concepto</th>
                            <th>Mes</th>
                            <th class="text-end">Monto</th>
                            <th class="text-end">Pagado</th>
                            <th class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['pagos'])): ?>
                            <tr><td colspan="5" class="text-center text-muted">No hay pagos</td></tr>
                        <?php else: ?>
                            <?php
                            $meses = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
                            foreach ($data['pagos'] as $pago):
                            ?>
                                <tr>
                                    <td><?= $pago->concepto_nombre ?></td>
                                    <td><?= $pago->mes ? $meses[$pago->mes] : '-' ?></td>
                                    <td class="text-end">S/ <?= number_format($pago->monto, 2) ?></td>
                                    <td class="text-end">S/ <?= number_format($pago->monto_pagado, 2) ?></td>
                                    <td class="text-center">
                                        <?php
                                        $badges = [
                                            'PENDIENTE' => 'warning',
                                            'PAGADO' => 'success',
                                            'PARCIAL' => 'info',
                                            'VENCIDO' => 'danger',
                                            'ANULADO' => 'secondary'
                                        ];
                                        ?>
                                        <span class="badge bg-<?= $badges[$pago->estado] ?>"><?= $pago->estado ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
