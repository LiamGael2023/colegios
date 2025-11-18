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
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['cursos'])): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No hay cursos</td>
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
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
