<div class="row g-2 align-items-center mb-3">
    <div class="col">
        <h3 class="page-title">Conceptos de Pago</h3>
    </div>
    <div class="col-auto">
        <a href="<?= APP_URL ?>/pagos/crearConcepto" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Nuevo Concepto
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th class="text-end">Monto</th>
                    <th class="text-center">Recurrente</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['conceptos'])): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No hay conceptos de pago registrados
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['conceptos'] as $concepto): ?>
                        <tr>
                            <td>
                                <div class="font-weight-medium"><?= $concepto->nombre ?></div>
                            </td>
                            <td class="text-muted"><?= $concepto->descripcion ?: '-' ?></td>
                            <td class="text-end">
                                <strong>S/ <?= number_format($concepto->monto, 2) ?></strong>
                            </td>
                            <td class="text-center">
                                <?php if ($concepto->es_recurrente): ?>
                                    <span class="badge bg-green-lt">Sí</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-lt">No</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="<?= APP_URL ?>/pagos/editarConcepto/<?= $concepto->id ?>"
                                       class="btn btn-sm" title="Editar">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>/pagos/eliminarConcepto/<?= $concepto->id ?>"
                                       class="btn btn-sm text-red"
                                       onclick="return confirm('¿Está seguro de eliminar este concepto?')"
                                       title="Eliminar">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
