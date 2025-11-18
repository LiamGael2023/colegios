<div class="page-header d-print-none mb-4">
    <div class="row align-items-center">
        <div class="col-auto">
            <a href="<?= APP_URL ?>/configuracion/anios" class="btn btn-link px-0">
                <i class="ti ti-arrow-left me-1"></i> Volver a Años
            </a>
        </div>
        <div class="col">
            <h2 class="page-title">
                Períodos del Año <?= $data['anio']->anio ?>
            </h2>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Período</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th class="text-center">Estado</th>
                    <th class="w-1">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['periodos'] as $periodo): ?>
                    <tr>
                        <td>
                            <div class="font-weight-medium"><?= $periodo->nombre ?></div>
                        </td>
                        <td>
                            <?= $periodo->fecha_inicio ? date('d/m/Y', strtotime($periodo->fecha_inicio)) : '<span class="text-muted">Sin definir</span>' ?>
                        </td>
                        <td>
                            <?= $periodo->fecha_fin ? date('d/m/Y', strtotime($periodo->fecha_fin)) : '<span class="text-muted">Sin definir</span>' ?>
                        </td>
                        <td class="text-center">
                            <?php if ($periodo->activo): ?>
                                <span class="badge bg-green-lt">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary-lt">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-list flex-nowrap">
                                <a href="<?= APP_URL ?>/configuracion/editarPeriodo/<?= $periodo->id ?>"
                                   class="btn btn-sm" title="Editar fechas">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <?php if (!$periodo->activo): ?>
                                    <a href="<?= APP_URL ?>/configuracion/activarPeriodo/<?= $periodo->id ?>"
                                       class="btn btn-sm btn-ghost-success" title="Activar">
                                        <i class="ti ti-check"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
