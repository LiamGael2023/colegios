import { useState, useEffect } from 'react';
import { reportesService, pagosService, asistenciaService, academicoService } from '../services/api';
import { FileText, Download, AlertTriangle, Users } from 'lucide-react';

export default function Reportes() {
  const [anioActivo, setAnioActivo] = useState(null);
  const [activeTab, setActiveTab] = useState('morosidad');

  useEffect(() => {
    loadAnioActivo();
  }, []);

  const loadAnioActivo = async () => {
    try {
      const res = await academicoService.getAnioActivo();
      setAnioActivo(res.data);
    } catch (error) {
      console.error('Error:', error);
    }
  };

  return (
    <div>
      <h1 className="text-2xl font-bold text-gray-900 mb-6">Reportes</h1>

      <div className="bg-white rounded-lg shadow">
        <div className="border-b">
          <nav className="flex -mb-px">
            <TabButton
              active={activeTab === 'morosidad'}
              onClick={() => setActiveTab('morosidad')}
              icon={AlertTriangle}
              label="Morosidad"
            />
            <TabButton
              active={activeTab === 'ingresos'}
              onClick={() => setActiveTab('ingresos')}
              icon={FileText}
              label="Ingresos"
            />
            <TabButton
              active={activeTab === 'asistencia'}
              onClick={() => setActiveTab('asistencia')}
              icon={Users}
              label="Asistencia"
            />
          </nav>
        </div>

        <div className="p-6">
          {activeTab === 'morosidad' && anioActivo && (
            <MorosidadReport anioEscolarId={anioActivo.id} />
          )}
          {activeTab === 'ingresos' && anioActivo && (
            <IngresosReport anioEscolarId={anioActivo.id} />
          )}
          {activeTab === 'asistencia' && anioActivo && (
            <AsistenciaReport anioEscolarId={anioActivo.id} />
          )}
        </div>
      </div>
    </div>
  );
}

function TabButton({ active, onClick, icon: Icon, label }) {
  return (
    <button
      onClick={onClick}
      className={`flex items-center px-6 py-3 text-sm font-medium border-b-2 ${
        active
          ? 'border-blue-500 text-blue-600'
          : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
      }`}
    >
      <Icon className="w-4 h-4 mr-2" />
      {label}
    </button>
  );
}

