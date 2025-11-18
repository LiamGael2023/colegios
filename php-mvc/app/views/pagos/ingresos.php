<div class="row g-2 align-items-center mb-3">
    <div class="col">
        <h3 class="page-title">Reporte de Ingresos</h3>
    </div>
    <div class="col-auto">
        <form method="GET" class="row g-2">
            <div class="col-auto">
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
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-filter me-1"></i> Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row row-deck row-cards mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Recaudado</div>
                </div>
                <div class="h1 mb-0 text-green">S/ <?= number_format($data['total'], 2) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['ingresos'])): ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">
                            No hay ingresos registrados
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['ingresos'] as $ingreso): ?>
                        <tr>
                            <td>
                                <div class="font-weight-medium"><?= $ingreso->concepto ?></div>
                            </td>
                            <td class="text-center"><?= $ingreso->cantidad ?></td>
                            <td class="text-end">
                                <strong>S/ <?= number_format($ingreso->total, 2) ?></strong>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-end fw-bold">Total:</td>
                    <td class="text-end fw-bold text-green">S/ <?= number_format($data['total'], 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
