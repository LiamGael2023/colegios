<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="ti ti-user-plus me-2"></i>Datos del Estudiante
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
                    <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg">
                    <small class="form-hint">Formatos permitidos: JPG, PNG. Tamaño máximo: 2MB</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Nombres</label>
                    <input type="text" name="nombres" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Apellido Paterno</label>
                    <input type="text" name="apellido_paterno" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Apellido Materno</label>
                    <input type="text" name="apellido_materno" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">DNI</label>
                    <input type="text" name="dni" class="form-control" maxlength="8" placeholder="12345678">
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Género</label>
                    <select name="genero" class="form-select" required>
                        <option value="MASCULINO">Masculino</option>
                        <option value="FEMENINO">Femenino</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" placeholder="999 999 999">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com">
                </div>
            </div>

            <div class="card-footer bg-transparent mt-3 px-0">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <a href="<?= APP_URL ?>/estudiantes" class="btn btn-link">
                            Cancelar
                        </a>
                    </div>
                    <div class="col-auto ms-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
