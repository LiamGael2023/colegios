<?php $est = $data['estudiante']; ?>

<div class="mb-4">
    <a href="<?= APP_URL ?>/estudiantes/ver/<?= $est->id ?>" class="text-decoration-none">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Editar Estudiante</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($data['error'])): ?>
            <div class="alert alert-danger"><?= $data['error'] ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nombres *</label>
                    <input type="text" name="nombres" class="form-control" value="<?= $est->nombres ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Apellido Paterno *</label>
                    <input type="text" name="apellido_paterno" class="form-control" value="<?= $est->apellido_paterno ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Apellido Materno *</label>
                    <input type="text" name="apellido_materno" class="form-control" value="<?= $est->apellido_materno ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">DNI</label>
                    <input type="text" name="dni" class="form-control" maxlength="8" value="<?= $est->dni ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha de Nacimiento *</label>
                    <input type="date" name="fecha_nacimiento" class="form-control" value="<?= $est->fecha_nacimiento ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Género *</label>
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

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar Cambios
                </button>
                <a href="<?= APP_URL ?>/estudiantes/ver/<?= $est->id ?>" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
