<div class="page-header d-print-none mb-4">
    <div class="row align-items-center">
        <div class="col-auto">
            <a href="<?= APP_URL ?>/docentes" class="btn btn-link px-0">
                <i class="ti ti-arrow-left me-1"></i> Volver
            </a>
        </div>
        <div class="col">
            <h2 class="page-title">
                Asignaciones de <?= $data['docente']->nombre ?> <?= $data['docente']->apellidos ?>
            </h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-plus me-2"></i>Nueva Asignación
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= APP_URL ?>/docentes/agregarAsignacion/<?= $data['docente']->id ?>">
                    <div class="mb-3">
                        <label class="form-label required">Sección</label>
                        <select name="seccion_id" class="form-select" required>
                            <option value="">Seleccionar...</option>
                            <?php foreach ($data['secciones'] as $seccion): ?>
                                <option value="<?= $seccion->id ?>">
                                    <?= $seccion->nivel_nombre ?> - <?= $seccion->grado_nombre ?> "<?= $seccion->nombre ?>"
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Curso</label>
                        <select name="curso_id" class="form-select" required>
                            <option value="">Seleccionar...</option>
                            <?php foreach ($data['cursos'] as $curso): ?>
                                <option value="<?= $curso->id ?>">
                                    <?= $curso->nombre ?> (<?= $curso->grado_nombre ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ti ti-plus me-1"></i> Agregar Asignación
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-clipboard-list me-2"></i>Asignaciones Actuales
                </h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Sección</th>
                            <th>Curso</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['asignaciones'])): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    Sin asignaciones
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['asignaciones'] as $asig): ?>
                                <tr>
                                    <td>
                                        <div class="font-weight-medium">
                                            <?= $asig->grado_nombre ?> "<?= $asig->seccion_nombre ?>"
                                        </div>
                                        <div class="text-muted small"><?= $asig->nivel_nombre ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-cyan-lt"><?= $asig->curso_nombre ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= APP_URL ?>/docentes/eliminarAsignacion/<?= $asig->id ?>"
                                           class="btn btn-sm btn-ghost-danger"
                                           onclick="return confirm('¿Eliminar esta asignación?')">
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
