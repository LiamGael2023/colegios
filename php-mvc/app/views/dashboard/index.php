<div class="row row-deck row-cards fade-in">
    <div class="col-6 col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-primary-lt text-primary me-3">
                        <i class="ti ti-users"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold">Estudiantes</div>
                        <div class="h2 mb-0"><?= $data['totalEstudiantes'] ?></div>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-primary-lt">
                        <i class="ti ti-check me-1"></i>Registrados
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-success-lt text-success me-3">
                        <i class="ti ti-user-check"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold">Matriculados</div>
                        <div class="h2 mb-0"><?= $data['totalMatriculas'] ?></div>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-success-lt">
                        <i class="ti ti-circle-check me-1"></i>Activos
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-warning-lt text-warning me-3">
                        <i class="ti ti-alert-triangle"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold">Pendientes</div>
                        <div class="h2 mb-0"><?= $data['pagosPendientes'] ?></div>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-warning-lt">
                        <i class="ti ti-clock me-1"></i>Por cobrar
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-teal-lt text-teal me-3">
                        <i class="ti ti-cash"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold">Ingresos</div>
                        <div class="h2 mb-0">S/ <?= number_format($data['ingresosTotales'], 0) ?></div>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-teal-lt">
                        <i class="ti ti-trending-up me-1"></i>Recaudado
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-deck row-cards mt-3">
    <!-- Gráfico de Estudiantes por Nivel -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-chart-pie me-2"></i>Estudiantes por Nivel
                </h3>
            </div>
            <div class="card-body">
                <canvas id="chartNiveles" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Gráfico de Ingresos por Mes -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-chart-bar me-2"></i>Ingresos por Mes
                </h3>
            </div>
            <div class="card-body">
                <canvas id="chartIngresos" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row row-deck row-cards mt-3">
    <!-- Accesos Rápidos -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-bolt me-2 text-warning"></i>Accesos Rápidos
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6 col-md-4">
                        <a href="<?= APP_URL ?>/estudiantes/crear" class="btn btn-outline-primary w-100 d-flex flex-column align-items-center py-3">
                            <i class="ti ti-user-plus fs-2 mb-1"></i>
                            <span class="small">Nuevo Estudiante</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a href="<?= APP_URL ?>/notas" class="btn btn-outline-success w-100 d-flex flex-column align-items-center py-3">
                            <i class="ti ti-notebook fs-2 mb-1"></i>
                            <span class="small">Ingresar Notas</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a href="<?= APP_URL ?>/asistencia" class="btn btn-outline-info w-100 d-flex flex-column align-items-center py-3">
                            <i class="ti ti-calendar-check fs-2 mb-1"></i>
                            <span class="small">Tomar Asistencia</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a href="<?= APP_URL ?>/pagos/nuevo" class="btn btn-outline-warning w-100 d-flex flex-column align-items-center py-3">
                            <i class="ti ti-credit-card fs-2 mb-1"></i>
                            <span class="small">Registrar Pago</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a href="<?= APP_URL ?>/reportes/consolidado" class="btn btn-outline-secondary w-100 d-flex flex-column align-items-center py-3">
                            <i class="ti ti-file-text fs-2 mb-1"></i>
                            <span class="small">Consolidado</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a href="<?= APP_URL ?>/pagos/morosidad" class="btn btn-outline-danger w-100 d-flex flex-column align-items-center py-3">
                            <i class="ti ti-alert-circle fs-2 mb-1"></i>
                            <span class="small">Morosidad</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Sistema -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-info-circle me-2"></i>Información del Sistema
                </h3>
            </div>
            <div class="card-body">
                <div class="datagrid">
                    <div class="datagrid-item">
                        <div class="datagrid-title">Usuario</div>
                        <div class="datagrid-content"><?= $_SESSION['usuario_email'] ?></div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Rol</div>
                        <div class="datagrid-content">
                            <span class="badge bg-blue-lt"><?= $_SESSION['usuario_rol'] ?></span>
                        </div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Año Escolar</div>
                        <div class="datagrid-content">
                            <?php if ($data['anioActivo']): ?>
                                <span class="badge bg-green-lt"><?= $data['anioActivo']->anio ?></span>
                            <?php else: ?>
                                <span class="badge bg-red-lt">No configurado</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Profesores</div>
                        <div class="datagrid-content"><?= $data['totalProfesores'] ?></div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Apoderados</div>
                        <div class="datagrid-content"><?= $data['totalApoderados'] ?></div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Fecha</div>
                        <div class="datagrid-content"><?= date('d/m/Y') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Datos para gráficos
<?php
$nivelesLabels = [];
$nivelesData = [];
foreach ($data['estudiantesPorNivel'] as $nivel) {
    $nivelesLabels[] = $nivel->nombre;
    $nivelesData[] = $nivel->total;
}

$meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
$ingresosData = array_fill(0, 12, 0);
foreach ($data['ingresosPorMes'] as $ing) {
    $ingresosData[$ing->mes - 1] = floatval($ing->total);
}
?>

// Gráfico de Estudiantes por Nivel
new Chart(document.getElementById('chartNiveles'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($nivelesLabels) ?>,
        datasets: [{
            data: <?= json_encode($nivelesData) ?>,
            backgroundColor: ['#206bc4', '#4299e1', '#94d3a2'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Gráfico de Ingresos por Mes
new Chart(document.getElementById('chartIngresos'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($meses) ?>,
        datasets: [{
            label: 'Ingresos (S/)',
            data: <?= json_encode($ingresosData) ?>,
            backgroundColor: '#206bc4',
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'S/ ' + value.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>
