<div class="row row-deck row-cards">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Estudiantes</div>
                </div>
                <div class="h1 mb-3"><?= $data['totalEstudiantes'] ?></div>
                <div class="d-flex mb-2">
                    <div>
                        <span class="text-green d-inline-flex align-items-center lh-1">
                            <i class="ti ti-users me-1"></i> Registrados
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Matriculados</div>
                </div>
                <div class="h1 mb-3"><?= $data['totalMatriculas'] ?></div>
                <div class="d-flex mb-2">
                    <div>
                        <span class="text-blue d-inline-flex align-items-center lh-1">
                            <i class="ti ti-user-check me-1"></i> Activos
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Pagos Pendientes</div>
                </div>
                <div class="h1 mb-3"><?= $data['pagosPendientes'] ?></div>
                <div class="d-flex mb-2">
                    <div>
                        <span class="text-yellow d-inline-flex align-items-center lh-1">
                            <i class="ti ti-alert-triangle me-1"></i> Por cobrar
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Ingresos Totales</div>
                </div>
                <div class="h1 mb-3">S/ <?= number_format($data['ingresosTotales'], 2) ?></div>
                <div class="d-flex mb-2">
                    <div>
                        <span class="text-green d-inline-flex align-items-center lh-1">
                            <i class="ti ti-cash me-1"></i> Recaudado
                        </span>
                    </div>
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
                    <i class="ti ti-bolt me-2"></i>Accesos Rápidos
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="<?= APP_URL ?>/estudiantes/crear" class="btn btn-outline-primary w-100">
                            <i class="ti ti-user-plus me-1"></i> Nuevo Estudiante
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= APP_URL ?>/notas" class="btn btn-outline-success w-100">
                            <i class="ti ti-notebook me-1"></i> Ingresar Notas
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= APP_URL ?>/asistencia" class="btn btn-outline-info w-100">
                            <i class="ti ti-calendar-check me-1"></i> Tomar Asistencia
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= APP_URL ?>/pagos/nuevo" class="btn btn-outline-warning w-100">
                            <i class="ti ti-credit-card me-1"></i> Registrar Pago
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= APP_URL ?>/reportes/consolidado" class="btn btn-outline-secondary w-100">
                            <i class="ti ti-file-text me-1"></i> Consolidado Notas
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= APP_URL ?>/pagos/morosidad" class="btn btn-outline-danger w-100">
                            <i class="ti ti-alert-circle me-1"></i> Morosidad
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
