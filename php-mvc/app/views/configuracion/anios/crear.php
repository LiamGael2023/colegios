<div class="page-header d-print-none mb-4">
    <div class="row align-items-center">
        <div class="col-auto">
            <a href="<?= APP_URL ?>/configuracion/anios" class="btn btn-link px-0">
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
                    <i class="ti ti-calendar me-2"></i>Nuevo Año Escolar
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
                        <label class="form-label required">Año</label>
                        <input type="number" name="anio" class="form-control"
                               min="2020" max="2050" value="<?= date('Y') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Fecha de Inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Fecha de Fin</label>
                        <input type="date" name="fecha_fin" class="form-control" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        Se crearán automáticamente los 4 bimestres para este año.
                    </div>

                    <div class="card-footer bg-transparent px-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a href="<?= APP_URL ?>/configuracion/anios" class="btn btn-link">Cancelar</a>
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
    </div>
</div>
