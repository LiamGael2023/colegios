<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-user-plus me-2 text-primary"></i>Nuevo Estudiante
                </h3>
            </div>
            <div class="card-body">
                <?php if (!empty($data['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <div class="d-flex">
                            <div><i class="ti ti-alert-circle me-2"></i></div>
                            <div><?= $data['error'] ?></div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" autocomplete="off">
                    <!-- Sección: Foto -->
                    <div class="mb-4">
                        <h4 class="card-title text-muted mb-3">
                            <i class="ti ti-camera me-1"></i>Foto de Perfil
                        </h4>
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label">Imagen del Estudiante</label>
                                <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg">
                                <small class="form-hint">Formatos: JPG, PNG. Máximo: 2MB</small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Sección: Datos Personales -->
                    <div class="mb-4">
                        <h4 class="card-title text-muted mb-3">
                            <i class="ti ti-user me-1"></i>Datos Personales
                        </h4>
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label required">Nombres</label>
                                <input type="text" name="nombres" class="form-control" required
                                       placeholder="Nombres completos">
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label required">Apellido Paterno</label>
                                <input type="text" name="apellido_paterno" class="form-control" required>
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label required">Apellido Materno</label>
                                <input type="text" name="apellido_materno" class="form-control" required>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">DNI</label>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <i class="ti ti-id"></i>
                                    </span>
                                    <input type="text" name="dni" class="form-control" maxlength="8"
                                           placeholder="12345678" pattern="[0-9]{8}">
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label required">Fecha de Nacimiento</label>
                                <input type="date" name="fecha_nacimiento" class="form-control" required>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label required">Género</label>
                                <select name="genero" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    <option value="MASCULINO">Masculino</option>
                                    <option value="FEMENINO">Femenino</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Sección: Contacto -->
                    <div class="mb-4">
                        <h4 class="card-title text-muted mb-3">
                            <i class="ti ti-address-book me-1"></i>Información de Contacto
                        </h4>
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label">Teléfono</label>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <i class="ti ti-phone"></i>
                                    </span>
                                    <input type="tel" name="telefono" class="form-control"
                                           placeholder="999 999 999">
                                </div>
                            </div>
                            <div class="col-12 col-md-8">
                                <label class="form-label">Email</label>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <i class="ti ti-mail"></i>
                                    </span>
                                    <input type="email" name="email" class="form-control"
                                           placeholder="correo@ejemplo.com">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Dirección</label>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <i class="ti ti-map-pin"></i>
                                    </span>
                                    <input type="text" name="direccion" class="form-control"
                                           placeholder="Av. Principal 123, Distrito">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent">
                    <div class="d-flex flex-column flex-md-row gap-2">
                        <a href="<?= APP_URL ?>/estudiantes" class="btn btn-outline-secondary order-2 order-md-1">
                            <i class="ti ti-arrow-left me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary ms-md-auto order-1 order-md-2">
                            <i class="ti ti-device-floppy me-1"></i>Guardar Estudiante
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
