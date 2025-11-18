<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Datos del Estudiante</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($data['error'])): ?>
            <div class="alert alert-danger"><?= $data['error'] ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nombres *</label>
                    <input type="text" name="nombres" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Apellido Paterno *</label>
                    <input type="text" name="apellido_paterno" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Apellido Materno *</label>
                    <input type="text" name="apellido_materno" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">DNI</label>
                    <input type="text" name="dni" class="form-control" maxlength="8">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha de Nacimiento *</label>
                    <input type="date" name="fecha_nacimiento" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Género *</label>
                    <select name="genero" class="form-select" required>
                        <option value="MASCULINO">Masculino</option>
                        <option value="FEMENINO">Femenino</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar
                </button>
                <a href="<?= APP_URL ?>/estudiantes" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
