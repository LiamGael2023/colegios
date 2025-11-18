<div class="row g-2 align-items-center mb-3">
    <div class="col">
        <h3 class="page-title">Consolidado de Notas</h3>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Sección</label>
                <select name="seccion" class="form-select" required onchange="this.form.submit()">
                    <option value="">Seleccionar sección...</option>
                    <?php foreach ($data['secciones'] as $seccion): ?>
                        <option value="<?= $seccion->id ?>" <?= $data['seccionId'] == $seccion->id ? 'selected' : '' ?>>
                            <?= $seccion->nivel_nombre ?> - <?= $seccion->grado_nombre ?> "<?= $seccion->nombre ?>"
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Período</label>
                <select name="periodo" class="form-select" required>
                    <option value="">Seleccionar período...</option>
                    <?php foreach ($data['periodos'] as $periodo): ?>
                        <option value="<?= $periodo->id ?>" <?= $data['periodoId'] == $periodo->id ? 'selected' : '' ?>>
                            <?= $periodo->nombre ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-search me-1"></i> Generar
                </button>
                <?php if (!empty($data['estudiantes'])): ?>
                    <button type="button" class="btn btn-outline-primary ms-2" onclick="window.print()">
                        <i class="ti ti-printer me-1"></i> Imprimir
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($data['estudiantes']) && !empty($data['cursos'])): ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <?= $data['seccion']->grado_nombre ?> "<?= $data['seccion']->nombre ?>" - <?= $data['periodo']->nombre ?>
        </h3>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table table-sm">
            <thead>
                <tr>
                    <th class="w-1">#</th>
                    <th>Estudiante</th>
                    <?php foreach ($data['cursos'] as $curso): ?>
                        <th class="text-center" style="font-size: 0.75rem; writing-mode: vertical-lr; transform: rotate(180deg); height: 100px;">
                            <?= $curso->nombre ?>
                        </th>
                    <?php endforeach; ?>
                    <th class="text-center">Prom.</th>
                </tr>
            </thead>
            <tbody>
                <?php $num = 0; foreach ($data['estudiantes'] as $est): $num++; ?>
                    <tr>
                        <td class="text-muted"><?= $num ?></td>
                        <td>
                            <?= $est->apellido_paterno ?> <?= $est->apellido_materno ?>, <?= $est->nombres ?>
                        </td>
                        <?php
                        $suma = 0;
                        $count = 0;
                        foreach ($data['cursos'] as $curso):
                            $nota = isset($data['notas'][$est->id][$curso->id]) ? $data['notas'][$est->id][$curso->id] : '-';
                            if (is_numeric($nota)) {
                                $suma += $nota;
                                $count++;
                            }
                        ?>
                            <td class="text-center"><?= $nota ?></td>
                        <?php endforeach; ?>
                        <td class="text-center fw-bold">
                            <?= $count > 0 ? round($suma / $count, 1) : '-' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php elseif ($data['seccionId'] && $data['periodoId']): ?>
<div class="alert alert-info">
    <i class="ti ti-info-circle me-2"></i>No hay datos para mostrar
</div>
<?php endif; ?>
