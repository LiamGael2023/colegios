<div class="row g-2 align-items-center mb-3">
    <div class="col">
        <h3 class="page-title">Grados</h3>
    </div>
    <div class="col-auto">
        <a href="<?= APP_URL ?>/configuracion/crearGrado" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Nuevo Grado
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
                    <th class="text-center">Número</th>
                    <th class="w-1">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['grados'])): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No hay grados</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['grados'] as $grado): ?>
                        <tr>
                            <td>
                                <span class="badge bg-blue-lt"><?= $grado->nivel_nombre ?></span>
                            </td>
                            <td>
                                <div class="font-weight-medium"><?= $grado->nombre ?></div>
                            </td>
                            <td class="text-center"><?= $grado->numero ?></td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="<?= APP_URL ?>/configuracion/editarGrado/<?= $grado->id ?>"
                                       class="btn btn-sm" title="Editar">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>/configuracion/eliminarGrado/<?= $grado->id ?>"
                                       class="btn btn-sm btn-ghost-danger"
                                       onclick="return confirm('¿Eliminar este grado?')"
                                       title="Eliminar">
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
