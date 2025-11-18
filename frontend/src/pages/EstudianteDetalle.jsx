import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { estudiantesService, apoderadosService, pagosService, notasService, academicoService } from '../services/api';
import { ArrowLeft, User, Users, BookOpen, CreditCard, Plus } from 'lucide-react';

export default function EstudianteDetalle() {
  const { id } = useParams();
  const [estudiante, setEstudiante] = useState(null);
  const [pagos, setPagos] = useState([]);
  const [anioActivo, setAnioActivo] = useState(null);
  const [activeTab, setActiveTab] = useState('info');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadData();
  }, [id]);

  const loadData = async () => {
    try {
      const [estRes, anioRes] = await Promise.all([
        estudiantesService.getById(id),
        academicoService.getAnioActivo()
      ]);

      setEstudiante(estRes.data);
      setAnioActivo(anioRes.data);

      if (anioRes.data) {
        const pagosRes = await pagosService.getByEstudiante(id, { anioEscolarId: anioRes.data.id });
        setPagos(pagosRes.data);
      }
    } catch (error) {
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="flex justify-center py-12">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>
    );
  }

  if (!estudiante) {
    return <div className="text-center py-12">Estudiante no encontrado</div>;
  }

  const matriculaActual = estudiante.matriculas?.find(m => m.anioEscolarId === anioActivo?.id);

  return (
    <div>
      <div className="mb-6">
        <Link to="/estudiantes" className="flex items-center text-blue-600 hover:text-blue-800 mb-4">
          <ArrowLeft className="w-4 h-4 mr-2" />
          Volver a Estudiantes
        </Link>

        <div className="bg-white rounded-lg shadow p-6">
          <div className="flex items-start justify-between">
            <div>
              <h1 className="text-2xl font-bold text-gray-900">
                {estudiante.apellidoPaterno} {estudiante.apellidoMaterno}, {estudiante.nombres}
              </h1>
              <p className="text-gray-600">Código: {estudiante.codigo}</p>
              {matriculaActual && (
                <p className="text-sm text-blue-600 mt-1">
                  {matriculaActual.seccion.grado.nivel.nombre} - {matriculaActual.seccion.grado.nombre} "{matriculaActual.seccion.nombre}"
                </p>
              )}
            </div>
            <span className={`px-3 py-1 rounded-full text-sm ${estudiante.activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>
              {estudiante.activo ? 'Activo' : 'Inactivo'}
            </span>
          </div>
        </div>
      </div>

      {/* Tabs */}
      <div className="bg-white rounded-lg shadow">
        <div className="border-b">
          <nav className="flex -mb-px">
            <TabButton
              active={activeTab === 'info'}
              onClick={() => setActiveTab('info')}
              icon={User}
              label="Información"
            />
            <TabButton
              active={activeTab === 'apoderados'}
              onClick={() => setActiveTab('apoderados')}
              icon={Users}
              label="Apoderados"
            />
            <TabButton
              active={activeTab === 'pagos'}
              onClick={() => setActiveTab('pagos')}
              icon={CreditCard}
              label="Pagos"
            />
          </nav>
        </div>

        <div className="p-6">
          {activeTab === 'info' && <InfoTab estudiante={estudiante} />}
          {activeTab === 'apoderados' && <ApoderadosTab estudiante={estudiante} onUpdate={loadData} />}
          {activeTab === 'pagos' && <PagosTab pagos={pagos} onUpdate={loadData} estudianteId={id} anioActivo={anioActivo} />}
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

function InfoTab({ estudiante }) {
  return (
    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h3 className="font-semibold mb-3">Datos Personales</h3>
        <dl className="space-y-2 text-sm">
          <InfoItem label="DNI" value={estudiante.dni || '-'} />
          <InfoItem label="Fecha Nacimiento" value={new Date(estudiante.fechaNacimiento).toLocaleDateString('es-PE')} />
          <InfoItem label="Género" value={estudiante.genero} />
          <InfoItem label="Nacionalidad" value={estudiante.nacionalidad} />
          <InfoItem label="Lengua" value={estudiante.lengua} />
        </dl>
      </div>
      <div>
        <h3 className="font-semibold mb-3">Contacto</h3>
        <dl className="space-y-2 text-sm">
          <InfoItem label="Dirección" value={estudiante.direccion || '-'} />
          <InfoItem label="Teléfono" value={estudiante.telefono || '-'} />
          <InfoItem label="Email" value={estudiante.email || '-'} />
        </dl>
      </div>
      <div>
        <h3 className="font-semibold mb-3">Salud</h3>
        <dl className="space-y-2 text-sm">
          <InfoItem label="Tipo Sangre" value={estudiante.tipoSangre || '-'} />
          <InfoItem label="Alergias" value={estudiante.alergias || '-'} />
          <InfoItem label="Discapacidad" value={estudiante.discapacidad || '-'} />
        </dl>
      </div>
      <div>
        <h3 className="font-semibold mb-3">Otros</h3>
        <dl className="space-y-2 text-sm">
          <InfoItem label="Religión" value={estudiante.religion || '-'} />
          <InfoItem label="Lugar Nacimiento" value={estudiante.lugarNacimiento || '-'} />
          <InfoItem label="Observaciones" value={estudiante.observaciones || '-'} />
        </dl>
      </div>
    </div>
  );
}

function InfoItem({ label, value }) {
  return (
    <div className="flex justify-between">
      <dt className="text-gray-500">{label}:</dt>
      <dd className="font-medium">{value}</dd>
    </div>
  );
}

function ApoderadosTab({ estudiante, onUpdate }) {
  return (
    <div>
      <div className="flex justify-between items-center mb-4">
        <h3 className="font-semibold">Apoderados Registrados</h3>
        <button className="flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
          <Plus className="w-4 h-4 mr-1" />
          Agregar
        </button>
      </div>

      {estudiante.apoderados?.length > 0 ? (
        <div className="space-y-4">
          {estudiante.apoderados.map((apoderado) => (
            <div key={apoderado.id} className="border rounded-lg p-4">
              <div className="flex justify-between items-start">
                <div>
                  <p className="font-medium">
                    {apoderado.nombres} {apoderado.apellidos}
                    {apoderado.esPrincipal && (
                      <span className="ml-2 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded">Principal</span>
                    )}
                  </p>
                  <p className="text-sm text-gray-600">{apoderado.parentesco}</p>
                </div>
              </div>
              <div className="mt-3 grid grid-cols-2 gap-4 text-sm">
                <div>
                  <span className="text-gray-500">DNI:</span> {apoderado.dni}
                </div>
                <div>
                  <span className="text-gray-500">Teléfono:</span> {apoderado.telefono}
                </div>
                <div>
                  <span className="text-gray-500">Email:</span> {apoderado.email || '-'}
                </div>
                <div>
                  <span className="text-gray-500">Ocupación:</span> {apoderado.ocupacion || '-'}
                </div>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <p className="text-center py-8 text-gray-500">No hay apoderados registrados</p>
      )}
    </div>
  );
}

function PagosTab({ pagos, estudianteId, anioActivo }) {
  const totalPendiente = pagos
    .filter(p => p.estado !== 'PAGADO' && p.estado !== 'ANULADO')
    .reduce((sum, p) => sum + (p.monto - p.montoPagado), 0);

  return (
    <div>
      <div className="flex justify-between items-center mb-4">
        <div>
          <h3 className="font-semibold">Estado de Pagos</h3>
          {totalPendiente > 0 && (
            <p className="text-sm text-red-600">Deuda pendiente: S/ {totalPendiente.toFixed(2)}</p>
          )}
        </div>
      </div>

      {pagos.length > 0 ? (
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead className="bg-gray-50">
              <tr>
                <th className="px-4 py-2 text-left">Concepto</th>
                <th className="px-4 py-2 text-left">Mes</th>
                <th className="px-4 py-2 text-right">Monto</th>
                <th className="px-4 py-2 text-right">Pagado</th>
                <th className="px-4 py-2 text-center">Estado</th>
                <th className="px-4 py-2 text-left">Vencimiento</th>
              </tr>
            </thead>
            <tbody className="divide-y">
              {pagos.map((pago) => (
                <tr key={pago.id}>
                  <td className="px-4 py-2">{pago.concepto.nombre}</td>
                  <td className="px-4 py-2">{pago.mes || '-'}</td>
                  <td className="px-4 py-2 text-right">S/ {pago.monto.toFixed(2)}</td>
                  <td className="px-4 py-2 text-right">S/ {pago.montoPagado.toFixed(2)}</td>
                  <td className="px-4 py-2 text-center">
                    <EstadoPago estado={pago.estado} />
                  </td>
                  <td className="px-4 py-2">{new Date(pago.fechaVencimiento).toLocaleDateString('es-PE')}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      ) : (
        <p className="text-center py-8 text-gray-500">No hay pagos registrados</p>
      )}
    </div>
  );
}

function EstadoPago({ estado }) {
  const estilos = {
    PENDIENTE: 'bg-yellow-100 text-yellow-800',
    PAGADO: 'bg-green-100 text-green-800',
    PARCIAL: 'bg-blue-100 text-blue-800',
    VENCIDO: 'bg-red-100 text-red-800',
    ANULADO: 'bg-gray-100 text-gray-800'
  };

  return (
    <span className={`px-2 py-0.5 rounded text-xs ${estilos[estado]}`}>
      {estado}
    </span>
  );
}
