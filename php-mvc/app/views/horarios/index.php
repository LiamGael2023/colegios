<?php $secciones = $data['secciones'] ?? []; ?>

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="page-pretitle">Gestión</div>
        <h2 class="page-title">
            <i class="ti ti-calendar-time me-2"></i>Horarios por Sección
        </h2>
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

        <div class="row row-cards">
            <?php
            $nivelActual = '';
            foreach ($secciones as $seccion):
                if ($seccion->nivel_nombre != $nivelActual):
                    if ($nivelActual != '') echo '</div><div class="row row-cards">';
                    $nivelActual = $seccion->nivel_nombre;
            ?>
                <div class="col-12">
                    <h3 class="mb-3"><?= htmlspecialchars($nivelActual) ?></h3>
                </div>
            <?php endif; ?>

                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader"><?= htmlspecialchars($seccion->grado_nombre) ?></div>
                            </div>
                            <div class="h1 mb-3"><?= htmlspecialchars($seccion->nombre) ?></div>
                            <div class="d-flex mb-2">
                                <div>
                                    <span class="text-muted">Capacidad: <?= $seccion->capacidad ?> alumnos</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="<?= APP_URL ?>/horarios/seccion/<?= $seccion->id ?>" class="btn btn-primary w-100">
                                <i class="ti ti-clock me-1"></i>Ver Horario
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
