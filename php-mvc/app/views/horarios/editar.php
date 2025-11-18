<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="page-pretitle">Horarios</div>
        <h2 class="page-title">Editar Horario</h2>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <div class="d-flex">
                    <div><i class="ti ti-alert-circle icon alert-icon"></i></div>
                    <div><?= $_SESSION['error'] ?></div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Modificar Horario</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?= APP_URL ?>/horarios/editar/<?= $horario->id ?>" method="POST">
                            <div class="mb-3">
                                <label class="form-label required">Día</label>
                                <select name="dia" class="form-select" required>
                                    <?php foreach ($dias as $dia): ?>
                                        <option value="<?= $dia ?>" <?= $horario->dia == $dia ? 'selected' : '' ?>>
                                            <?= ucfirst(strtolower($dia)) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Hora Inicio</label>
                                        <input type="time" name="hora_inicio" class="form-control"
                                               value="<?= substr($horario->hora_inicio, 0, 5) ?>" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Hora Fin</label>
                                        <input type="time" name="hora_fin" class="form-control"
                                               value="<?= substr($horario->hora_fin, 0, 5) ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Aula</label>
                                <input type="text" name="aula" class="form-control"
                                       value="<?= htmlspecialchars($horario->aula ?? '') ?>" placeholder="Ej: Aula 101">
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?= APP_URL ?>/horarios/seccion/<?= $horario->seccion_id ?>" class="btn btn-outline-secondary">
                                    <i class="ti ti-arrow-left me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i>Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
