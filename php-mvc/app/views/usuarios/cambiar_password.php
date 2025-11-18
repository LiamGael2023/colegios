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
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-key me-2"></i>Cambiar Contraseña
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <div class="d-flex">
                        <div><i class="ti ti-info-circle me-2"></i></div>
                        <div>
                            Cambiando contraseña para: <strong><?= $usuario->nombre ?> <?= $usuario->apellidos ?></strong>
                            <br>
                            <small class="text-muted"><?= $usuario->email ?></small>
                        </div>
                    </div>
                </div>

                <?php if (!empty($data['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle me-2"></i><?= $data['error'] ?>
                    </div>
                <?php endif; ?>

                <form method="POST" autocomplete="off">
                    <div class="mb-3">
                        <label class="form-label required">Nueva Contraseña</label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                        <small class="form-hint">Mínimo 6 caracteres</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Confirmar Contraseña</label>
                        <input type="password" name="confirmar" class="form-control" minlength="6" required>
                    </div>

                    <div class="card-footer bg-transparent px-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a href="<?= APP_URL ?>/usuarios" class="btn btn-link">Cancelar</a>
                            </div>
                            <div class="col-auto ms-auto">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-key me-1"></i> Cambiar Contraseña
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
