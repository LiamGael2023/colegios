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

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="ti ti-edit me-2"></i>Editar Estudiante
        </h3>
    </div>
    <div class="card-body">
        <?php if (!empty($data['error'])): ?>
            <div class="alert alert-danger">
                <i class="ti ti-alert-circle me-2"></i><?= $data['error'] ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="row g-3">
                <!-- Foto de perfil -->
                <div class="col-md-12">
                    <label class="form-label">Foto del Estudiante</label>
                    <div class="row align-items-center">
                        <?php if (!empty($est->foto)): ?>
                            <div class="col-auto">
                                <span class="avatar avatar-lg" style="background-image: url(<?= APP_URL ?>/uploads/fotos/<?= $est->foto ?>)"></span>
                            </div>
                            <div class="col">
                                <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg">
                                <small class="form-hint">Deje vacío para mantener la foto actual</small>
                            </div>
                        <?php else: ?>
                            <div class="col">
                                <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg">
                                <small class="form-hint">Formatos permitidos: JPG, PNG. Tamaño máximo: 2MB</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Nombres</label>
                    <input type="text" name="nombres" class="form-control" value="<?= $est->nombres ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Apellido Paterno</label>
                    <input type="text" name="apellido_paterno" class="form-control" value="<?= $est->apellido_paterno ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Apellido Materno</label>
                    <input type="text" name="apellido_materno" class="form-control" value="<?= $est->apellido_materno ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">DNI</label>
                    <input type="text" name="dni" class="form-control" maxlength="8" value="<?= $est->dni ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control" value="<?= $est->fecha_nacimiento ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Género</label>
                    <select name="genero" class="form-select" required>
                        <option value="MASCULINO" <?= $est->genero == 'MASCULINO' ? 'selected' : '' ?>>Masculino</option>
                        <option value="FEMENINO" <?= $est->genero == 'FEMENINO' ? 'selected' : '' ?>>Femenino</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="<?= $est->telefono ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control" value="<?= $est->direccion ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= $est->email ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2"><?= $est->observaciones ?></textarea>
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
                            <i class="ti ti-device-floppy me-1"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
