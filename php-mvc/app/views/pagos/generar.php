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
                <h5 class="mb-0">Generar Cuotas de Pago</h5>
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
                    <div class="mb-3">
                        <label class="form-label">Concepto de Pago *</label>
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
                        <label class="form-label">Meses a Generar *</label>
                        <div class="row g-2">
                            <?php
                            $meses = ['Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                            foreach ($meses as $i => $mes):
                                $num = $i + 3;
                            ?>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="meses[]"
                                               value="<?= $num ?>" id="mes_<?= $num ?>">
                                        <label class="form-check-label" for="mes_<?= $num ?>">
                                            <?= $mes ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="seleccionarTodos()">
                                Seleccionar todos
                            </button>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Generar Cuotas
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
