<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="page-pretitle">Comunicados</div>
                <h2 class="page-title">Nuevo Comunicado</h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="<?= APP_URL ?>/comunicados" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
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

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Datos del Comunicado</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?= APP_URL ?>/comunicados/crear" method="POST">
                            <div class="mb-3">
                                <label class="form-label required">Título</label>
                                <input type="text" name="titulo" class="form-control" required
                                       placeholder="Título del comunicado">
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Contenido</label>
                                <textarea name="contenido" class="form-control" rows="6" required
                                          placeholder="Escriba el contenido del comunicado..."></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tipo de Destinatario</label>
                                        <select name="tipo" id="tipo" class="form-select">
                                            <option value="GENERAL">General (Todos)</option>
                                            <option value="NIVEL">Por Nivel</option>
                                            <option value="GRADO">Por Grado</option>
                                            <option value="SECCION">Por Sección</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Destinatario</label>
                                        <select name="destinatario_id" id="destinatario_id" class="form-select" disabled>
                                            <option value="">No aplica</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Fecha de Expiración (opcional)</label>
                                <input type="date" name="fecha_expiracion" class="form-control">
                                <small class="form-hint">El comunicado dejará de mostrarse después de esta fecha</small>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="<?= APP_URL ?>/comunicados" class="btn btn-outline-secondary me-2">Cancelar</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-send me-1"></i>Publicar Comunicado
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const niveles = <?= json_encode($niveles) ?>;
const grados = <?= json_encode($grados) ?>;
const secciones = <?= json_encode($secciones) ?>;

document.getElementById('tipo').addEventListener('change', function() {
    const destinatarioSelect = document.getElementById('destinatario_id');
    destinatarioSelect.innerHTML = '';

    switch (this.value) {
        case 'GENERAL':
            destinatarioSelect.innerHTML = '<option value="">No aplica</option>';
            destinatarioSelect.disabled = true;
            break;
        case 'NIVEL':
            destinatarioSelect.disabled = false;
            destinatarioSelect.innerHTML = '<option value="">Seleccione un nivel</option>';
            niveles.forEach(n => {
                destinatarioSelect.innerHTML += `<option value="${n.id}">${n.nombre}</option>`;
            });
            break;
        case 'GRADO':
            destinatarioSelect.disabled = false;
            destinatarioSelect.innerHTML = '<option value="">Seleccione un grado</option>';
            grados.forEach(g => {
                destinatarioSelect.innerHTML += `<option value="${g.id}">${g.nivel_nombre} - ${g.nombre}</option>`;
            });
            break;
        case 'SECCION':
            destinatarioSelect.disabled = false;
            destinatarioSelect.innerHTML = '<option value="">Seleccione una sección</option>';
            secciones.forEach(s => {
                destinatarioSelect.innerHTML += `<option value="${s.id}">${s.nivel_nombre} - ${s.grado_nombre} "${s.nombre}"</option>`;
            });
            break;
    }
});
</script>
