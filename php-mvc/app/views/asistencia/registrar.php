<div class="mb-4">
    <a href="<?= APP_URL ?>/asistencia" class="text-decoration-none">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Control de Asistencia</h5>
            <small class="text-muted">
                <?= $data['seccion']->nivel_nombre ?> - <?= $data['seccion']->grado_nombre ?> "<?= $data['seccion']->nombre ?>"
                | <?= date('d/m/Y', strtotime($data['fecha'])) ?>
            </small>
        </div>
        <div>
            <button type="button" class="btn btn-sm btn-success" onclick="marcarTodos('PRESENTE')">
                Todos Presentes
            </button>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($data['error'])): ?>
            <div class="alert alert-danger"><?= $data['error'] ?></div>
        <?php endif; ?>
        <?php if (!empty($data['success'])): ?>
            <div class="alert alert-success"><?= $data['success'] ?></div>
        <?php endif; ?>

        <?php
        $contadores = ['PRESENTE' => 0, 'AUSENTE' => 0, 'TARDANZA' => 0, 'JUSTIFICADO' => 0];
        foreach ($data['estudiantes'] as $est) {
            if ($est->estado) $contadores[$est->estado]++;
        }
        ?>
        <div class="mb-3">
            <span class="badge bg-success">Presentes: <?= $contadores['PRESENTE'] ?></span>
            <span class="badge bg-danger">Ausentes: <?= $contadores['AUSENTE'] ?></span>
            <span class="badge bg-warning">Tardanzas: <?= $contadores['TARDANZA'] ?></span>
            <span class="badge bg-info">Justificados: <?= $contadores['JUSTIFICADO'] ?></span>
        </div>

        <form method="POST">
            <table class="table">
                <thead>
                    <tr>
                        <th width="50">N°</th>
                        <th>Código</th>
                        <th>Estudiante</th>
                        <th class="text-center">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($data['estudiantes'] as $est): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $est->codigo ?></td>
                            <td><?= $est->apellido_paterno ?> <?= $est->apellido_materno ?>, <?= $est->nombres ?></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <?php
                                    $estados = ['PRESENTE' => 'P', 'AUSENTE' => 'A', 'TARDANZA' => 'T', 'JUSTIFICADO' => 'J'];
                                    $colores = ['PRESENTE' => 'success', 'AUSENTE' => 'danger', 'TARDANZA' => 'warning', 'JUSTIFICADO' => 'info'];
                                    foreach ($estados as $estado => $letra):
                                        $checked = ($est->estado == $estado) || (!$est->estado && $estado == 'PRESENTE');
                                    ?>
                                        <input type="radio" class="btn-check" name="asistencia[<?= $est->estudiante_id ?>]"
                                               id="asist_<?= $est->estudiante_id ?>_<?= $estado ?>"
                                               value="<?= $estado ?>" <?= $checked ? 'checked' : '' ?>>
                                        <label class="btn btn-outline-<?= $colores[$estado] ?>"
                                               for="asist_<?= $est->estudiante_id ?>_<?= $estado ?>"><?= $letra ?></label>
                                    <?php endforeach; ?>
                                </div>
                                <input type="hidden" name="observacion[<?= $est->estudiante_id ?>]" value="">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Guardar Asistencia
            </button>
        </form>
    </div>
</div>

<script>
function marcarTodos(estado) {
    document.querySelectorAll(`input[value="${estado}"]`).forEach(radio => {
        radio.checked = true;
    });
}
</script>
