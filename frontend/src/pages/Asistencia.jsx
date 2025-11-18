import { useState, useEffect } from 'react';
import { academicoService, asistenciaService } from '../services/api';
import { Save, Calendar } from 'lucide-react';

export default function Asistencia() {
  const [niveles, setNiveles] = useState([]);
  const [anioActivo, setAnioActivo] = useState(null);
  const [secciones, setSecciones] = useState([]);
  const [selectedNivel, setSelectedNivel] = useState('');
  const [selectedGrado, setSelectedGrado] = useState('');
  const [selectedSeccion, setSelectedSeccion] = useState('');
  const [fecha, setFecha] = useState(new Date().toISOString().split('T')[0]);
  const [estudiantes, setEstudiantes] = useState([]);
  const [asistencias, setAsistencias] = useState({});
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
    } catch (error) {
      console.error('Error:', error);
    }
  };

  const handleNivelChange = (nivelId) => {
    setSelectedNivel(nivelId);
    setSelectedGrado('');
    setSelectedSeccion('');
    setEstudiantes([]);
  };

  const handleGradoChange = async (gradoId) => {
    setSelectedGrado(gradoId);
    setSelectedSeccion('');
    setEstudiantes([]);

    if (gradoId) {
      try {
        const res = await academicoService.getSecciones({ gradoId });
        setSecciones(res.data);
      } catch (error) {
        console.error('Error:', error);
      }
    }
  };

  const loadAsistencia = async () => {
    if (!selectedSeccion || !fecha) return;

    setLoading(true);
    try {
      const res = await asistenciaService.getBySeccion(selectedSeccion, { fecha });
      setEstudiantes(res.data);

      // Inicializar asistencias
      const asistenciasMap = {};
      res.data.forEach(m => {
        const asist = m.estudiante.asistencias?.[0];
        asistenciasMap[m.estudiante.id] = asist?.estado || 'PRESENTE';
      });
      setAsistencias(asistenciasMap);
    } catch (error) {
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (selectedSeccion && fecha) {
      loadAsistencia();
    }
  }, [selectedSeccion, fecha]);

  const handleAsistenciaChange = (estudianteId, estado) => {
    setAsistencias({ ...asistencias, [estudianteId]: estado });
  };

  const handleSave = async () => {
    setSaving(true);
    try {
      const asistenciasArray = Object.entries(asistencias).map(([estudianteId, estado]) => ({
        estudianteId: parseInt(estudianteId),
        estado
      }));

      await asistenciaService.registrar({
        fecha,
        anioEscolarId: anioActivo.id,
        asistencias: asistenciasArray
      });

      alert('Asistencia guardada correctamente');
    } catch (error) {
      console.error('Error:', error);
      alert('Error al guardar asistencia');
    } finally {
      setSaving(false);
    }
  };

  const marcarTodos = (estado) => {
    const nuevasAsistencias = {};
    estudiantes.forEach(m => {
      nuevasAsistencias[m.estudiante.id] = estado;
    });
    setAsistencias(nuevasAsistencias);
  };

  const selectedNivelData = niveles.find(n => n.id === parseInt(selectedNivel));
  const grados = selectedNivelData?.grados || [];

  // Contadores
  const contadores = {
    PRESENTE: Object.values(asistencias).filter(a => a === 'PRESENTE').length,
    AUSENTE: Object.values(asistencias).filter(a => a === 'AUSENTE').length,
    TARDANZA: Object.values(asistencias).filter(a => a === 'TARDANZA').length,
    JUSTIFICADO: Object.values(asistencias).filter(a => a === 'JUSTIFICADO').length
  };

  return (
    <div>
      <h1 className="text-2xl font-bold text-gray-900 mb-6">Control de Asistencia</h1>

      <div className="bg-white rounded-lg shadow p-6 mb-6">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
            <input
              type="date"
              value={fecha}
              onChange={(e) => setFecha(e.target.value)}
              className="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

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
        </div>
      </div>

      {loading ? (
        <div className="flex justify-center py-12">
          <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>
      ) : estudiantes.length > 0 ? (
        <div className="bg-white rounded-lg shadow">
          <div className="p-4 border-b">
            <div className="flex flex-wrap justify-between items-center gap-4">
              <div className="flex gap-4 text-sm">
                <span className="text-green-600">Presentes: {contadores.PRESENTE}</span>
                <span className="text-red-600">Ausentes: {contadores.AUSENTE}</span>
                <span className="text-yellow-600">Tardanzas: {contadores.TARDANZA}</span>
                <span className="text-blue-600">Justificados: {contadores.JUSTIFICADO}</span>
              </div>
              <div className="flex gap-2">
                <button
                  onClick={() => marcarTodos('PRESENTE')}
                  className="px-3 py-1 text-sm bg-green-100 text-green-700 rounded hover:bg-green-200"
                >
                  Todos Presentes
                </button>
                <button
                  onClick={handleSave}
                  disabled={saving}
                  className="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
                >
                  <Save className="w-4 h-4 mr-2" />
                  {saving ? 'Guardando...' : 'Guardar'}
                </button>
              </div>
            </div>
          </div>

          <div className="overflow-x-auto">
            <table className="w-full">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N°</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estudiante</th>
                  <th className="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
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
                    <td className="px-6 py-4">
                      <div className="flex justify-center gap-2">
                        {['PRESENTE', 'AUSENTE', 'TARDANZA', 'JUSTIFICADO'].map(estado => (
                          <button
                            key={estado}
                            onClick={() => handleAsistenciaChange(m.estudiante.id, estado)}
                            className={`px-3 py-1 text-xs rounded ${
                              asistencias[m.estudiante.id] === estado
                                ? estado === 'PRESENTE' ? 'bg-green-600 text-white'
                                : estado === 'AUSENTE' ? 'bg-red-600 text-white'
                                : estado === 'TARDANZA' ? 'bg-yellow-500 text-white'
                                : 'bg-blue-600 text-white'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                            }`}
                          >
                            {estado.charAt(0)}
                          </button>
                        ))}
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      ) : selectedSeccion ? (
        <div className="text-center py-12 text-gray-500">
          No hay estudiantes matriculados en esta sección
        </div>
      ) : (
        <div className="text-center py-12 text-gray-500">
          Seleccione fecha, nivel, grado y sección para tomar asistencia
        </div>
      )}
    </div>
  );
}
