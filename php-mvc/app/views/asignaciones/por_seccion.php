<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="page-pretitle">Asignaciones por Sección</div>
                <h2 class="page-title">
                    <?= htmlspecialchars($seccion->grado_nombre . ' "' . $seccion->nombre . '"') ?>
                </h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="<?= APP_URL ?>/asignaciones" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cursos y Docentes Asignados</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Docente</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($asignaciones)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    No hay asignaciones para esta sección
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($asignaciones as $asignacion): ?>
                                <tr>
                                    <td>
                                        <div class="font-weight-medium"><?= htmlspecialchars($asignacion->curso_nombre) ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($asignacion->docente_nombre) ?></td>
                                    <td>
                                        <a href="<?= APP_URL ?>/asignaciones/eliminar/<?= $asignacion->id ?>"
                                           class="btn btn-ghost-danger btn-sm"
                                           onclick="return confirm('¿Está seguro de eliminar esta asignación?')">
                                            <i class="ti ti-trash"></i>
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
