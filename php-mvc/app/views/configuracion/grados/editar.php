<div class="page-header d-print-none mb-4">
    <div class="row align-items-center">
        <div class="col-auto">
            <a href="<?= APP_URL ?>/configuracion/grados" class="btn btn-link px-0">
                <i class="ti ti-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-edit me-2"></i>Editar Grado
                </h3>
            </div>
            <div class="card-body">
                <?php if (!empty($data['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle me-2"></i><?= $data['error'] ?>
                    </div>
                <?php endif; ?>

                <form method="POST" autocomplete="off">
                    <div class="mb-3">
                        <label class="form-label required">Nivel</label>
                        <select name="nivel_id" class="form-select" required>
                            <?php foreach ($data['niveles'] as $nivel): ?>
                                <option value="<?= $nivel->id ?>" <?= $nivel->id == $data['grado']->nivel_id ? 'selected' : '' ?>>
                                    <?= $nivel->nombre ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Nombre del Grado</label>
                        <input type="text" name="nombre" class="form-control"
                               value="<?= $data['grado']->nombre ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Número de Orden</label>
                        <input type="number" name="numero" class="form-control" min="1"
                               value="<?= $data['grado']->numero ?>" required>
                    </div>

                    <div class="card-footer bg-transparent px-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a href="<?= APP_URL ?>/configuracion/grados" class="btn btn-link">Cancelar</a>
                            </div>
                            <div class="col-auto ms-auto">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i> Actualizar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
