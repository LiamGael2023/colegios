<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="page-pretitle">Horario</div>
                <h2 class="page-title">
                    <?= htmlspecialchars($seccion->grado_nombre . ' "' . $seccion->nombre . '"') ?>
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="<?= APP_URL ?>/horarios" class="btn btn-outline-secondary">
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
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible no-print">
                <div class="d-flex">
                    <div><i class="ti ti-check icon alert-icon"></i></div>
                    <div><?= $_SESSION['success'] ?></div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible no-print">
                <div class="d-flex">
                    <div><i class="ti ti-alert-circle icon alert-icon"></i></div>
                    <div><?= $_SESSION['error'] ?></div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="row">
            <?php if (in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR'])): ?>
            <!-- Formulario para agregar horario -->
            <div class="col-lg-4 no-print">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-plus me-1"></i>Agregar Horario
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($asignaciones)): ?>
                            <div class="text-muted text-center py-3">
                                No hay asignaciones disponibles.<br>
                                Primero asigne docentes a los cursos.
                            </div>
                        <?php else: ?>
                            <form action="<?= APP_URL ?>/horarios/agregar" method="POST">
                                <input type="hidden" name="seccion_id" value="<?= $seccion->id ?>">

                                <div class="mb-3">
                                    <label class="form-label required">Curso/Docente</label>
                                    <select name="asignacion_id" class="form-select" required>
                                        <option value="">Seleccione...</option>
                                        <?php foreach ($asignaciones as $asig): ?>
                                            <option value="<?= $asig->id ?>">
                                                <?= htmlspecialchars($asig->curso_nombre . ' - ' . $asig->docente_nombre) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Día</label>
                                    <select name="dia" class="form-select" required>
                                        <option value="">Seleccione...</option>
                                        <?php foreach ($dias as $dia): ?>
                                            <option value="<?= $dia ?>"><?= ucfirst(strtolower($dia)) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Hora Inicio</label>
                                            <input type="time" name="hora_inicio" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Hora Fin</label>
                                            <input type="time" name="hora_fin" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Aula</label>
                                    <input type="text" name="aula" class="form-control" placeholder="Ej: Aula 101">
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti ti-plus me-1"></i>Agregar
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Grilla de horarios -->
            <div class="<?= in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR']) ? 'col-lg-8' : 'col-12' ?>">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Horario Semanal</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered card-table">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">Hora</th>
                                    <?php foreach ($dias as $dia): ?>
                                        <th class="text-center"><?= ucfirst(strtolower($dia)) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Obtener todas las horas únicas
                                $horas = [];
                                foreach ($horarios as $h) {
                                    $horas[$h->hora_inicio . '-' . $h->hora_fin] = [
                                        'inicio' => $h->hora_inicio,
                                        'fin' => $h->hora_fin
                                    ];
                                }
                                ksort($horas);

                                if (empty($horas)):
                                ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            No hay horarios registrados para esta sección
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($horas as $key => $hora): ?>
                                        <tr>
                                            <td class="text-muted">
                                                <?= substr($hora['inicio'], 0, 5) ?><br>
                                                <?= substr($hora['fin'], 0, 5) ?>
                                            </td>
                                            <?php foreach ($dias as $dia): ?>
                                                <td class="p-1">
                                                    <?php
                                                    $encontrado = false;
                                                    foreach ($horariosPorDia[$dia] as $h) {
                                                        if ($h->hora_inicio == $hora['inicio'] && $h->hora_fin == $hora['fin']) {
                                                            $encontrado = true;
                                                    ?>
                                                        <div class="bg-primary-lt rounded p-2 text-center">
                                                            <div class="fw-bold small"><?= htmlspecialchars($h->curso_nombre) ?></div>
                                                            <div class="text-muted small"><?= htmlspecialchars($h->docente_nombre) ?></div>
                                                            <?php if ($h->aula): ?>
                                                                <div class="text-muted small"><?= htmlspecialchars($h->aula) ?></div>
                                                            <?php endif; ?>
                                                            <?php if (in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR'])): ?>
                                                                <div class="mt-1 no-print">
                                                                    <a href="<?= APP_URL ?>/horarios/editar/<?= $h->id ?>" class="btn btn-sm btn-ghost-primary">
                                                                        <i class="ti ti-edit"></i>
                                                                    </a>
                                                                    <a href="<?= APP_URL ?>/horarios/eliminar/<?= $h->id ?>"
                                                                       class="btn btn-sm btn-ghost-danger"
                                                                       onclick="return confirm('¿Eliminar este horario?')">
                                                                        <i class="ti ti-trash"></i>
                                                                    </a>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php
                                                        }
                                                    }
                                                    if (!$encontrado) echo '<div class="text-muted text-center">-</div>';
                                                    ?>
                                                </td>
                                            <?php endforeach; ?>
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
