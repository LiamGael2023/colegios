<div class="row g-2 align-items-center mb-3">
    <div class="col-auto">
        <form class="row g-2" method="GET">
            <div class="col-auto">
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" name="buscar" class="form-control" placeholder="Buscar estudiante..."
                           value="<?= $data['buscar'] ?>">
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    Buscar
                </button>
            </div>
        </form>
    </div>
    <div class="col-auto ms-auto">
        <a href="<?= APP_URL ?>/estudiantes/crear" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Nuevo Estudiante
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Estudiante</th>
                    <th>DNI</th>
                    <th>Grado/Sección</th>
                    <th>Apoderado</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['estudiantes'])): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="ti ti-mood-empty ti-lg mb-2"></i><br>
                            No se encontraron estudiantes
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['estudiantes'] as $est): ?>
                        <tr>
                            <td class="text-muted"><?= $est->codigo ?></td>
                            <td>
                                <div class="d-flex py-1 align-items-center">
                                    <span class="avatar avatar-sm bg-primary-lt me-2">
                                        <?= strtoupper(substr($est->nombres, 0, 1)) ?>
                                    </span>
                                    <div class="flex-fill">
                                        <div class="font-weight-medium"><?= $est->apellido_paterno ?> <?= $est->apellido_materno ?></div>
                                        <div class="text-muted small"><?= $est->nombres ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted"><?= $est->dni ?: '-' ?></td>
                            <td>
                                <?php if ($est->grado_nombre): ?>
                                    <span class="badge bg-blue-lt"><?= $est->grado_nombre ?> "<?= $est->seccion_nombre ?>"</span>
                                <?php else: ?>
                                    <span class="badge bg-yellow-lt">Sin matrícula</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($est->apoderado_nombres): ?>
                                    <?= $est->apoderado_nombres ?> <?= $est->apoderado_apellidos ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="<?= APP_URL ?>/estudiantes/ver/<?= $est->id ?>"
                                       class="btn btn-sm" title="Ver">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>/estudiantes/editar/<?= $est->id ?>"
                                       class="btn btn-sm" title="Editar">
                                        <i class="ti ti-edit"></i>
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
