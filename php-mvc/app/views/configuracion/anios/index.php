<div class="row g-2 align-items-center mb-3">
    <div class="col">
        <h3 class="page-title">Años Escolares</h3>
    </div>
    <div class="col-auto">
        <a href="<?= APP_URL ?>/configuracion/crearAnio" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Nuevo Año
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Año</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th class="text-center">Estado</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['anios'])): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No hay años escolares</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['anios'] as $anio): ?>
                        <tr>
                            <td>
                                <div class="font-weight-medium"><?= $anio->anio ?></div>
                            </td>
                            <td><?= date('d/m/Y', strtotime($anio->fecha_inicio)) ?></td>
                            <td><?= date('d/m/Y', strtotime($anio->fecha_fin)) ?></td>
                            <td class="text-center">
                                <?php if ($anio->activo): ?>
                                    <span class="badge bg-green-lt">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-lt">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$anio->activo): ?>
                                    <a href="<?= APP_URL ?>/configuracion/activarAnio/<?= $anio->id ?>"
                                       class="btn btn-sm btn-success"
                                       onclick="return confirm('¿Activar este año escolar?')">
                                        <i class="ti ti-check me-1"></i> Activar
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
