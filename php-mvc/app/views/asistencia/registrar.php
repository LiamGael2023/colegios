<div class="page-header d-print-none mb-4">
    <div class="row align-items-center">
        <div class="col-auto">
            <a href="<?= APP_URL ?>/asistencia" class="btn btn-link px-0">
                <i class="ti ti-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Control de Asistencia</h3>
            <p class="card-subtitle">
                <?= $data['seccion']->nivel_nombre ?> - <?= $data['seccion']->grado_nombre ?> "<?= $data['seccion']->nombre ?>"
                | <?= date('d/m/Y', strtotime($data['fecha'])) ?>
            </p>
        </div>
        <div class="card-actions">
            <button type="button" class="btn btn-success" onclick="marcarTodos('PRESENTE')">
                <i class="ti ti-checks me-1"></i> Todos Presentes
            </button>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($data['error'])): ?>
            <div class="alert alert-danger">
                <i class="ti ti-alert-circle me-2"></i><?= $data['error'] ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($data['success'])): ?>
            <div class="alert alert-success">
                <i class="ti ti-check me-2"></i><?= $data['success'] ?>
            </div>
        <?php endif; ?>

        <?php
        $contadores = ['PRESENTE' => 0, 'AUSENTE' => 0, 'TARDANZA' => 0, 'JUSTIFICADO' => 0];
        foreach ($data['estudiantes'] as $est) {
            if ($est->estado) $contadores[$est->estado]++;
        }
        ?>
        <div class="mb-3">
            <span class="badge bg-green-lt me-1">
                <i class="ti ti-check me-1"></i>Presentes: <?= $contadores['PRESENTE'] ?>
            </span>
            <span class="badge bg-red-lt me-1">
                <i class="ti ti-x me-1"></i>Ausentes: <?= $contadores['AUSENTE'] ?>
            </span>
            <span class="badge bg-yellow-lt me-1">
                <i class="ti ti-clock me-1"></i>Tardanzas: <?= $contadores['TARDANZA'] ?>
            </span>
            <span class="badge bg-azure-lt">
                <i class="ti ti-file-check me-1"></i>Justificados: <?= $contadores['JUSTIFICADO'] ?>
            </span>
        </div>

        <form method="POST">
            <div class="table-responsive">
                <table class="table table-vcenter">
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
                                <td class="text-muted"><?= $i++ ?></td>
                                <td class="text-muted"><?= $est->codigo ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-sm bg-primary-lt me-2">
                                            <?= strtoupper(substr($est->nombres, 0, 1)) ?>
                                        </span>
                                        <?= $est->apellido_paterno ?> <?= $est->apellido_materno ?>, <?= $est->nombres ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
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
            </div>

            <div class="card-footer bg-transparent px-0">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-1"></i> Guardar Asistencia
                </button>
            </div>
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
