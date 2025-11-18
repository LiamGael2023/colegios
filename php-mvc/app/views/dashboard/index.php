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
                        <a href="<?= APP_URL ?>/pagos" class="btn btn-outline-warning w-100">
                            <i class="ti ti-credit-card me-1"></i> Registrar Pago
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= APP_URL ?>/reportes/libreta" class="btn btn-outline-secondary w-100">
                            <i class="ti ti-file-text me-1"></i> Libreta de Notas
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
                        <div class="datagrid-title">Total Profesores</div>
                        <div class="datagrid-content"><?= $data['totalProfesores'] ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
