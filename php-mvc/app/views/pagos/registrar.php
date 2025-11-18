<?php $pago = $data['pago']; ?>

<div class="page-header d-print-none mb-4">
    <div class="row align-items-center">
        <div class="col-auto">
            <a href="<?= APP_URL ?>/pagos" class="btn btn-link px-0">
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
                    <i class="ti ti-cash me-2"></i>Registrar Pago
                </h3>
            </div>
            <div class="card-body">
                <?php if (!empty($data['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle me-2"></i><?= $data['error'] ?>
                    </div>
                <?php endif; ?>

                <div class="datagrid mb-4">
                    <div class="datagrid-item">
                        <div class="datagrid-title">Recibo</div>
                        <div class="datagrid-content"><?= $pago->numero_recibo ?></div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Estudiante</div>
                        <div class="datagrid-content"><?= $pago->apellido_paterno ?> <?= $pago->apellido_materno ?>, <?= $pago->nombres ?></div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Concepto</div>
                        <div class="datagrid-content"><?= $pago->concepto_nombre ?></div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Monto Total</div>
                        <div class="datagrid-content">S/ <?= number_format($pago->monto, 2) ?></div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Ya Pagado</div>
                        <div class="datagrid-content">S/ <?= number_format($pago->monto_pagado, 2) ?></div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Pendiente</div>
                        <div class="datagrid-content text-red fw-bold">S/ <?= number_format($pago->monto - $pago->monto_pagado, 2) ?></div>
                    </div>
                </div>

                <form method="POST" autocomplete="off">
                    <div class="mb-3">
                        <label class="form-label required">Monto a Pagar</label>
                        <div class="input-group">
                            <span class="input-group-text">S/</span>
                            <input type="number" name="monto_pagado" class="form-control" step="0.01"
                                   min="0.01" max="<?= $pago->monto - $pago->monto_pagado ?>"
                                   value="<?= number_format($pago->monto - $pago->monto_pagado, 2, '.', '') ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Método de Pago</label>
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
                    <div class="card-footer bg-transparent px-0">
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-check me-1"></i> Registrar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
