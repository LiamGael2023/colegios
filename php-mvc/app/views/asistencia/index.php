<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="ti ti-calendar-check me-2"></i>Seleccionar Sección
        </h3>
    </div>
    <div class="card-body">
        <form id="formAsistencia" method="GET" action="<?= APP_URL ?>/asistencia/registrar">
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha" class="form-control" value="<?= $data['fecha'] ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Nivel</label>
                    <select id="nivel" class="form-select" required>
                        <option value="">Seleccionar...</option>
                        <?php foreach ($data['niveles'] as $nivel): ?>
                            <option value="<?= $nivel->id ?>"><?= $nivel->nombre ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Grado</label>
                    <select id="grado" class="form-select" disabled required>
                        <option value="">Seleccionar...</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sección</label>
                    <select name="seccion_id" id="seccion" class="form-select" disabled required>
                        <option value="">Seleccionar...</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ti ti-arrow-right me-1"></i> Continuar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
const baseUrl = '<?= APP_URL ?>';

document.getElementById('nivel').addEventListener('change', function() {
    const nivelId = this.value;
    const gradoSelect = document.getElementById('grado');

    if (nivelId) {
        fetch(`${baseUrl}/notas/getGrados?nivel_id=${nivelId}`)
            .then(r => r.json())
            .then(grados => {
                gradoSelect.innerHTML = '<option value="">Seleccionar...</option>';
                grados.forEach(g => {
                    gradoSelect.innerHTML += `<option value="${g.id}">${g.nombre}</option>`;
                });
                gradoSelect.disabled = false;
            });
    }
});

document.getElementById('grado').addEventListener('change', function() {
    const gradoId = this.value;
    const seccionSelect = document.getElementById('seccion');

    if (gradoId) {
        fetch(`${baseUrl}/notas/getSecciones?grado_id=${gradoId}`)
            .then(r => r.json())
            .then(secciones => {
                seccionSelect.innerHTML = '<option value="">Seleccionar...</option>';
                secciones.forEach(s => {
                    seccionSelect.innerHTML += `<option value="${s.id}">${s.nombre}</option>`;
                });
                seccionSelect.disabled = false;
            });
    }
});
</script>
