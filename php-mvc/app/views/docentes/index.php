<div class="row g-2 align-items-center mb-3">
    <div class="col">
        <h3 class="page-title">Docentes</h3>
    </div>
    <div class="col-auto">
        <a href="<?= APP_URL ?>/docentes/crear" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Nuevo Docente
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Docente</th>
                    <th>DNI</th>
                    <th>Especialidad</th>
                    <th>Contacto</th>
                    <th class="text-center">Estado</th>
                    <th class="w-1">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['docentes'])): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No hay docentes registrados</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['docentes'] as $docente): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-sm bg-primary-lt me-2">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <div>
                                        <div class="font-weight-medium">
                                            <?= $docente->apellidos ?>, <?= $docente->nombre ?>
                                        </div>
                                        <div class="text-muted small"><?= $docente->email ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?= $docente->dni ?></td>
                            <td>
                                <?php if ($docente->especialidad): ?>
                                    <span class="badge bg-blue-lt"><?= $docente->especialidad ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $docente->telefono ?: '-' ?></td>
                            <td class="text-center">
                                <?php if ($docente->activo): ?>
                                    <span class="badge bg-green-lt">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-red-lt">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="<?= APP_URL ?>/docentes/asignaciones/<?= $docente->id ?>"
                                       class="btn btn-sm" title="Asignaciones">
                                        <i class="ti ti-clipboard-list"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>/docentes/editar/<?= $docente->id ?>"
                                       class="btn btn-sm" title="Editar">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>/docentes/eliminar/<?= $docente->id ?>"
                                       class="btn btn-sm btn-ghost-danger"
                                       onclick="return confirm('¿Eliminar este docente?')"
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
