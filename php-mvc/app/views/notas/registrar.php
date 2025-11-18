<div class="mb-4">
    <a href="<?= APP_URL ?>/notas" class="text-decoration-none">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0"><?= $data['curso']->nombre ?></h5>
            <small class="text-muted">
                <?= $data['seccion']->nivel_nombre ?> - <?= $data['seccion']->grado_nombre ?> "<?= $data['seccion']->nombre ?>"
                | <?= $data['periodo']->nombre ?>
            </small>
        </div>
        <span class="badge bg-info">
            <?= $data['tipoCalificacion'] == 'LITERAL' ? 'AD, A, B, C' : '0 - 20' ?>
        </span>
    </div>
    <div class="card-body">
        <?php if (!empty($data['error'])): ?>
            <div class="alert alert-danger"><?= $data['error'] ?></div>
        <?php endif; ?>
        <?php if (!empty($data['success'])): ?>
            <div class="alert alert-success"><?= $data['success'] ?></div>
        <?php endif; ?>

        <form method="POST">
            <table class="table">
                <thead>
                    <tr>
                        <th width="50">N°</th>
                        <th>Código</th>
                        <th>Estudiante</th>
                        <th width="150" class="text-center">Nota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($data['estudiantes'] as $est): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $est->codigo ?></td>
                            <td><?= $est->apellido_paterno ?> <?= $est->apellido_materno ?>, <?= $est->nombres ?></td>
                            <td class="text-center">
                                <?php if ($data['tipoCalificacion'] == 'LITERAL'): ?>
                                    <select name="notas[<?= $est->estudiante_id ?>]" class="form-select form-select-sm">
                                        <option value="">-</option>
                                        <option value="AD" <?= $est->calificacion == 'AD' ? 'selected' : '' ?>>AD</option>
                                        <option value="A" <?= $est->calificacion == 'A' ? 'selected' : '' ?>>A</option>
                                        <option value="B" <?= $est->calificacion == 'B' ? 'selected' : '' ?>>B</option>
                                        <option value="C" <?= $est->calificacion == 'C' ? 'selected' : '' ?>>C</option>
                                    </select>
                                <?php else: ?>
                                    <input type="number" name="notas[<?= $est->estudiante_id ?>]"
                                           class="form-control form-control-sm text-center"
                                           min="0" max="20" value="<?= $est->calificacion ?>">
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Guardar Notas
            </button>
        </form>
    </div>
</div>
