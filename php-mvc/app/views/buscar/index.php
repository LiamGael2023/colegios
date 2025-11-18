<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="page-pretitle">Resultados de búsqueda</div>
        <h2 class="page-title">
            <i class="ti ti-search me-2"></i>
            <?php if (isset($total)): ?>
                <?= $total ?> resultado(s) para "<?= htmlspecialchars($q) ?>"
            <?php else: ?>
                Búsqueda
            <?php endif; ?>
        </h2>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Formulario de búsqueda -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="<?= APP_URL ?>/buscar" method="GET">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">
                            <i class="ti ti-search"></i>
                        </span>
                        <input type="text" name="q" class="form-control" placeholder="Buscar estudiantes, apoderados, pagos, comunicados..."
                               value="<?= htmlspecialchars($q) ?>" autofocus>
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-info">
                <i class="ti ti-info-circle me-2"></i><?= $mensaje ?>
            </div>
        <?php elseif (empty($resultados)): ?>
            <div class="empty">
                <div class="empty-icon">
                    <i class="ti ti-search-off" style="font-size: 3rem;"></i>
                </div>
                <p class="empty-title">No se encontraron resultados</p>
                <p class="empty-subtitle text-muted">
                    No hay coincidencias para "<?= htmlspecialchars($q) ?>". Intente con otros términos.
                </p>
            </div>
        <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($resultados as $resultado): ?>
                    <a href="<?= $resultado['url'] ?>" class="list-group-item list-group-item-action">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar bg-primary-lt">
                                    <i class="ti <?= $resultado['icono'] ?>"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-between">
                                    <div class="font-weight-medium"><?= htmlspecialchars($resultado['titulo']) ?></div>
                                    <span class="badge bg-azure-lt"><?= $resultado['tipo'] ?></span>
                                </div>
                                <div class="text-muted small"><?= htmlspecialchars($resultado['subtitulo']) ?></div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="mt-3 text-muted text-center">
                <small>Mostrando los primeros resultados de cada categoría</small>
            </div>
        <?php endif; ?>
    </div>
</div>
