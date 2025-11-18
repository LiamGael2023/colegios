<div class="row g-2 align-items-center mb-3">
    <div class="col">
        <h3 class="page-title">Secciones</h3>
    </div>
    <div class="col-auto">
        <a href="<?= APP_URL ?>/configuracion/crearSeccion" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Nueva Sección
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Nivel</th>
                    <th>Grado</th>
                    <th>Sección</th>
                    <th class="text-center">Capacidad</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['secciones'])): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No hay secciones</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['secciones'] as $seccion): ?>
                        <tr>
                            <td>
                                <span class="badge bg-blue-lt"><?= $seccion->nivel_nombre ?></span>
                            </td>
                            <td><?= $seccion->grado_nombre ?></td>
                            <td>
                                <div class="font-weight-medium">"<?= $seccion->nombre ?>"</div>
                            </td>
                            <td class="text-center"><?= $seccion->capacidad ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
