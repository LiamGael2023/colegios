<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="page-pretitle">Administración</div>
        <h2 class="page-title">
            <i class="ti ti-link me-2"></i>Asignaciones Docente-Curso
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

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Docentes y sus Asignaciones</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Docente</th>
                            <th>Especialidad</th>
                            <th>Email</th>
                            <th>Asignaciones</th>
                            <th class="w-1">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($docentes)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No hay docentes registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($docentes as $docente): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex py-1 align-items-center">
                                            <span class="avatar avatar-md bg-blue-lt me-2">
                                                <?= strtoupper(substr($docente->nombre, 0, 1) . substr($docente->apellidos, 0, 1)) ?>
                                            </span>
                                            <div class="flex-fill">
                                                <div class="font-weight-medium"><?= htmlspecialchars($docente->apellidos . ', ' . $docente->nombre) ?></div>
                                                <div class="text-muted">DNI: <?= htmlspecialchars($docente->dni) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($docente->especialidad ?? 'Sin especificar') ?></td>
                                    <td><?= htmlspecialchars($docente->email) ?></td>
                                    <td>
                                        <?php
                                        $asignaciones = $asignacionesPorDocente[$docente->id] ?? [];
                                        $total = count($asignaciones);
                                        ?>
                                        <?php if ($total > 0): ?>
                                            <span class="badge bg-blue"><?= $total ?> curso(s)</span>
                                            <div class="small text-muted mt-1">
                                                <?php
                                                $primeros = array_slice($asignaciones, 0, 3);
                                                $nombres = array_map(function($a) {
                                                    return $a->curso_nombre . ' (' . $a->seccion_nombre . ')';
                                                }, $primeros);
                                                echo implode(', ', $nombres);
                                                if ($total > 3) echo '...';
                                                ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Sin asignaciones</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= APP_URL ?>/asignaciones/porDocente/<?= $docente->id ?>"
                                           class="btn btn-primary btn-sm">
                                            <i class="ti ti-settings me-1"></i>Gestionar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
