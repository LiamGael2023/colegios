<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="page-pretitle">Horario del Docente</div>
                <h2 class="page-title">
                    <?= htmlspecialchars($docente->nombre . ' ' . $docente->apellidos) ?>
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="<?= APP_URL ?>/horarios" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Volver
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="ti ti-printer me-1"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Horario Semanal</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered card-table">
                    <thead>
                        <tr>
                            <th style="width: 100px;">Hora</th>
                            <?php foreach ($dias as $dia): ?>
                                <th class="text-center"><?= ucfirst(strtolower($dia)) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Obtener todas las horas únicas
                        $horas = [];
                        foreach ($horarios as $h) {
                            $horas[$h->hora_inicio . '-' . $h->hora_fin] = [
                                'inicio' => $h->hora_inicio,
                                'fin' => $h->hora_fin
                            ];
                        }
                        ksort($horas);

                        if (empty($horas)):
                        ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No hay horarios registrados para este docente
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($horas as $key => $hora): ?>
                                <tr>
                                    <td class="text-muted">
                                        <?= substr($hora['inicio'], 0, 5) ?><br>
                                        <?= substr($hora['fin'], 0, 5) ?>
                                    </td>
                                    <?php foreach ($dias as $dia): ?>
                                        <td class="p-1">
                                            <?php
                                            $encontrado = false;
                                            foreach ($horariosPorDia[$dia] as $h) {
                                                if ($h->hora_inicio == $hora['inicio'] && $h->hora_fin == $hora['fin']) {
                                                    $encontrado = true;
                                            ?>
                                                <div class="bg-azure-lt rounded p-2 text-center">
                                                    <div class="fw-bold small"><?= htmlspecialchars($h->curso_nombre) ?></div>
                                                    <div class="text-muted small">
                                                        <?= htmlspecialchars($h->grado_nombre . ' "' . $h->seccion_nombre . '"') ?>
                                                    </div>
                                                </div>
                                            <?php
                                                }
                                            }
                                            if (!$encontrado) echo '<div class="text-muted text-center">-</div>';
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
