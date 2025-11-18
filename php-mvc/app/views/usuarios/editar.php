<?php $usuario = $data['usuario']; ?>

<div class="page-header d-print-none mb-4">
    <div class="row align-items-center">
        <div class="col-auto">
            <a href="<?= APP_URL ?>/usuarios" class="btn btn-link px-0">
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
                    <i class="ti ti-edit me-2"></i>Editar Usuario
                </h3>
            </div>
            <div class="card-body">
                <?php if (!empty($data['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle me-2"></i><?= $data['error'] ?>
                    </div>
                <?php endif; ?>

                <form method="POST" autocomplete="off">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="<?= $usuario->nombre ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control" value="<?= $usuario->apellidos ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $usuario->email ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">DNI</label>
                            <input type="text" name="dni" class="form-control" maxlength="8" value="<?= $usuario->dni ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="<?= $usuario->telefono ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Rol</label>
                            <select name="rol" class="form-select" required>
                                <option value="ADMIN" <?= $usuario->rol == 'ADMIN' ? 'selected' : '' ?>>Administrador</option>
                                <option value="DIRECTOR" <?= $usuario->rol == 'DIRECTOR' ? 'selected' : '' ?>>Director</option>
                                <option value="SECRETARIA" <?= $usuario->rol == 'SECRETARIA' ? 'selected' : '' ?>>Secretaria</option>
                                <option value="PROFESOR" <?= $usuario->rol == 'PROFESOR' ? 'selected' : '' ?>>Profesor</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estado</label>
                            <label class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="activo" value="1"
                                       <?= $usuario->activo ? 'checked' : '' ?>>
                                <span class="form-check-label">Usuario activo</span>
                            </label>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent mt-3 px-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a href="<?= APP_URL ?>/usuarios" class="btn btn-link">Cancelar</a>
                            </div>
                            <div class="col-auto ms-auto">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
