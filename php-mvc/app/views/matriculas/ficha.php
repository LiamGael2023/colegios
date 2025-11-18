<style>
    @media print {
        .no-print { display: none !important; }
        body { font-size: 12px; }
        .card { border: 1px solid #000 !important; }
    }
</style>

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="page-pretitle">Matrícula</div>
                <h2 class="page-title">Ficha de Matrícula</h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="<?= APP_URL ?>/matriculas/ver/<?= $matricula->id ?>" class="btn btn-outline-secondary">
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
            <div class="card-body">
                <!-- Encabezado -->
                <div class="text-center mb-4">
                    <h3 class="mb-1"><?= htmlspecialchars($institucion->nombre ?? 'INSTITUCIÓN EDUCATIVA') ?></h3>
                    <div class="text-muted">
                        <?= htmlspecialchars($institucion->direccion ?? '') ?><br>
                        Código Modular: <?= htmlspecialchars($institucion->codigo_modular ?? '') ?>
                    </div>
                    <h4 class="mt-3">FICHA DE MATRÍCULA <?= $matricula->anio ?></h4>
                    <div class="badge bg-azure fs-5"><?= htmlspecialchars($matricula->codigo) ?></div>
                </div>

                <hr>

                <!-- Datos del Estudiante -->
                <h5 class="mb-3"><i class="ti ti-user me-2"></i>DATOS DEL ESTUDIANTE</h5>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <strong>Código:</strong> <?= htmlspecialchars($matricula->codigo) ?>
                    </div>
                    <div class="col-md-4">
                        <strong>DNI:</strong> <?= htmlspecialchars($matricula->dni ?? 'Sin DNI') ?>
                    </div>
                    <div class="col-md-4">
                        <strong>Fecha Nac.:</strong> <?= date('d/m/Y', strtotime($matricula->fecha_nacimiento)) ?>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Apellido Paterno:</strong> <?= htmlspecialchars($matricula->apellido_paterno) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Apellido Materno:</strong> <?= htmlspecialchars($matricula->apellido_materno) ?>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Nombres:</strong> <?= htmlspecialchars($matricula->nombres) ?>
                    </div>
                    <div class="col-md-3">
                        <strong>Género:</strong> <?= $matricula->genero ?>
                    </div>
                    <div class="col-md-3">
                        <strong>Nacionalidad:</strong> <?= htmlspecialchars($matricula->nacionalidad ?? 'Peruana') ?>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <strong>Dirección:</strong> <?= htmlspecialchars($matricula->direccion ?? 'No registrada') ?>
                    </div>
                </div>

                <hr>

                <!-- Datos Académicos -->
                <h5 class="mb-3"><i class="ti ti-school me-2"></i>DATOS ACADÉMICOS</h5>
                <div class="row mb-4">
                    <div class="col-md-3">
                        <strong>Nivel:</strong> <?= htmlspecialchars($matricula->nivel_nombre) ?>
                    </div>
                    <div class="col-md-3">
                        <strong>Grado:</strong> <?= htmlspecialchars($matricula->grado_nombre) ?>
                    </div>
                    <div class="col-md-3">
                        <strong>Sección:</strong> <?= htmlspecialchars($matricula->seccion_nombre) ?>
                    </div>
                    <div class="col-md-3">
                        <strong>Tipo:</strong> <?= $matricula->tipo_matricula ?>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Fecha de Matrícula:</strong> <?= date('d/m/Y', strtotime($matricula->fecha_matricula)) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Procedencia:</strong> <?= htmlspecialchars($matricula->procedencia ?? 'No aplica') ?>
                    </div>
                </div>

                <hr>

                <!-- Datos del Apoderado -->
                <h5 class="mb-3"><i class="ti ti-users me-2"></i>DATOS DEL APODERADO</h5>
                <?php
                $apoderadoPrincipal = null;
                foreach ($apoderados as $ap) {
                    if ($ap->es_principal) {
                        $apoderadoPrincipal = $ap;
                        break;
                    }
                }
                if (!$apoderadoPrincipal && !empty($apoderados)) {
                    $apoderadoPrincipal = $apoderados[0];
                }
                ?>
                <?php if ($apoderadoPrincipal): ?>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Nombres y Apellidos:</strong> <?= htmlspecialchars($apoderadoPrincipal->nombres . ' ' . $apoderadoPrincipal->apellidos) ?>
                        </div>
                        <div class="col-md-3">
                            <strong>DNI:</strong> <?= htmlspecialchars($apoderadoPrincipal->dni) ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Parentesco:</strong> <?= $apoderadoPrincipal->parentesco ?>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <strong>Teléfono:</strong> <?= htmlspecialchars($apoderadoPrincipal->telefono) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Email:</strong> <?= htmlspecialchars($apoderadoPrincipal->email ?? '-') ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Ocupación:</strong> <?= htmlspecialchars($apoderadoPrincipal->ocupacion ?? '-') ?>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12">
                            <strong>Dirección:</strong> <?= htmlspecialchars($apoderadoPrincipal->direccion ?? 'No registrada') ?>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No hay apoderado registrado</p>
                <?php endif; ?>

                <hr>

                <!-- Firmas -->
                <div class="row mt-5 pt-5">
                    <div class="col-md-4 text-center">
                        <div style="border-top: 1px solid #000; padding-top: 5px;">
                            Firma del Apoderado
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div style="border-top: 1px solid #000; padding-top: 5px;">
                            Firma del Director
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div style="border-top: 1px solid #000; padding-top: 5px;">
                            Sello de la I.E.
                        </div>
                    </div>
                </div>

                <div class="text-center text-muted mt-4">
                    <small>Documento generado el <?= date('d/m/Y H:i') ?></small>
                </div>
            </div>
        </div>
    </div>
</div>
