<div class="page-header d-print-none mb-4">
    <div class="row align-items-center">
        <div class="col-auto">
            <a href="<?= APP_URL ?>/docentes" class="btn btn-link px-0">
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
                    <i class="ti ti-plus me-2"></i>Nuevo Docente
                </h3>
            </div>
            <div class="card-body">
                <?php if (!empty($data['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle me-2"></i><?= $data['error'] ?>
                    </div>
                <?php endif; ?>

                <?php if (empty($data['usuarios'])): ?>
                    <div class="alert alert-warning">
                        <i class="ti ti-alert-triangle me-2"></i>
                        No hay usuarios con rol PROFESOR disponibles.
                        <a href="<?= APP_URL ?>/usuarios/crear" class="alert-link">Crear usuario primero</a>
                    </div>
                <?php else: ?>
                    <form method="POST" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label required">Usuario (Profesor)</label>
                            <select name="usuario_id" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                <?php foreach ($data['usuarios'] as $usuario): ?>
                                    <option value="<?= $usuario->id ?>">
                                        <?= $usuario->apellidos ?>, <?= $usuario->nombre ?> - DNI: <?= $usuario->dni ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-hint">Solo aparecen usuarios con rol PROFESOR sin perfil docente</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Especialidad</label>
                            <input type="text" name="especialidad" class="form-control"
                                   placeholder="Ej: Matemática, Comunicación, Ciencias">
                        </div>

                        <div class="card-footer bg-transparent px-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <a href="<?= APP_URL ?>/docentes" class="btn btn-link">Cancelar</a>
                                </div>
                                <div class="col-auto ms-auto">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Guardar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
