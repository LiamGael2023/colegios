<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="ti ti-search me-2"></i>Buscar Estudiante para Generar Pagos
        </h3>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" name="buscar" class="form-control"
                           placeholder="Buscar por nombre, apellido, DNI o código..."
                           value="<?= $data['buscar'] ?>" autofocus>
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-search me-1"></i> Buscar
                </button>
            </div>
        </form>

        <?php if ($data['buscar']): ?>
            <?php if (empty($data['estudiantes'])): ?>
                <div class="empty">
                    <div class="empty-icon">
                        <i class="ti ti-mood-empty"></i>
                    </div>
                    <p class="empty-title">No se encontraron estudiantes</p>
                    <p class="empty-subtitle text-muted">
                        Intenta con otro término de búsqueda
                    </p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Estudiante</th>
                                <th>DNI</th>
                                <th>Grado/Sección</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
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
                                        <a href="<?= APP_URL ?>/pagos/generar/<?= $est->id ?>" class="btn btn-sm btn-primary">
                                            <i class="ti ti-receipt me-1"></i> Generar Cuotas
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center text-muted py-4">
                <i class="ti ti-info-circle ti-lg mb-2"></i>
                <p class="mb-0">Ingresa un término de búsqueda para encontrar al estudiante</p>
            </div>
        <?php endif; ?>
    </div>
</div>
