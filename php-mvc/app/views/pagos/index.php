<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body text-center">
                <p class="text-muted mb-0 small">Total a Cobrar</p>
                <h4 class="mb-0">S/ <?= number_format($data['totalMonto'], 2) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success bg-opacity-10">
            <div class="card-body text-center">
                <p class="text-muted mb-0 small">Total Recaudado</p>
                <h4 class="mb-0 text-success">S/ <?= number_format($data['totalPagado'], 2) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger bg-opacity-10">
            <div class="card-body text-center">
                <p class="text-muted mb-0 small">Pendiente</p>
                <h4 class="mb-0 text-danger">S/ <?= number_format($data['totalMonto'] - $data['totalPagado'], 2) ?></h4>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="PENDIENTE" <?= $data['estadoFiltro'] == 'PENDIENTE' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="PAGADO" <?= $data['estadoFiltro'] == 'PAGADO' ? 'selected' : '' ?>>Pagado</option>
                    <option value="PARCIAL" <?= $data['estadoFiltro'] == 'PARCIAL' ? 'selected' : '' ?>>Parcial</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="mes" class="form-select">
                    <option value="">Todos los meses</option>
                    <?php
                    $meses = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
                    for ($m = 1; $m <= 12; $m++):
                    ?>
                        <option value="<?= $m ?>" <?= $data['mesFiltro'] == $m ? 'selected' : '' ?>><?= $meses[$m] ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Recibo</th>
                    <th>Estudiante</th>
                    <th>Concepto</th>
                    <th class="text-center">Mes</th>
                    <th class="text-end">Monto</th>
                    <th class="text-end">Pagado</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['pagos'])): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">No hay pagos</td></tr>
                <?php else: ?>
                    <?php
                    $meses = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
                    foreach ($data['pagos'] as $pago):
                    ?>
                        <tr>
                            <td class="small"><?= $pago->numero_recibo ?></td>
                            <td>
                                <div><?= $pago->apellido_paterno ?> <?= $pago->apellido_materno ?></div>
                                <small class="text-muted"><?= $pago->nombres ?></small>
                            </td>
                            <td><?= $pago->concepto_nombre ?></td>
                            <td class="text-center"><?= $pago->mes ? $meses[$pago->mes] : '-' ?></td>
                            <td class="text-end">S/ <?= number_format($pago->monto, 2) ?></td>
                            <td class="text-end">S/ <?= number_format($pago->monto_pagado, 2) ?></td>
                            <td class="text-center">
                                <?php
                                $badges = [
                                    'PENDIENTE' => 'warning',
                                    'PAGADO' => 'success',
                                    'PARCIAL' => 'info',
                                    'VENCIDO' => 'danger',
                                    'ANULADO' => 'secondary'
                                ];
                                ?>
                                <span class="badge bg-<?= $badges[$pago->estado] ?>"><?= $pago->estado ?></span>
                            </td>
                            <td class="text-center">
                                <?php if ($pago->estado != 'PAGADO' && $pago->estado != 'ANULADO'): ?>
                                    <a href="<?= APP_URL ?>/pagos/registrar/<?= $pago->id ?>"
                                       class="btn btn-sm btn-success">Pagar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
