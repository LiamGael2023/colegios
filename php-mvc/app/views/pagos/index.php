<div class="row row-deck row-cards mb-4">
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total a Cobrar</div>
                </div>
                <div class="h1 mb-0">S/ <?= number_format($data['totalMonto'], 2) ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Recaudado</div>
                </div>
                <div class="h1 mb-0 text-green">S/ <?= number_format($data['totalPagado'], 2) ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Pendiente</div>
                </div>
                <div class="h1 mb-0 text-red">S/ <?= number_format($data['totalMonto'] - $data['totalPagado'], 2) ?></div>
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
                <button type="submit" class="btn btn-primary w-100">
                    <i class="ti ti-filter me-1"></i> Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Recibo</th>
                    <th>Estudiante</th>
                    <th>Concepto</th>
                    <th class="text-center">Mes</th>
                    <th class="text-end">Monto</th>
                    <th class="text-end">Pagado</th>
                    <th class="text-center">Estado</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['pagos'])): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="ti ti-receipt-off ti-lg mb-2"></i><br>
                            No hay pagos registrados
                        </td>
                    </tr>
                <?php else: ?>
                    <?php
                    $meses = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
                    foreach ($data['pagos'] as $pago):
                    ?>
                        <tr>
                            <td class="text-muted"><?= $pago->numero_recibo ?></td>
                            <td>
                                <div class="d-flex py-1 align-items-center">
                                    <span class="avatar avatar-sm bg-primary-lt me-2">
                                        <?= strtoupper(substr($pago->nombres, 0, 1)) ?>
                                    </span>
                                    <div class="flex-fill">
                                        <div class="font-weight-medium"><?= $pago->apellido_paterno ?> <?= $pago->apellido_materno ?></div>
                                        <div class="text-muted small"><?= $pago->nombres ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?= $pago->concepto_nombre ?></td>
                            <td class="text-center"><?= $pago->mes ? $meses[$pago->mes] : '-' ?></td>
                            <td class="text-end">S/ <?= number_format($pago->monto, 2) ?></td>
                            <td class="text-end">S/ <?= number_format($pago->monto_pagado, 2) ?></td>
                            <td class="text-center">
                                <?php
                                $badges = [
                                    'PENDIENTE' => 'yellow',
                                    'PAGADO' => 'green',
                                    'PARCIAL' => 'azure',
                                    'VENCIDO' => 'red',
                                    'ANULADO' => 'secondary'
                                ];
                                ?>
                                <span class="badge bg-<?= $badges[$pago->estado] ?>-lt"><?= $pago->estado ?></span>
                            </td>
                            <td>
                                <?php if ($pago->estado != 'PAGADO' && $pago->estado != 'ANULADO'): ?>
                                    <a href="<?= APP_URL ?>/pagos/registrar/<?= $pago->id ?>"
                                       class="btn btn-sm btn-success">
                                        <i class="ti ti-cash me-1"></i> Pagar
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
