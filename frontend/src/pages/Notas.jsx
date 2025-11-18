import { useState, useEffect } from 'react';
import { academicoService, notasService } from '../services/api';
import { Save, Download } from 'lucide-react';

export default function Notas() {
  const [niveles, setNiveles] = useState([]);
  const [anioActivo, setAnioActivo] = useState(null);
  const [secciones, setSecciones] = useState([]);
  const [cursos, setCursos] = useState([]);
  const [selectedNivel, setSelectedNivel] = useState('');
  const [selectedGrado, setSelectedGrado] = useState('');
  const [selectedSeccion, setSelectedSeccion] = useState('');
  const [selectedCurso, setSelectedCurso] = useState('');
  const [selectedPeriodo, setSelectedPeriodo] = useState('');
  const [estudiantes, setEstudiantes] = useState([]);
  const [notas, setNotas] = useState({});
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    loadInitialData();
  }, []);

  const loadInitialData = async () => {
    try {
      const [nivelesRes, anioRes] = await Promise.all([
        academicoService.getNiveles(),
        academicoService.getAnioActivo()
      ]);
      setNiveles(nivelesRes.data);
      setAnioActivo(anioRes.data);
      if (anioRes.data?.periodos?.length > 0) {
        setSelectedPeriodo(anioRes.data.periodos[0].id.toString());
      }
    } catch (error) {
      console.error('Error:', error);
    }
  };

  const handleNivelChange = (nivelId) => {
    setSelectedNivel(nivelId);
    setSelectedGrado('');
    setSelectedSeccion('');
    setSelectedCurso('');
    setEstudiantes([]);
  };

  const handleGradoChange = async (gradoId) => {
    setSelectedGrado(gradoId);
    setSelectedSeccion('');
    setSelectedCurso('');
    setEstudiantes([]);

    if (gradoId) {
      try {
        const [seccionesRes, cursosRes] = await Promise.all([
          academicoService.getSecciones({ gradoId }),
          academicoService.getCursos({ gradoId })
        ]);
        setSecciones(seccionesRes.data);
        setCursos(cursosRes.data);
      } catch (error) {
        console.error('Error:', error);
      }
    }
  };

  const loadEstudiantes = async () => {
    if (!selectedSeccion || !selectedCurso || !selectedPeriodo) return;

    setLoading(true);
    try {
      const res = await notasService.getBySeccionCurso(selectedSeccion, selectedCurso, {
        periodoId: selectedPeriodo
      });

      setEstudiantes(res.data);

      // Inicializar notas
      const notasMap = {};
      res.data.forEach(m => {
        const nota = m.estudiante.notas?.[0];
        notasMap[m.estudiante.id] = nota?.calificacion || '';
      });
      setNotas(notasMap);
    } catch (error) {
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (selectedSeccion && selectedCurso && selectedPeriodo) {
      loadEstudiantes();
    }
  }, [selectedSeccion, selectedCurso, selectedPeriodo]);

  const handleNotaChange = (estudianteId, valor) => {
    setNotas({ ...notas, [estudianteId]: valor });
  };

  const handleSave = async () => {
    setSaving(true);
    try {
      const nivel = niveles.find(n => n.id === parseInt(selectedNivel));
      const tipoCalificacion = nivel?.nombre === 'SECUNDARIA' ? 'VIGESIMAL' : 'LITERAL';

      const notasArray = Object.entries(notas)
        .filter(([_, calificacion]) => calificacion !== '')
        .map(([estudianteId, calificacion]) => ({
          estudianteId: parseInt(estudianteId),
          cursoId: parseInt(selectedCurso),
          periodoId: parseInt(selectedPeriodo),
          calificacion,
          tipo: tipoCalificacion
        }));

      await notasService.registrar({ notas: notasArray });
      alert('Notas guardadas correctamente');
    } catch (error) {
      console.error('Error:', error);
      alert('Error al guardar notas');
    } finally {
      setSaving(false);
    }
  };

  const selectedNivelData = niveles.find(n => n.id === parseInt(selectedNivel));
  const grados = selectedNivelData?.grados || [];
  const isSecundaria = selectedNivelData?.nombre === 'SECUNDARIA';

  return (
    <div>
      <h1 className="text-2xl font-bold text-gray-900 mb-6">Registro de Notas</h1>

      <div className="bg-white rounded-lg shadow p-6 mb-6">
        <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Nivel</label>
            <select
              value={selectedNivel}
              onChange={(e) => handleNivelChange(e.target.value)}
              className="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Seleccionar...</option>
              {niveles.map(nivel => (
                <option key={nivel.id} value={nivel.id}>{nivel.nombre}</option>
              ))}
            </select>
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Grado</label>
            <select
              value={selectedGrado}
              onChange={(e) => handleGradoChange(e.target.value)}
              disabled={!selectedNivel}
              className="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100"
            >
              <option value="">Seleccionar...</option>
              {grados.map(grado => (
                <option key={grado.id} value={grado.id}>{grado.nombre}</option>
              ))}
            </select>
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Sección</label>
            <select
              value={selectedSeccion}
              onChange={(e) => setSelectedSeccion(e.target.value)}
              disabled={!selectedGrado}
              className="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100"
            >
              <option value="">Seleccionar...</option>
              {secciones.map(sec => (
                <option key={sec.id} value={sec.id}>{sec.nombre}</option>
              ))}
            </select>
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Curso</label>
            <select
              value={selectedCurso}
              onChange={(e) => setSelectedCurso(e.target.value)}
              disabled={!selectedGrado}
              className="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100"
            >
              <option value="">Seleccionar...</option>
              {cursos.map(curso => (
                <option key={curso.id} value={curso.id}>{curso.nombre}</option>
              ))}
            </select>
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Período</label>
            <select
              value={selectedPeriodo}
              onChange={(e) => setSelectedPeriodo(e.target.value)}
              className="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              {anioActivo?.periodos?.map(periodo => (
                <option key={periodo.id} value={periodo.id}>{periodo.nombre}</option>
              ))}
            </select>
          </div>
        </div>
      </div>

      {loading ? (
        <div className="flex justify-center py-12">
          <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>
      ) : estudiantes.length > 0 ? (
        <div className="bg-white rounded-lg shadow">
          <div className="p-4 border-b flex justify-between items-center">
            <p className="text-sm text-gray-600">
              {estudiantes.length} estudiantes | Sistema: {isSecundaria ? 'Vigesimal (0-20)' : 'Literal (AD, A, B, C)'}
            </p>
            <button
              onClick={handleSave}
              disabled={saving}
              className="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
            >
              <Save className="w-4 h-4 mr-2" />
              {saving ? 'Guardando...' : 'Guardar Notas'}
            </button>
          </div>

          <div className="overflow-x-auto">
            <table className="w-full">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N°</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estudiante</th>
                  <th className="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nota</th>
                </tr>
              </thead>
              <tbody className="divide-y">
                {estudiantes.map((m, index) => (
                  <tr key={m.estudiante.id} className="hover:bg-gray-50">
                    <td className="px-6 py-4 text-sm text-gray-500">{index + 1}</td>
                    <td className="px-6 py-4 text-sm font-medium">{m.estudiante.codigo}</td>
                    <td className="px-6 py-4 text-sm">
                      {m.estudiante.apellidoPaterno} {m.estudiante.apellidoMaterno}, {m.estudiante.nombres}
                    </td>
                    <td className="px-6 py-4 text-center">
                      {isSecundaria ? (
                        <input
                          type="number"
                          min="0"
                          max="20"
                          value={notas[m.estudiante.id] || ''}
                          onChange={(e) => handleNotaChange(m.estudiante.id, e.target.value)}
                          className="w-20 text-center border rounded-md px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                      ) : (
                        <select
                          value={notas[m.estudiante.id] || ''}
                          onChange={(e) => handleNotaChange(m.estudiante.id, e.target.value)}
                          className="w-20 text-center border rounded-md px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                          <option value="">-</option>
                          <option value="AD">AD</option>
                          <option value="A">A</option>
                          <option value="B">B</option>
                          <option value="C">C</option>
                        </select>
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      ) : selectedSeccion && selectedCurso ? (
        <div className="text-center py-12 text-gray-500">
          No hay estudiantes matriculados en esta sección
        </div>
      ) : (
        <div className="text-center py-12 text-gray-500">
          Seleccione nivel, grado, sección y curso para ver los estudiantes
        </div>
      )}
    </div>
  );
}
