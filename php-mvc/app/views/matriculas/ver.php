<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="page-pretitle">Matrícula</div>
                <h2 class="page-title"><?= htmlspecialchars($matricula->codigo) ?></h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="<?= APP_URL ?>/matriculas" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Volver
                </a>
                <a href="<?= APP_URL ?>/matriculas/ficha/<?= $matricula->id ?>" class="btn btn-primary">
                    <i class="ti ti-printer me-1"></i>Imprimir Ficha
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <!-- Datos del Estudiante -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="ti ti-user me-2"></i>Datos del Estudiante</h3>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-5">Código:</dt>
                            <dd class="col-7"><?= htmlspecialchars($matricula->codigo) ?></dd>

                            <dt class="col-5">DNI:</dt>
                            <dd class="col-7"><?= htmlspecialchars($matricula->dni ?? 'Sin DNI') ?></dd>

                            <dt class="col-5">Nombres:</dt>
                            <dd class="col-7"><?= htmlspecialchars($matricula->nombres) ?></dd>

                            <dt class="col-5">Apellidos:</dt>
                            <dd class="col-7"><?= htmlspecialchars($matricula->apellido_paterno . ' ' . $matricula->apellido_materno) ?></dd>

                            <dt class="col-5">Fecha Nac.:</dt>
                            <dd class="col-7"><?= date('d/m/Y', strtotime($matricula->fecha_nacimiento)) ?></dd>

                            <dt class="col-5">Género:</dt>
                            <dd class="col-7"><?= $matricula->genero ?></dd>

                            <dt class="col-5">Dirección:</dt>
                            <dd class="col-7"><?= htmlspecialchars($matricula->direccion ?? 'No registrada') ?></dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Datos de la Matrícula -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="ti ti-file-certificate me-2"></i>Datos de Matrícula</h3>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-5">Año Escolar:</dt>
                            <dd class="col-7"><span class="badge bg-blue"><?= $matricula->anio ?></span></dd>

                            <dt class="col-5">Nivel:</dt>
                            <dd class="col-7"><?= htmlspecialchars($matricula->nivel_nombre) ?></dd>

                            <dt class="col-5">Grado:</dt>
                            <dd class="col-7"><?= htmlspecialchars($matricula->grado_nombre) ?></dd>

                            <dt class="col-5">Sección:</dt>
                            <dd class="col-7"><?= htmlspecialchars($matricula->seccion_nombre) ?></dd>

                            <dt class="col-5">Fecha Matrícula:</dt>
                            <dd class="col-7"><?= date('d/m/Y', strtotime($matricula->fecha_matricula)) ?></dd>

                            <dt class="col-5">Tipo:</dt>
                            <dd class="col-7"><?= $matricula->tipo_matricula ?></dd>

                            <dt class="col-5">Estado:</dt>
                            <dd class="col-7">
                                <?php
                                $badgeClass = match($matricula->estado) {
                                    'ACTIVA' => 'bg-success',
                                    'RETIRADO' => 'bg-danger',
                                    'TRASLADADO' => 'bg-warning',
                                    'FINALIZADA' => 'bg-secondary',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= $matricula->estado ?></span>
                            </dd>

                            <?php if ($matricula->procedencia): ?>
                                <dt class="col-5">Procedencia:</dt>
                                <dd class="col-7"><?= htmlspecialchars($matricula->procedencia) ?></dd>
                            <?php endif; ?>
                        </dl>

                        <?php if ($matricula->observaciones): ?>
                            <hr>
                            <strong>Observaciones:</strong>
                            <p class="text-muted"><?= nl2br(htmlspecialchars($matricula->observaciones)) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Apoderados -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="ti ti-users me-2"></i>Apoderados</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>DNI</th>
                                    <th>Parentesco</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($apoderados)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No hay apoderados registrados</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($apoderados as $ap): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($ap->nombres . ' ' . $ap->apellidos) ?></td>
                                            <td><?= htmlspecialchars($ap->dni) ?></td>
                                            <td>
                                                <?= $ap->parentesco ?>
                                                <?php if ($ap->es_principal): ?>
                                                    <span class="badge bg-green-lt ms-1">Principal</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($ap->telefono) ?></td>
                                            <td><?= htmlspecialchars($ap->email ?? '-') ?></td>
                                            <td>
                                                <a href="<?= APP_URL ?>/apoderados/editar/<?= $ap->id ?>" class="btn btn-sm btn-ghost-primary">
                                                    <i class="ti ti-edit"></i>
                                                </a>
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
    </div>
</div>
