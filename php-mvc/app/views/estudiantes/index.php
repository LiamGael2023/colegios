<div class="row g-2 align-items-center mb-3">
    <div class="col-12 col-md-auto">
        <form class="row g-2" method="GET">
            <div class="col">
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
                    <i class="ti ti-search d-md-none"></i>
                    <span class="d-none d-md-inline">Buscar</span>
                </button>
            </div>
        </form>
    </div>
    <div class="col-12 col-md-auto ms-md-auto mt-2 mt-md-0">
        <a href="<?= APP_URL ?>/estudiantes/crear" class="btn btn-primary w-100 w-md-auto">
            <i class="ti ti-plus me-1"></i> Nuevo Estudiante
        </a>
    </div>
</div>

<!-- Vista Desktop -->
<div class="card d-none d-md-block">
    <div class="table-responsive">
        <table class="table table-vcenter card-table table-hover">
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
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="ti ti-mood-empty" style="font-size: 3rem;"></i>
                                </div>
                                <p class="text-muted">No se encontraron estudiantes</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['estudiantes'] as $est): ?>
                        <tr>
                            <td class="text-muted"><?= $est->codigo ?></td>
                            <td>
                                <div class="d-flex py-1 align-items-center">
                                    <?php if (!empty($est->foto)): ?>
                                        <span class="avatar avatar-sm me-2" style="background-image: url(<?= APP_URL ?>/uploads/fotos/<?= $est->foto ?>)"></span>
                                    <?php else: ?>
                                        <span class="avatar avatar-sm bg-primary-lt me-2">
                                            <?= strtoupper(substr($est->nombres, 0, 1)) ?>
                                        </span>
                                    <?php endif; ?>
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
                                       class="btn btn-sm btn-ghost-primary" title="Ver">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>/estudiantes/editar/<?= $est->id ?>"
                                       class="btn btn-sm btn-ghost-primary" title="Editar">
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

<!-- Vista Mobile (Cards) -->
<div class="d-md-none">
    <?php if (empty($data['estudiantes'])): ?>
        <div class="card">
            <div class="card-body text-center py-4">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="ti ti-mood-empty" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-muted">No se encontraron estudiantes</p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-2">
            <?php foreach ($data['estudiantes'] as $est): ?>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <?php if (!empty($est->foto)): ?>
                                    <span class="avatar avatar-md me-3" style="background-image: url(<?= APP_URL ?>/uploads/fotos/<?= $est->foto ?>)"></span>
                                <?php else: ?>
                                    <span class="avatar avatar-md bg-primary-lt me-3">
                                        <?= strtoupper(substr($est->nombres, 0, 1)) ?>
                                    </span>
                                <?php endif; ?>
                                <div class="flex-fill">
                                    <h4 class="card-title mb-1"><?= $est->apellido_paterno ?> <?= $est->apellido_materno ?></h4>
                                    <div class="text-muted"><?= $est->nombres ?></div>
                                </div>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="text-muted small">Código</div>
                                    <div class="fw-medium"><?= $est->codigo ?></div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small">DNI</div>
                                    <div class="fw-medium"><?= $est->dni ?: '-' ?></div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small">Grado/Sección</div>
                                    <div>
                                        <?php if ($est->grado_nombre): ?>
                                            <span class="badge bg-blue-lt"><?= $est->grado_nombre ?> "<?= $est->seccion_nombre ?>"</span>
                                        <?php else: ?>
                                            <span class="badge bg-yellow-lt">Sin matrícula</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small">Apoderado</div>
                                    <div class="fw-medium">
                                        <?php if ($est->apoderado_nombres): ?>
                                            <?= $est->apoderado_nombres ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="btn-list">
                                <a href="<?= APP_URL ?>/estudiantes/ver/<?= $est->id ?>" class="btn btn-primary btn-sm">
                                    <i class="ti ti-eye me-1"></i>Ver
                                </a>
                                <a href="<?= APP_URL ?>/estudiantes/editar/<?= $est->id ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="ti ti-edit me-1"></i>Editar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
