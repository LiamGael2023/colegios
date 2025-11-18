<?php $est = $data['estudiante']; ?>

<div class="page-header d-print-none mb-4">
    <div class="row align-items-center">
        <div class="col-auto">
            <a href="<?= APP_URL ?>/estudiantes" class="btn btn-link px-0">
                <i class="ti ti-arrow-left me-1"></i> Volver a Estudiantes
            </a>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-auto">
                <span class="avatar avatar-lg bg-primary-lt">
                    <?= strtoupper(substr($est->nombres, 0, 1) . substr($est->apellido_paterno, 0, 1)) ?>
                </span>
            </div>
            <div class="col">
                <h2 class="mb-1"><?= $est->apellido_paterno ?> <?= $est->apellido_materno ?>, <?= $est->nombres ?></h2>
                <div class="text-muted">
                    <span class="me-2">Código: <?= $est->codigo ?></span>
                    <?php if ($est->nivel_nombre): ?>
                        <span class="badge bg-blue-lt">
                            <?= $est->nivel_nombre ?> - <?= $est->grado_nombre ?> "<?= $est->seccion_nombre ?>"
                        </span>
                    <?php else: ?>
                        <span class="badge bg-yellow-lt">Sin matrícula</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-auto">
                <div class="btn-list">
                    <?php if (!$est->nivel_id): ?>
                        <a href="<?= APP_URL ?>/estudiantes/matricular/<?= $est->id ?>" class="btn btn-primary">
                            <i class="ti ti-certificate me-1"></i> Matricular
                        </a>
                    <?php endif; ?>
                    <a href="<?= APP_URL ?>/estudiantes/editar/<?= $est->id ?>" class="btn">
                        <i class="ti ti-edit me-1"></i> Editar
                    </a>
                    <?php if ($est->nivel_id): ?>
                        <a href="<?= APP_URL ?>/reportes/libreta/<?= $est->id ?>" class="btn btn-success" target="_blank">
                            <i class="ti ti-file-text me-1"></i> Ver Libreta
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
            <li class="nav-item">
                <a href="#tabs-info" class="nav-link active" data-bs-toggle="tab">
                    <i class="ti ti-info-circle me-1"></i> Información
                </a>
            </li>
            <li class="nav-item">
                <a href="#tabs-apoderados" class="nav-link" data-bs-toggle="tab">
                    <i class="ti ti-users me-1"></i> Apoderados
                </a>
            </li>
            <li class="nav-item">
                <a href="#tabs-pagos" class="nav-link" data-bs-toggle="tab">
                    <i class="ti ti-credit-card me-1"></i> Pagos
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane active show" id="tabs-info">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="subheader">Datos Personales</h4>
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">DNI</div>
                                <div class="datagrid-content"><?= $est->dni ?: '-' ?></div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Fecha Nacimiento</div>
                                <div class="datagrid-content"><?= date('d/m/Y', strtotime($est->fecha_nacimiento)) ?></div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Género</div>
                                <div class="datagrid-content"><?= $est->genero ?></div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Nacionalidad</div>
                                <div class="datagrid-content"><?= $est->nacionalidad ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 class="subheader">Contacto</h4>
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Dirección</div>
                                <div class="datagrid-content"><?= $est->direccion ?: '-' ?></div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Teléfono</div>
                                <div class="datagrid-content"><?= $est->telefono ?: '-' ?></div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Email</div>
                                <div class="datagrid-content"><?= $est->email ?: '-' ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($data['asistencia']): ?>
                    <h4 class="subheader mt-4">Resumen de Asistencia</h4>
                    <div class="row row-cards">
                        <div class="col-sm-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <span class="bg-green text-white avatar me-2">
                                            <i class="ti ti-check"></i>
                                        </span>
                                        <div>
                                            <div class="font-weight-medium"><?= $data['asistencia']->presente ?: 0 ?></div>
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
                                            <div class="font-weight-medium"><?= $data['asistencia']->ausente ?: 0 ?></div>
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
                                            <div class="font-weight-medium"><?= $data['asistencia']->tardanza ?: 0 ?></div>
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
                                            <div class="font-weight-medium"><?= $data['asistencia']->justificado ?: 0 ?></div>
                                            <div class="text-muted small">Justificado</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="tab-pane" id="tabs-apoderados">
                <?php if (empty($data['apoderados'])): ?>
                    <div class="empty">
                        <div class="empty-icon">
                            <i class="ti ti-users-group"></i>
                        </div>
                        <p class="empty-title">No hay apoderados registrados</p>
                        <p class="empty-subtitle text-muted">
                            Los apoderados aún no han sido registrados para este estudiante
                        </p>
                    </div>
                <?php else: ?>
                    <div class="row row-cards">
                        <?php foreach ($data['apoderados'] as $apo): ?>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar bg-cyan-lt me-2">
                                                <i class="ti ti-user"></i>
                                            </span>
                                            <div>
                                                <div class="font-weight-medium"><?= $apo->nombres ?> <?= $apo->apellidos ?></div>
                                                <div class="text-muted small"><?= $apo->parentesco ?></div>
                                            </div>
                                            <?php if ($apo->es_principal): ?>
                                                <span class="badge bg-primary ms-auto">Principal</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="datagrid">
                                            <div class="datagrid-item">
                                                <div class="datagrid-title">DNI</div>
                                                <div class="datagrid-content"><?= $apo->dni ?></div>
                                            </div>
                                            <div class="datagrid-item">
                                                <div class="datagrid-title">Teléfono</div>
                                                <div class="datagrid-content"><?= $apo->telefono ?></div>
                                            </div>
                                            <?php if ($apo->email): ?>
                                                <div class="datagrid-item">
                                                    <div class="datagrid-title">Email</div>
                                                    <div class="datagrid-content"><?= $apo->email ?></div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($apo->ocupacion): ?>
                                                <div class="datagrid-item">
                                                    <div class="datagrid-title">Ocupación</div>
                                                    <div class="datagrid-content"><?= $apo->ocupacion ?></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="tab-pane" id="tabs-pagos">
                <div class="d-flex justify-content-end mb-3">
                    <a href="<?= APP_URL ?>/pagos/generar/<?= $est->id ?>" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Generar Cuotas
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter">
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
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hay pagos registrados</td>
                                </tr>
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
                                                'PENDIENTE' => 'yellow',
                                                'PAGADO' => 'green',
                                                'PARCIAL' => 'azure',
                                                'VENCIDO' => 'red',
                                                'ANULADO' => 'secondary'
                                            ];
                                            ?>
                                            <span class="badge bg-<?= $badges[$pago->estado] ?>-lt"><?= $pago->estado ?></span>
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
</div>
