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
                    <i class="ti ti-receipt me-2"></i>Generar Cuotas de Pago
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <div class="d-flex">
                        <div><i class="ti ti-info-circle me-2"></i></div>
                        <div>
                            <h4 class="alert-title">Información del Estudiante</h4>
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
                    <div class="mb-3">
                        <label class="form-label required">Concepto de Pago</label>
                        <select name="concepto_id" class="form-select" required>
                            <option value="">Seleccionar...</option>
                            <?php foreach ($data['conceptos'] as $concepto): ?>
                                <option value="<?= $concepto->id ?>">
                                    <?= $concepto->nombre ?> - S/ <?= number_format($concepto->monto, 2) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Meses a Generar</label>
                        <div class="row g-2">
                            <?php
                            $meses = ['Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                            foreach ($meses as $i => $mes):
                                $num = $i + 3;
                            ?>
                                <div class="col-md-3">
                                    <label class="form-check">
                                        <input class="form-check-input" type="checkbox" name="meses[]"
                                               value="<?= $num ?>">
                                        <span class="form-check-label"><?= $mes ?></span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm" onclick="seleccionarTodos()">
                                <i class="ti ti-checks me-1"></i> Seleccionar todos
                            </button>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent px-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Generar Cuotas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function seleccionarTodos() {
    document.querySelectorAll('input[name="meses[]"]').forEach(cb => {
        cb.checked = true;
    });
}
</script>
