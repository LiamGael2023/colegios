<div class="page-header d-print-none mb-4">
    <div class="row align-items-center">
        <div class="col-auto">
            <a href="<?= APP_URL ?>/apoderados" class="btn btn-link px-0">
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
                    <i class="ti ti-edit me-2"></i>Editar Apoderado
                </h3>
            </div>
            <div class="card-body">
                <?php if (!empty($data['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle me-2"></i><?= $data['error'] ?>
                    </div>
                <?php endif; ?>

                <form method="POST" autocomplete="off">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Estudiante</label>
                            <select name="estudiante_id" class="form-select" required>
                                <?php foreach ($data['estudiantes'] as $est): ?>
                                    <option value="<?= $est->id ?>" <?= $est->id == $data['apoderado']->estudiante_id ? 'selected' : '' ?>>
                                        <?= $est->apellido_paterno ?> <?= $est->apellido_materno ?>, <?= $est->nombres ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Parentesco</label>
                            <select name="parentesco" class="form-select" required>
                                <?php
                                $parentescos = ['PADRE', 'MADRE', 'TUTOR', 'ABUELO', 'ABUELA', 'TIO', 'TIA', 'HERMANO', 'HERMANA', 'OTRO'];
                                foreach ($parentescos as $p):
                                ?>
                                    <option value="<?= $p ?>" <?= $data['apoderado']->parentesco == $p ? 'selected' : '' ?>><?= $p ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">DNI</label>
                            <input type="text" name="dni" class="form-control" maxlength="8"
                                   value="<?= $data['apoderado']->dni ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Nombres</label>
                            <input type="text" name="nombres" class="form-control"
                                   value="<?= $data['apoderado']->nombres ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control"
                                   value="<?= $data['apoderado']->apellidos ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Teléfono</label>
                            <input type="text" name="telefono" class="form-control"
                                   value="<?= $data['apoderado']->telefono ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Teléfono Trabajo</label>
                            <input type="text" name="telefono_trabajo" class="form-control"
                                   value="<?= $data['apoderado']->telefono_trabajo ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?= $data['apoderado']->email ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ocupación</label>
                            <input type="text" name="ocupacion" class="form-control"
                                   value="<?= $data['apoderado']->ocupacion ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lugar de Trabajo</label>
                            <input type="text" name="lugar_trabajo" class="form-control"
                                   value="<?= $data['apoderado']->lugar_trabajo ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control"
                               value="<?= $data['apoderado']->direccion ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-check">
                            <input type="checkbox" name="es_principal" value="1" class="form-check-input"
                                   <?= $data['apoderado']->es_principal ? 'checked' : '' ?>>
                            <span class="form-check-label">Apoderado Principal</span>
                        </label>
                    </div>

                    <div class="card-footer bg-transparent px-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a href="<?= APP_URL ?>/apoderados" class="btn btn-link">Cancelar</a>
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
