<div class="row g-2 align-items-center mb-3">
    <div class="col">
        <h3 class="page-title">Cursos</h3>
    </div>
    <div class="col-auto">
        <a href="<?= APP_URL ?>/configuracion/crearCurso" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Nuevo Curso
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Área Curricular</th>
                    <th>Grado</th>
                    <th class="text-center">Horas/Sem</th>
                    <th class="w-1">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['cursos'])): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No hay cursos</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['cursos'] as $curso): ?>
                        <tr>
                            <td>
                                <div class="font-weight-medium"><?= $curso->nombre ?></div>
                            </td>
                            <td>
                                <span class="badge bg-cyan-lt"><?= $curso->area_nombre ?></span>
                            </td>
                            <td><?= $curso->grado_nombre ?></td>
                            <td class="text-center"><?= $curso->horas_semanales ?></td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="<?= APP_URL ?>/configuracion/editarCurso/<?= $curso->id ?>"
                                       class="btn btn-sm" title="Editar">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>/configuracion/eliminarCurso/<?= $curso->id ?>"
                                       class="btn btn-sm btn-ghost-danger"
                                       onclick="return confirm('¿Eliminar este curso?')"
                                       title="Eliminar">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
