<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="page-pretitle">Comunicado</div>
                <h2 class="page-title"><?= htmlspecialchars($comunicado->titulo) ?></h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="<?= APP_URL ?>/comunicados" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Volver
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="ti ti-printer me-1"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-status-top <?= $comunicado->activo ? 'bg-success' : 'bg-secondary' ?>"></div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h2><?= htmlspecialchars($comunicado->titulo) ?></h2>

                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <?php
                                $badgeClass = match($comunicado->tipo) {
                                    'GENERAL' => 'bg-blue',
                                    'NIVEL' => 'bg-purple',
                                    'GRADO' => 'bg-cyan',
                                    'SECCION' => 'bg-orange',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <span class="badge <?= $badgeClass ?>">
                                    <?= $comunicado->tipo ?>
                                    <?php if ($destinatarioNombre): ?>
                                        : <?= htmlspecialchars($destinatarioNombre) ?>
                                    <?php endif; ?>
                                </span>
                                <?php if (!$comunicado->activo): ?>
                                    <span class="badge bg-secondary">Inactivo</span>
                                <?php endif; ?>
                            </div>

                            <div class="text-muted mb-3">
                                <div>
                                    <i class="ti ti-calendar me-1"></i>
                                    Publicado: <?= date('d/m/Y H:i', strtotime($comunicado->fecha_publicacion)) ?>
                                </div>
                                <div>
                                    <i class="ti ti-user me-1"></i>
                                    Por: <?= htmlspecialchars($comunicado->usuario_nombre . ' ' . $comunicado->usuario_apellidos) ?>
                                </div>
                                <?php if ($comunicado->fecha_expiracion): ?>
                                    <div>
                                        <i class="ti ti-clock me-1"></i>
                                        Expira: <?= date('d/m/Y', strtotime($comunicado->fecha_expiracion)) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr>

                        <div class="content">
                            <?= nl2br(htmlspecialchars($comunicado->contenido)) ?>
                        </div>
                    </div>

                    <?php if (in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR', 'SECRETARIA'])): ?>
                        <div class="card-footer no-print">
                            <div class="d-flex justify-content-end">
                                <a href="<?= APP_URL ?>/comunicados/editar/<?= $comunicado->id ?>" class="btn btn-outline-primary">
                                    <i class="ti ti-edit me-1"></i>Editar
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
