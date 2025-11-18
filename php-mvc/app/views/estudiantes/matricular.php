<?php $est = $data['estudiante']; ?>

<div class="page-header d-print-none mb-4">
    <div class="row align-items-center">
        <div class="col-auto">
            <a href="<?= APP_URL ?>/estudiantes/ver/<?= $est->id ?>" class="btn btn-link px-0">
                <i class="ti ti-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-certificate me-2"></i>Matricular Estudiante
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <div class="d-flex">
                        <div><i class="ti ti-info-circle me-2"></i></div>
                        <div>
                            <h4 class="alert-title">Información de Matrícula</h4>
                            <div class="text-muted">
                                <strong>Estudiante:</strong> <?= $est->apellido_paterno ?> <?= $est->apellido_materno ?>, <?= $est->nombres ?>
                                <br>
                                <strong>Año Escolar:</strong> <?= $data['anioActivo']->anio ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($data['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle me-2"></i><?= $data['error'] ?>
                    </div>
                <?php endif; ?>

                <form method="POST" autocomplete="off">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label required">Sección</label>
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

                    <div class="card-footer bg-transparent mt-3 px-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a href="<?= APP_URL ?>/estudiantes/ver/<?= $est->id ?>" class="btn btn-link">
                                    Cancelar
                                </a>
                            </div>
                            <div class="col-auto ms-auto">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-check me-1"></i> Confirmar Matrícula
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
