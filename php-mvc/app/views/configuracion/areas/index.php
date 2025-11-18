<div class="row g-2 align-items-center mb-3">
    <div class="col">
        <h3 class="page-title">Áreas Curriculares</h3>
    </div>
    <div class="col-auto">
        <a href="<?= APP_URL ?>/configuracion/crearArea" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Nueva Área
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th class="w-1">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['areas'])): ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">No hay áreas curriculares</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['areas'] as $area): ?>
                        <tr>
                            <td>
                                <div class="font-weight-medium"><?= $area->nombre ?></div>
                            </td>
                            <td class="text-muted">
                                <?= $area->descripcion ? $area->descripcion : '-' ?>
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="<?= APP_URL ?>/configuracion/editarArea/<?= $area->id ?>" class="btn btn-sm">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>/configuracion/eliminarArea/<?= $area->id ?>"
                                       class="btn btn-sm btn-ghost-danger"
                                       onclick="return confirm('¿Eliminar esta área?')">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
