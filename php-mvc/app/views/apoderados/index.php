<div class="row g-2 align-items-center mb-3">
    <div class="col">
        <h3 class="page-title">Apoderados</h3>
    </div>
    <div class="col-auto">
        <a href="<?= APP_URL ?>/apoderados/crear" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Nuevo Apoderado
        </a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-8">
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" name="buscar" class="form-control"
                           placeholder="Buscar por nombre, DNI o estudiante..."
                           value="<?= $data['buscar'] ?>">
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Buscar</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Apoderado</th>
                    <th>DNI</th>
                    <th>Parentesco</th>
                    <th>Estudiante</th>
                    <th>Contacto</th>
                    <th class="w-1">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['apoderados'])): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No hay apoderados</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['apoderados'] as $apoderado): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-sm bg-primary-lt me-2">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <div>
                                        <div class="font-weight-medium">
                                            <?= $apoderado->apellidos ?>, <?= $apoderado->nombres ?>
                                        </div>
                                        <?php if ($apoderado->es_principal): ?>
                                            <span class="badge bg-green-lt">Principal</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><?= $apoderado->dni ?></td>
                            <td>
                                <span class="badge bg-blue-lt"><?= $apoderado->parentesco ?></span>
                            </td>
                            <td>
                                <div class="small">
                                    <?= $apoderado->estudiante_nombres ?> <?= $apoderado->apellido_paterno ?>
                                </div>
                                <div class="text-muted small"><?= $apoderado->codigo ?></div>
                            </td>
                            <td>
                                <div class="small"><?= $apoderado->telefono ?></div>
                                <?php if ($apoderado->email): ?>
                                    <div class="text-muted small"><?= $apoderado->email ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="<?= APP_URL ?>/apoderados/editar/<?= $apoderado->id ?>"
                                       class="btn btn-sm" title="Editar">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>/apoderados/eliminar/<?= $apoderado->id ?>"
                                       class="btn btn-sm btn-ghost-danger"
                                       onclick="return confirm('¿Eliminar este apoderado?')"
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
