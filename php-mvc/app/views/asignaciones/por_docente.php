<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="page-pretitle">Asignaciones</div>
                <h2 class="page-title">
                    <?= htmlspecialchars($docente->nombre . ' ' . $docente->apellidos) ?>
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

        <div class="row">
            <!-- Formulario para agregar asignación -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-plus me-1"></i>Nueva Asignación
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="<?= APP_URL ?>/asignaciones/agregar" method="POST">
                            <input type="hidden" name="docente_id" value="<?= $docente->id ?>">

                            <div class="mb-3">
                                <label class="form-label required">Sección</label>
                                <select name="seccion_id" id="seccion_id" class="form-select" required>
                                    <option value="">Seleccione una sección</option>
                                    <?php
                                    $nivelActual = '';
                                    $gradoActual = '';
                                    foreach ($secciones as $seccion):
                                        if ($seccion->nivel_nombre != $nivelActual):
                                            if ($nivelActual != '') echo '</optgroup>';
                                            $nivelActual = $seccion->nivel_nombre;
                                            echo '<optgroup label="' . htmlspecialchars($nivelActual) . '">';
                                        endif;
                                    ?>
                                        <option value="<?= $seccion->id ?>" data-grado="<?= $seccion->grado_id ?>">
                                            <?= htmlspecialchars($seccion->grado_nombre . ' "' . $seccion->nombre . '"') ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <?php if ($nivelActual != '') echo '</optgroup>'; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Curso</label>
                                <select name="curso_id" id="curso_id" class="form-select" required disabled>
                                    <option value="">Primero seleccione una sección</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-plus me-1"></i>Agregar Asignación
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Lista de asignaciones actuales -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-list me-1"></i>Asignaciones Actuales
                            <span class="badge bg-blue ms-2"><?= count($asignaciones) ?></span>
                        </h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Nivel</th>
                                    <th>Grado</th>
                                    <th>Sección</th>
                                    <th>Curso</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($asignaciones)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            No hay asignaciones para este docente
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($asignaciones as $asignacion): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-azure-lt"><?= htmlspecialchars($asignacion->nivel_nombre) ?></span>
                                            </td>
                                            <td><?= htmlspecialchars($asignacion->grado_nombre) ?></td>
                                            <td>
                                                <span class="badge bg-purple-lt"><?= htmlspecialchars($asignacion->seccion_nombre) ?></span>
                                            </td>
                                            <td><?= htmlspecialchars($asignacion->curso_nombre) ?></td>
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
    </div>
</div>

<script>
// Cursos por grado (precargar desde PHP)
const cursosPorGrado = <?= json_encode($cursosPorGrado) ?>;

document.getElementById('seccion_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const gradoId = selectedOption.dataset.grado;
    const cursoSelect = document.getElementById('curso_id');

    // Limpiar opciones
    cursoSelect.innerHTML = '<option value="">Seleccione un curso</option>';

    if (gradoId && cursosPorGrado[gradoId]) {
        cursosPorGrado[gradoId].forEach(function(curso) {
            const option = document.createElement('option');
            option.value = curso.id;
            option.textContent = curso.nombre;
            cursoSelect.appendChild(option);
        });
        cursoSelect.disabled = false;
    } else {
        cursoSelect.innerHTML = '<option value="">No hay cursos para este grado</option>';
        cursoSelect.disabled = true;
    }
});
</script>
