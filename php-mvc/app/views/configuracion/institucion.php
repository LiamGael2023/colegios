<?php $inst = $data['institucion']; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-building me-2"></i>Datos de la Institución
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
                        <div class="col-md-8">
                            <label class="form-label required">Nombre de la Institución</label>
                            <input type="text" name="nombre" class="form-control"
                                   value="<?= $inst->nombre ?? '' ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Código Modular</label>
                            <input type="text" name="codigo_modular" class="form-control"
                                   value="<?= $inst->codigo_modular ?? '' ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control"
                                   value="<?= $inst->direccion ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control"
                                   value="<?= $inst->telefono ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?= $inst->email ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Director(a)</label>
                            <input type="text" name="director" class="form-control"
                                   value="<?= $inst->director ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">UGEL</label>
                            <input type="text" name="ugel" class="form-control"
                                   value="<?= $inst->ugel ?? '' ?>">
                        </div>
                    </div>

                    <div class="card-footer bg-transparent mt-3 px-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
