import { useState, useEffect } from 'react';
import { useAuth } from '../context/AuthContext';
import { reportesService, academicoService } from '../services/api';
import { Users, UserCheck, CreditCard, DollarSign } from 'lucide-react';

export default function Dashboard() {
  const { usuario } = useAuth();
  const [stats, setStats] = useState(null);
  const [anioActivo, setAnioActivo] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    try {
      const anioRes = await academicoService.getAnioActivo();
      setAnioActivo(anioRes.data);

      if (anioRes.data) {
        const dashRes = await reportesService.getDashboard({ anioEscolarId: anioRes.data.id });
        setStats(dashRes.data);
      }
    } catch (error) {
      console.error('Error cargando dashboard:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center h-64">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>
    );
  }

  return (
    <div>
      <div className="mb-8">
        <h1 className="text-2xl font-bold text-gray-900">
          Bienvenido, {usuario?.nombre}
        </h1>
        <p className="text-gray-600">
          {anioActivo ? `Año Escolar ${anioActivo.anio}` : 'No hay año escolar activo'}
        </p>
      </div>

      {stats && (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <StatCard
            icon={Users}
            label="Total Estudiantes"
            value={stats.totalEstudiantes}
            color="blue"
          />
          <StatCard
            icon={UserCheck}
            label="Matriculados"
            value={stats.totalMatriculas}
            color="green"
          />
          <StatCard
            icon={CreditCard}
            label="Pagos Pendientes"
            value={stats.pagosPendientes}
            color="yellow"
          />
          <StatCard
            icon={DollarSign}
            label="Ingresos Totales"
            value={`S/ ${stats.ingresosTotales?.toFixed(2) || '0.00'}`}
            color="purple"
          />
        </div>
      )}

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div className="bg-white rounded-lg shadow p-6">
          <h3 className="text-lg font-semibold mb-4">Accesos Rápidos</h3>
          <div className="grid grid-cols-2 gap-4">
            <QuickLink href="/estudiantes" label="Registrar Estudiante" />
            <QuickLink href="/notas" label="Ingresar Notas" />
            <QuickLink href="/asistencia" label="Tomar Asistencia" />
            <QuickLink href="/pagos" label="Registrar Pago" />
          </div>
        </div>

        <div className="bg-white rounded-lg shadow p-6">
          <h3 className="text-lg font-semibold mb-4">Información del Sistema</h3>
          <div className="space-y-3 text-sm">
            <div className="flex justify-between">
              <span className="text-gray-600">Usuario:</span>
              <span className="font-medium">{usuario?.email}</span>
            </div>
            <div className="flex justify-between">
              <span className="text-gray-600">Rol:</span>
              <span className="font-medium">{usuario?.rol}</span>
            </div>
            <div className="flex justify-between">
              <span className="text-gray-600">Año Activo:</span>
              <span className="font-medium">{anioActivo?.anio || 'N/A'}</span>
            </div>
            <div className="flex justify-between">
              <span className="text-gray-600">Profesores:</span>
              <span className="font-medium">{stats?.totalProfesores || 0}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

function StatCard({ icon: Icon, label, value, color }) {
  const colors = {
    blue: 'bg-blue-100 text-blue-600',
    green: 'bg-green-100 text-green-600',
    yellow: 'bg-yellow-100 text-yellow-600',
    purple: 'bg-purple-100 text-purple-600'
  };

  return (
    <div className="bg-white rounded-lg shadow p-6">
      <div className="flex items-center">
        <div className={`p-3 rounded-full ${colors[color]}`}>
          <Icon className="w-6 h-6" />
        </div>
        <div className="ml-4">
          <p className="text-sm text-gray-600">{label}</p>
          <p className="text-2xl font-bold text-gray-900">{value}</p>
        </div>
      </div>
    </div>
  );
}

function QuickLink({ href, label }) {
  return (
    <a
      href={href}
      className="block p-4 text-center bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
    >
      <span className="text-sm font-medium text-gray-700">{label}</span>
    </a>
  );
}
