<?php $est = $data['estudiante']; ?>

<div class="mb-4">
    <a href="<?= APP_URL ?>/estudiantes/ver/<?= $est->id ?>" class="text-decoration-none">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Matricular Estudiante</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Estudiante:</strong> <?= $est->apellido_paterno ?> <?= $est->apellido_materno ?>, <?= $est->nombres ?>
                    <br>
                    <strong>Año Escolar:</strong> <?= $data['anioActivo']->anio ?>
                </div>

                <?php if (!empty($data['error'])): ?>
                    <div class="alert alert-danger"><?= $data['error'] ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Sección *</label>
                            <select name="seccion_id" class="form-select" required>
                                <option value="">Seleccionar sección...</option>
                                <?php foreach ($data['secciones'] as $sec): ?>
                                    <option value="<?= $sec->id ?>">
                                        <?= $sec->nivel_nombre ?> - <?= $sec->grado_nombre ?> "<?= $sec->nombre ?>"
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo de Matrícula</label>
                            <select name="tipo_matricula" class="form-select">
                                <option value="REGULAR">Regular</option>
                                <option value="TRASLADO">Traslado</option>
                                <option value="REINGRESO">Reingreso</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Confirmar Matrícula
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
