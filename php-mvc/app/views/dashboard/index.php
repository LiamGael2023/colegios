<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-0 small">Total Estudiantes</p>
                        <h3 class="mb-0"><?= $data['totalEstudiantes'] ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-person-check text-success" style="font-size: 2rem;"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-0 small">Matriculados</p>
                        <h3 class="mb-0"><?= $data['totalMatriculas'] ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-credit-card text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-0 small">Pagos Pendientes</p>
                        <h3 class="mb-0"><?= $data['pagosPendientes'] ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card danger">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-cash-stack text-danger" style="font-size: 2rem;"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-0 small">Ingresos Totales</p>
                        <h3 class="mb-0">S/ <?= number_format($data['ingresosTotales'], 2) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Accesos Rápidos</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="<?= APP_URL ?>/estudiantes/crear" class="btn btn-outline-primary w-100">
                            <i class="bi bi-person-plus"></i> Nuevo Estudiante
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= APP_URL ?>/notas" class="btn btn-outline-success w-100">
                            <i class="bi bi-journal-text"></i> Ingresar Notas
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= APP_URL ?>/asistencia" class="btn btn-outline-info w-100">
                            <i class="bi bi-calendar-check"></i> Tomar Asistencia
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= APP_URL ?>/pagos" class="btn btn-outline-warning w-100">
                            <i class="bi bi-credit-card"></i> Registrar Pago
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información del Sistema</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted">Usuario:</td>
                        <td class="text-end fw-medium"><?= $_SESSION['usuario_email'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Rol:</td>
                        <td class="text-end fw-medium"><?= $_SESSION['usuario_rol'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Año Escolar:</td>
                        <td class="text-end fw-medium">
                            <?= $data['anioActivo'] ? $data['anioActivo']->anio : 'No configurado' ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total Profesores:</td>
                        <td class="text-end fw-medium"><?= $data['totalProfesores'] ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
