<?php
$matriculas = $data['matriculas'] ?? [];
$secciones = $data['secciones'] ?? [];
$seccionId = $data['seccionId'] ?? '';
$estado = $data['estado'] ?? '';
$anio = $data['anio'] ?? null;
?>

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="page-pretitle">Gestión</div>
                <h2 class="page-title">
                    <i class="ti ti-file-certificate me-2"></i>Matrículas <?= $anio ? $anio->anio : '' ?>
                </h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="<?= APP_URL ?>/matriculas/nueva" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>Nueva Matrícula
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible">
                <div class="d-flex">
                    <div><i class="ti ti-check icon alert-icon"></i></div>
                    <div><?= $_SESSION['success'] ?></div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <div class="d-flex">
                    <div><i class="ti ti-alert-circle icon alert-icon"></i></div>
                    <div><?= $_SESSION['error'] ?></div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Filtros -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Sección</label>
                        <select name="seccion_id" class="form-select">
                            <option value="">Todas las secciones</option>
                            <?php foreach ($secciones as $seccion): ?>
                                <option value="<?= $seccion->id ?>" <?= $seccionId == $seccion->id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($seccion->nivel_nombre . ' - ' . $seccion->grado_nombre . ' "' . $seccion->nombre . '"') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos</option>
                            <option value="ACTIVA" <?= $estado == 'ACTIVA' ? 'selected' : '' ?>>Activa</option>
                            <option value="RETIRADO" <?= $estado == 'RETIRADO' ? 'selected' : '' ?>>Retirado</option>
                            <option value="TRASLADADO" <?= $estado == 'TRASLADADO' ? 'selected' : '' ?>>Trasladado</option>
                            <option value="FINALIZADA" <?= $estado == 'FINALIZADA' ? 'selected' : '' ?>>Finalizada</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-filter me-1"></i>Filtrar
                        </button>
                        <a href="<?= APP_URL ?>/matriculas" class="btn btn-outline-secondary ms-2">
                            <i class="ti ti-x me-1"></i>Limpiar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Matrículas</h3>
                <div class="card-actions">
                    <span class="badge bg-blue"><?= count($matriculas) ?> registros</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Estudiante</th>
                            <th>DNI</th>
                            <th>Sección</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th class="w-1">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($matriculas)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">No hay matrículas registradas</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($matriculas as $mat): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-azure-lt"><?= htmlspecialchars($mat->codigo) ?></span>
                                    </td>
                                    <td>
                                        <div class="font-weight-medium">
                                            <?= htmlspecialchars($mat->apellido_paterno . ' ' . $mat->apellido_materno . ', ' . $mat->nombres) ?>
                                        </div>
                                        <div class="text-muted small">Código: <?= htmlspecialchars($mat->codigo) ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($mat->dni ?? 'Sin DNI') ?></td>
                                    <td>
                                        <div><?= htmlspecialchars($mat->grado_nombre . ' "' . $mat->seccion_nombre . '"') ?></div>
                                        <div class="text-muted small"><?= htmlspecialchars($mat->nivel_nombre) ?></div>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($mat->fecha_matricula)) ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = match($mat->estado) {
                                            'ACTIVA' => 'bg-success',
                                            'RETIRADO' => 'bg-danger',
                                            'TRASLADADO' => 'bg-warning',
                                            'FINALIZADA' => 'bg-secondary',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= $mat->estado ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= APP_URL ?>/matriculas/ver/<?= $mat->id ?>" class="btn btn-sm btn-ghost-primary" title="Ver detalle">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="<?= APP_URL ?>/matriculas/ficha/<?= $mat->id ?>" class="btn btn-sm btn-ghost-info" title="Ficha de matrícula">
                                                <i class="ti ti-file-text"></i>
                                            </a>
                                            <?php if (in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR', 'SECRETARIA']) && $mat->estado == 'ACTIVA'): ?>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-ghost-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item text-warning" href="<?= APP_URL ?>/matriculas/cambiarEstado/<?= $mat->id ?>?estado=RETIRADO"
                                                           onclick="return confirm('¿Marcar como RETIRADO?')">
                                                            Marcar Retirado
                                                        </a>
                                                        <a class="dropdown-item text-info" href="<?= APP_URL ?>/matriculas/cambiarEstado/<?= $mat->id ?>?estado=TRASLADADO"
                                                           onclick="return confirm('¿Marcar como TRASLADADO?')">
                                                            Marcar Trasladado
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
