<div class="d-flex justify-content-between align-items-center mb-4">
    <form class="d-flex gap-2" method="GET">
        <input type="text" name="buscar" class="form-control" placeholder="Buscar estudiante..."
               value="<?= $data['buscar'] ?>">
        <button type="submit" class="btn btn-outline-primary">
            <i class="bi bi-search"></i>
        </button>
    </form>
    <a href="<?= APP_URL ?>/estudiantes/crear" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nuevo Estudiante
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Código</th>
                    <th>Estudiante</th>
                    <th>DNI</th>
                    <th>Grado/Sección</th>
                    <th>Apoderado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['estudiantes'])): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No se encontraron estudiantes
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['estudiantes'] as $est): ?>
                        <tr>
                            <td class="fw-medium"><?= $est->codigo ?></td>
                            <td>
                                <div><?= $est->apellido_paterno ?> <?= $est->apellido_materno ?></div>
                                <small class="text-muted"><?= $est->nombres ?></small>
                            </td>
                            <td><?= $est->dni ?: '-' ?></td>
                            <td>
                                <?php if ($est->grado_nombre): ?>
                                    <?= $est->grado_nombre ?> "<?= $est->seccion_nombre ?>"
                                <?php else: ?>
                                    <span class="text-muted">Sin matrícula</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($est->apoderado_nombres): ?>
                                    <?= $est->apoderado_nombres ?> <?= $est->apoderado_apellidos ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= APP_URL ?>/estudiantes/ver/<?= $est->id ?>"
                                   class="btn btn-sm btn-outline-primary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?= APP_URL ?>/estudiantes/editar/<?= $est->id ?>"
                                   class="btn btn-sm btn-outline-secondary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
