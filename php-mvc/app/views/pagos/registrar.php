<?php $pago = $data['pago']; ?>

<div class="mb-4">
    <a href="<?= APP_URL ?>/pagos" class="text-decoration-none">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Registrar Pago</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($data['error'])): ?>
                    <div class="alert alert-danger"><?= $data['error'] ?></div>
                <?php endif; ?>

                <div class="mb-4 p-3 bg-light rounded">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted">Recibo:</td>
                            <td class="fw-medium"><?= $pago->numero_recibo ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Estudiante:</td>
                            <td><?= $pago->apellido_paterno ?> <?= $pago->apellido_materno ?>, <?= $pago->nombres ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Concepto:</td>
                            <td><?= $pago->concepto_nombre ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Monto Total:</td>
                            <td>S/ <?= number_format($pago->monto, 2) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ya Pagado:</td>
                            <td>S/ <?= number_format($pago->monto_pagado, 2) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pendiente:</td>
                            <td class="text-danger fw-bold">S/ <?= number_format($pago->monto - $pago->monto_pagado, 2) ?></td>
                        </tr>
                    </table>
                </div>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Monto a Pagar *</label>
                        <input type="number" name="monto_pagado" class="form-control" step="0.01"
                               min="0.01" max="<?= $pago->monto - $pago->monto_pagado ?>"
                               value="<?= number_format($pago->monto - $pago->monto_pagado, 2, '.', '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Método de Pago *</label>
                        <select name="metodo_pago" class="form-select" required>
                            <option value="EFECTIVO">Efectivo</option>
                            <option value="TRANSFERENCIA">Transferencia</option>
                            <option value="TARJETA">Tarjeta</option>
                            <option value="YAPE">Yape</option>
                            <option value="PLIN">Plin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observación</label>
                        <textarea name="observacion" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg"></i> Registrar Pago
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
