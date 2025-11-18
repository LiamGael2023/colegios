<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="page-pretitle">Gestión</div>
                <h2 class="page-title">
                    <i class="ti ti-speakerphone me-2"></i>Comunicados
                </h2>
            </div>
            <?php if (in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR', 'SECRETARIA'])): ?>
            <div class="col-auto ms-auto">
                <a href="<?= APP_URL ?>/comunicados/crear" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>Nuevo Comunicado
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible">
                <div class="d-flex">
                    <div><i class="ti ti-check icon alert-icon"></i></div>
                    <div><?= $_SESSION['success'] ?></div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <div class="d-flex">
                    <div><i class="ti ti-alert-circle icon alert-icon"></i></div>
                    <div><?= $_SESSION['error'] ?></div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (empty($comunicados)): ?>
            <div class="empty">
                <div class="empty-icon">
                    <i class="ti ti-speakerphone" style="font-size: 3rem;"></i>
                </div>
                <p class="empty-title">No hay comunicados</p>
                <p class="empty-subtitle text-muted">
                    No se han publicado comunicados todavía.
                </p>
                <?php if (in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR', 'SECRETARIA'])): ?>
                <div class="empty-action">
                    <a href="<?= APP_URL ?>/comunicados/crear" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>Nuevo Comunicado
                    </a>
                </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="row row-cards">
                <?php foreach ($comunicados as $com): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-status-top <?= $com->activo ? 'bg-success' : 'bg-secondary' ?>"></div>
                            <div class="card-header">
                                <h3 class="card-title"><?= htmlspecialchars($com->titulo) ?></h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <?php
                                    $badgeClass = match($com->tipo) {
                                        'GENERAL' => 'bg-blue',
                                        'NIVEL' => 'bg-purple',
                                        'GRADO' => 'bg-cyan',
                                        'SECCION' => 'bg-orange',
                                        default => 'bg-secondary'
                                    };
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= $com->tipo ?></span>
                                    <?php if (!$com->activo): ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-muted">
                                    <?= htmlspecialchars(substr(strip_tags($com->contenido), 0, 100)) ?>...
                                </p>
                                <div class="text-muted small">
                                    <i class="ti ti-calendar me-1"></i>
                                    <?= date('d/m/Y H:i', strtotime($com->fecha_publicacion)) ?>
                                </div>
                                <div class="text-muted small">
                                    <i class="ti ti-user me-1"></i>
                                    <?= htmlspecialchars($com->usuario_nombre . ' ' . $com->usuario_apellidos) ?>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex">
                                    <a href="<?= APP_URL ?>/comunicados/ver/<?= $com->id ?>" class="btn btn-link">
                                        Ver más
                                    </a>
                                    <?php if (in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR', 'SECRETARIA'])): ?>
                                        <div class="ms-auto">
                                            <a href="<?= APP_URL ?>/comunicados/editar/<?= $com->id ?>" class="btn btn-ghost-primary btn-sm">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <a href="<?= APP_URL ?>/comunicados/cambiarEstado/<?= $com->id ?>"
                                               class="btn btn-ghost-<?= $com->activo ? 'warning' : 'success' ?> btn-sm"
                                               title="<?= $com->activo ? 'Desactivar' : 'Activar' ?>">
                                                <i class="ti ti-<?= $com->activo ? 'eye-off' : 'eye' ?>"></i>
                                            </a>
                                            <?php if (in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR'])): ?>
                                                <a href="<?= APP_URL ?>/comunicados/eliminar/<?= $com->id ?>"
                                                   class="btn btn-ghost-danger btn-sm"
                                                   onclick="return confirm('¿Eliminar este comunicado?')">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
