<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Seleccionar Sección y Curso</h5>
    </div>
    <div class="card-body">
        <form id="formNotas" method="GET" action="<?= APP_URL ?>/notas/registrar">
            <div class="row g-3">
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
                <div class="col-md-3">
                    <label class="form-label">Curso</label>
                    <select name="curso_id" id="curso" class="form-select" disabled required>
                        <option value="">Seleccionar...</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Período</label>
                    <select name="periodo_id" class="form-select" required>
                        <?php foreach ($data['periodos'] as $periodo): ?>
                            <option value="<?= $periodo->id ?>"><?= $periodo->nombre ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-arrow-right"></i>
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
    gradoSelect.innerHTML = '<option value="">Cargando...</option>';
    gradoSelect.disabled = true;

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
    const cursoSelect = document.getElementById('curso');

    if (gradoId) {
        // Cargar secciones
        fetch(`${baseUrl}/notas/getSecciones?grado_id=${gradoId}`)
            .then(r => r.json())
            .then(secciones => {
                seccionSelect.innerHTML = '<option value="">Seleccionar...</option>';
                secciones.forEach(s => {
                    seccionSelect.innerHTML += `<option value="${s.id}">${s.nombre}</option>`;
                });
                seccionSelect.disabled = false;
            });

        // Cargar cursos
        fetch(`${baseUrl}/notas/getCursos?grado_id=${gradoId}`)
            .then(r => r.json())
            .then(cursos => {
                cursoSelect.innerHTML = '<option value="">Seleccionar...</option>';
                cursos.forEach(c => {
                    cursoSelect.innerHTML += `<option value="${c.id}">${c.nombre}</option>`;
                });
                cursoSelect.disabled = false;
            });
    }
});
</script>
