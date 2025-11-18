<div class="page-header d-print-none mb-4">
    <div class="row align-items-center">
        <div class="col-auto">
            <a href="<?= APP_URL ?>/notas" class="btn btn-link px-0">
                <i class="ti ti-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title"><?= $data['curso']->nombre ?></h3>
            <p class="card-subtitle">
                <?= $data['seccion']->nivel_nombre ?> - <?= $data['seccion']->grado_nombre ?> "<?= $data['seccion']->nombre ?>"
                | <?= $data['periodo']->nombre ?>
            </p>
        </div>
        <div class="card-actions">
            <span class="badge bg-azure-lt">
                <?= $data['tipoCalificacion'] == 'LITERAL' ? 'AD, A, B, C' : '0 - 20' ?>
            </span>
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

        <form method="POST">
            <div class="table-responsive">
                <table class="table table-vcenter">
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
            </div>

            <div class="card-footer bg-transparent px-0">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-1"></i> Guardar Notas
                </button>
            </div>
        </form>
    </div>
</div>
