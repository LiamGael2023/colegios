<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="page-pretitle">Matrículas</div>
                <h2 class="page-title">Nueva Matrícula - <?= $anio->anio ?></h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="<?= APP_URL ?>/matriculas" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
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

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Datos de Matrícula</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($estudiantes)): ?>
                            <div class="alert alert-info">
                                <i class="ti ti-info-circle me-2"></i>
                                No hay estudiantes disponibles para matricular. Todos los estudiantes activos ya tienen matrícula en el año <?= $anio->anio ?>.
                            </div>
                            <div class="text-center">
                                <a href="<?= APP_URL ?>/estudiantes/crear" class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i>Registrar Nuevo Estudiante
                                </a>
                            </div>
                        <?php else: ?>
                            <form action="<?= APP_URL ?>/matriculas/nueva" method="POST">
                                <div class="mb-3">
                                    <label class="form-label required">Estudiante</label>
                                    <select name="estudiante_id" class="form-select" required>
                                        <option value="">Seleccione un estudiante...</option>
                                        <?php foreach ($estudiantes as $est): ?>
                                            <option value="<?= $est->id ?>">
                                                <?= htmlspecialchars($est->apellido_paterno . ' ' . $est->apellido_materno . ', ' . $est->nombres) ?>
                                                <?= $est->dni ? ' - DNI: ' . $est->dni : '' ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Sección</label>
                                    <select name="seccion_id" class="form-select" required>
                                        <option value="">Seleccione una sección...</option>
                                        <?php
                                        $nivelActual = '';
                                        foreach ($secciones as $seccion):
                                            if ($seccion->nivel_nombre != $nivelActual):
                                                if ($nivelActual != '') echo '</optgroup>';
                                                $nivelActual = $seccion->nivel_nombre;
                                                echo '<optgroup label="' . htmlspecialchars($nivelActual) . '">';
                                            endif;
                                        ?>
                                            <option value="<?= $seccion->id ?>">
                                                <?= htmlspecialchars($seccion->grado_nombre . ' "' . $seccion->nombre . '"') ?>
                                                (Cap: <?= $seccion->capacidad ?>)
                                            </option>
                                        <?php endforeach; ?>
                                        <?php if ($nivelActual != '') echo '</optgroup>'; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Tipo de Matrícula</label>
                                    <select name="tipo_matricula" class="form-select" required>
                                        <option value="REGULAR">Regular</option>
                                        <option value="TRASLADO">Traslado</option>
                                        <option value="REINGRESO">Reingreso</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Procedencia (IE anterior)</label>
                                    <input type="text" name="procedencia" class="form-control"
                                           placeholder="Nombre de la institución educativa de procedencia">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Observaciones</label>
                                    <textarea name="observaciones" class="form-control" rows="3"
                                              placeholder="Observaciones adicionales sobre la matrícula"></textarea>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <a href="<?= APP_URL ?>/matriculas" class="btn btn-outline-secondary me-2">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i>Registrar Matrícula
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