function MorosidadReport({ anioEscolarId }) {
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadData();
  }, [anioEscolarId]);

  const loadData = async () => {
    try {
      const res = await pagosService.getMorosidad({ anioEscolarId });
      setData(res.data);
    } catch (error) {
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="flex justify-center py-12">
        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      </div>
    );
  }

  const totalDeuda = data.reduce((sum, d) => sum + d.totalDeuda, 0);

  return (
    <div>
      <div className="mb-4 flex justify-between items-center">
        <div>
          <h3 className="text-lg font-semibold">Reporte de Morosidad</h3>
          <p className="text-sm text-gray-600">
            {data.length} estudiantes con deudas pendientes
          </p>
        </div>
        <div className="text-right">
          <p className="text-sm text-gray-600">Total Deuda</p>
          <p className="text-2xl font-bold text-red-600">S/ {totalDeuda.toFixed(2)}</p>
        </div>
      </div>

      {data.length > 0 ? (
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead className="bg-gray-50">
              <tr>
                <th className="px-4 py-2 text-left">Estudiante</th>
                <th className="px-4 py-2 text-left">Grado</th>
                <th className="px-4 py-2 text-left">Apoderado</th>
                <th className="px-4 py-2 text-left">Teléfono</th>
                <th className="px-4 py-2 text-center">Meses</th>
                <th className="px-4 py-2 text-right">Deuda Total</th>
              </tr>
            </thead>
            <tbody className="divide-y">
              {data.map((item) => (
                <tr key={item.estudiante.id} className="hover:bg-gray-50">
                  <td className="px-4 py-2">
                    <div className="font-medium">{item.estudiante.apellidos}</div>
                    <div className="text-gray-500">{item.estudiante.nombres}</div>
                  </td>
                  <td className="px-4 py-2">
                    {item.estudiante.grado} "{item.estudiante.seccion}"
                  </td>
                  <td className="px-4 py-2">
                    {item.apoderado ? `${item.apoderado.nombres} ${item.apoderado.apellidos}` : '-'}
                  </td>
                  <td className="px-4 py-2">
                    {item.apoderado?.telefono || '-'}
                  </td>
                  <td className="px-4 py-2 text-center">
                    {item.pagosVencidos.map(p => p.mes).filter(Boolean).join(', ') || '-'}
                  </td>
                  <td className="px-4 py-2 text-right font-medium text-red-600">
                    S/ {item.totalDeuda.toFixed(2)}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      ) : (
        <div className="text-center py-12 text-gray-500">
          No hay estudiantes morosos
        </div>
      )}
    </div>
  );
}

function IngresosReport({ anioEscolarId }) {
  const [data, setData] = useState(null);
  const [mes, setMes] = useState('');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadData();
  }, [anioEscolarId, mes]);

  const loadData = async () => {
    try {
      const params = { anioEscolarId };
      if (mes) params.mes = mes;
      const res = await pagosService.getIngresos(params);
      setData(res.data);
    } catch (error) {
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  };

  const meses = [
    { value: 1, label: 'Enero' }, { value: 2, label: 'Febrero' }, { value: 3, label: 'Marzo' },
    { value: 4, label: 'Abril' }, { value: 5, label: 'Mayo' }, { value: 6, label: 'Junio' },
    { value: 7, label: 'Julio' }, { value: 8, label: 'Agosto' }, { value: 9, label: 'Septiembre' },
    { value: 10, label: 'Octubre' }, { value: 11, label: 'Noviembre' }, { value: 12, label: 'Diciembre' }
  ];

  if (loading) {
    return (
      <div className="flex justify-center py-12">
        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      </div>
    );
  }

  return (
    <div>
      <div className="mb-4 flex justify-between items-center">
        <div>
          <h3 className="text-lg font-semibold">Reporte de Ingresos</h3>
        </div>
        <select
          value={mes}
          onChange={(e) => setMes(e.target.value)}
          className="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="">Todo el año</option>
          {meses.map(m => (
            <option key={m.value} value={m.value}>{m.label}</option>
          ))}
        </select>
      </div>

      {data && (
        <>
          <div className="mb-6 p-4 bg-green-50 rounded-lg">
            <p className="text-sm text-green-600">Total Recaudado</p>
            <p className="text-3xl font-bold text-green-700">S/ {data.totalGeneral.toFixed(2)}</p>
          </div>

          <table className="w-full text-sm">
            <thead className="bg-gray-50">
              <tr>
                <th className="px-4 py-2 text-left">Concepto</th>
                <th className="px-4 py-2 text-center">Cantidad</th>
                <th className="px-4 py-2 text-right">Total</th>
              </tr>
            </thead>
            <tbody className="divide-y">
              {data.porConcepto.map((item, index) => (
                <tr key={index} className="hover:bg-gray-50">
                  <td className="px-4 py-2">{item.concepto}</td>
                  <td className="px-4 py-2 text-center">{item.cantidad}</td>
                  <td className="px-4 py-2 text-right font-medium">S/ {item.total.toFixed(2)}</td>
                </tr>
              ))}
            </tbody>
            <tfoot>
              <tr className="bg-gray-100 font-semibold">
                <td className="px-4 py-2">Total</td>
                <td className="px-4 py-2 text-center">
                  {data.porConcepto.reduce((sum, c) => sum + c.cantidad, 0)}
                </td>
                <td className="px-4 py-2 text-right">S/ {data.totalGeneral.toFixed(2)}</td>
              </tr>
            </tfoot>
          </table>
        </>
      )}
    </div>
  );
}

function AsistenciaReport({ anioEscolarId }) {
  const [niveles, setNiveles] = useState([]);
  const [secciones, setSecciones] = useState([]);
  const [selectedNivel, setSelectedNivel] = useState('');
  const [selectedGrado, setSelectedGrado] = useState('');
  const [selectedSeccion, setSelectedSeccion] = useState('');
  const [fechaInicio, setFechaInicio] = useState('');
  const [fechaFin, setFechaFin] = useState('');
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    loadNiveles();
    // Establecer fechas por defecto (mes actual)
    const now = new Date();
    const inicio = new Date(now.getFullYear(), now.getMonth(), 1);
    const fin = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    setFechaInicio(inicio.toISOString().split('T')[0]);
    setFechaFin(fin.toISOString().split('T')[0]);
  }, []);

  const loadNiveles = async () => {
    try {
      const res = await academicoService.getNiveles();
      setNiveles(res.data);
    } catch (error) {
      console.error('Error:', error);
    }
  };

  const handleGradoChange = async (gradoId) => {
    setSelectedGrado(gradoId);
    setSelectedSeccion('');
    if (gradoId) {
      try {
        const res = await academicoService.getSecciones({ gradoId });
        setSecciones(res.data);
      } catch (error) {
        console.error('Error:', error);
      }
    }
  };

  const loadReport = async () => {
    if (!selectedSeccion || !fechaInicio || !fechaFin) return;

    setLoading(true);
    try {
      const res = await asistenciaService.getReporte(selectedSeccion, {
        anioEscolarId,
        fechaInicio,
        fechaFin
      });
      setData(res.data);
    } catch (error) {
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  };

  const selectedNivelData = niveles.find(n => n.id === parseInt(selectedNivel));
  const grados = selectedNivelData?.grados || [];

  return (
    <div>
      <h3 className="text-lg font-semibold mb-4">Reporte de Asistencia</h3>

      <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-4">
        <select
          value={selectedNivel}
          onChange={(e) => {
            setSelectedNivel(e.target.value);
            setSelectedGrado('');
            setSelectedSeccion('');
          }}
          className="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="">Nivel</option>
          {niveles.map(n => (
            <option key={n.id} value={n.id}>{n.nombre}</option>
          ))}
        </select>

        <select
          value={selectedGrado}
          onChange={(e) => handleGradoChange(e.target.value)}
          disabled={!selectedNivel}
          className="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100"
        >
          <option value="">Grado</option>
          {grados.map(g => (
            <option key={g.id} value={g.id}>{g.nombre}</option>
          ))}
        </select>

        <select
          value={selectedSeccion}
          onChange={(e) => setSelectedSeccion(e.target.value)}
          disabled={!selectedGrado}
          className="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100"
        >
          <option value="">Sección</option>
          {secciones.map(s => (
            <option key={s.id} value={s.id}>{s.nombre}</option>
          ))}
        </select>

        <input
          type="date"
          value={fechaInicio}
          onChange={(e) => setFechaInicio(e.target.value)}
          className="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <input
          type="date"
          value={fechaFin}
          onChange={(e) => setFechaFin(e.target.value)}
          className="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <button
          onClick={loadReport}
          disabled={!selectedSeccion || loading}
          className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
        >
          Generar
        </button>
      </div>

      {loading ? (
        <div className="flex justify-center py-12">
          <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>
      ) : data.length > 0 ? (
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead className="bg-gray-50">
              <tr>
                <th className="px-4 py-2 text-left">Estudiante</th>
                <th className="px-4 py-2 text-center">Presentes</th>
                <th className="px-4 py-2 text-center">Ausentes</th>
                <th className="px-4 py-2 text-center">Tardanzas</th>
                <th className="px-4 py-2 text-center">Justificados</th>
                <th className="px-4 py-2 text-center">% Asistencia</th>
              </tr>
            </thead>
            <tbody className="divide-y">
              {data.map((item) => (
                <tr key={item.estudiante.id} className="hover:bg-gray-50">
                  <td className="px-4 py-2">
                    <div className="font-medium">{item.estudiante.apellidos}</div>
                    <div className="text-gray-500 text-xs">{item.estudiante.codigo}</div>
                  </td>
                  <td className="px-4 py-2 text-center text-green-600">{item.resumen.presente}</td>
                  <td className="px-4 py-2 text-center text-red-600">{item.resumen.ausente}</td>
                  <td className="px-4 py-2 text-center text-yellow-600">{item.resumen.tardanza}</td>
                  <td className="px-4 py-2 text-center text-blue-600">{item.resumen.justificado}</td>
                  <td className="px-4 py-2 text-center font-medium">
                    {item.porcentajeAsistencia}%
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      ) : selectedSeccion ? (
        <div className="text-center py-12 text-gray-500">
          No hay datos de asistencia para mostrar
        </div>
      ) : (
        <div className="text-center py-12 text-gray-500">
          Seleccione sección y rango de fechas para generar el reporte
        </div>
      )}
    </div>
  );
}
