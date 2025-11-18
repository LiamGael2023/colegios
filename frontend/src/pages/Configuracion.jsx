import { useState, useEffect } from 'react';
import { useAuth } from '../context/AuthContext';
import { academicoService, authService } from '../services/api';
import { School, Calendar, Lock } from 'lucide-react';

export default function Configuracion() {
  const { usuario } = useAuth();
  const [activeTab, setActiveTab] = useState('institucion');

  return (
    <div>
      <h1 className="text-2xl font-bold text-gray-900 mb-6">Configuración</h1>

      <div className="bg-white rounded-lg shadow">
        <div className="border-b">
          <nav className="flex -mb-px">
            {(usuario?.rol === 'ADMIN' || usuario?.rol === 'DIRECTOR') && (
              <>
                <TabButton
                  active={activeTab === 'institucion'}
                  onClick={() => setActiveTab('institucion')}
                  icon={School}
                  label="Institución"
                />
                <TabButton
                  active={activeTab === 'anios'}
                  onClick={() => setActiveTab('anios')}
                  icon={Calendar}
                  label="Años Escolares"
                />
              </>
            )}
            <TabButton
              active={activeTab === 'password'}
              onClick={() => setActiveTab('password')}
              icon={Lock}
              label="Cambiar Contraseña"
            />
          </nav>
        </div>

        <div className="p-6">
          {activeTab === 'institucion' && <InstitucionTab />}
          {activeTab === 'anios' && <AniosTab />}
          {activeTab === 'password' && <PasswordTab />}
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

function InstitucionTab() {
  const [institucion, setInstitucion] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadInstitucion();
  }, []);

  const loadInstitucion = async () => {
    try {
      const res = await academicoService.getInstitucion();
      setInstitucion(res.data);
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

  if (!institucion) {
    return (
      <div className="text-center py-12 text-gray-500">
        No hay institución configurada
      </div>
    );
  }

  return (
    <div>
      <h3 className="text-lg font-semibold mb-4">Datos de la Institución</h3>
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div className="space-y-3">
          <InfoItem label="Nombre" value={institucion.nombre} />
          <InfoItem label="Código Modular" value={institucion.codigoModular} />
          <InfoItem label="Director" value={institucion.director || '-'} />
          <InfoItem label="UGEL" value={institucion.ugel || '-'} />
        </div>
        <div className="space-y-3">
          <InfoItem label="Dirección" value={institucion.direccion} />
          <InfoItem label="Teléfono" value={institucion.telefono || '-'} />
          <InfoItem label="Email" value={institucion.email || '-'} />
          <InfoItem label="Región" value={institucion.region || '-'} />
        </div>
      </div>
    </div>
  );
}

function AniosTab() {
  const [anios, setAnios] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadAnios();
  }, []);

  const loadAnios = async () => {
    try {
      const res = await academicoService.getAnios();
      setAnios(res.data);
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

  return (
    <div>
      <h3 className="text-lg font-semibold mb-4">Años Escolares</h3>

      {anios.length > 0 ? (
        <div className="space-y-4">
          {anios.map((anio) => (
            <div key={anio.id} className="border rounded-lg p-4">
              <div className="flex justify-between items-start mb-3">
                <div>
                  <h4 className="font-semibold text-lg">Año {anio.anio}</h4>
                  <p className="text-sm text-gray-600">
                    {new Date(anio.fechaInicio).toLocaleDateString('es-PE')} - {new Date(anio.fechaFin).toLocaleDateString('es-PE')}
                  </p>
                </div>
                {anio.activo && (
                  <span className="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">
                    Activo
                  </span>
                )}
              </div>

              <div>
                <p className="text-sm font-medium mb-2">Períodos:</p>
                <div className="grid grid-cols-2 md:grid-cols-4 gap-2">
                  {anio.periodos.map((periodo) => (
                    <div key={periodo.id} className="text-xs bg-gray-50 p-2 rounded">
                      <p className="font-medium">{periodo.nombre}</p>
                      <p className="text-gray-500">
                        {new Date(periodo.fechaInicio).toLocaleDateString('es-PE', { day: '2-digit', month: 'short' })} - {new Date(periodo.fechaFin).toLocaleDateString('es-PE', { day: '2-digit', month: 'short' })}
                      </p>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <div className="text-center py-12 text-gray-500">
          No hay años escolares configurados
        </div>
      )}
    </div>
  );
}

function PasswordTab() {
  const [formData, setFormData] = useState({
    currentPassword: '',
    newPassword: '',
    confirmPassword: ''
  });
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState({ type: '', text: '' });

  const handleSubmit = async (e) => {
    e.preventDefault();
    setMessage({ type: '', text: '' });

    if (formData.newPassword !== formData.confirmPassword) {
      setMessage({ type: 'error', text: 'Las contraseñas no coinciden' });
      return;
    }

    if (formData.newPassword.length < 6) {
      setMessage({ type: 'error', text: 'La contraseña debe tener al menos 6 caracteres' });
      return;
    }

    setLoading(true);

    try {
      await authService.changePassword({
        currentPassword: formData.currentPassword,
        newPassword: formData.newPassword
      });

      setMessage({ type: 'success', text: 'Contraseña actualizada correctamente' });
      setFormData({ currentPassword: '', newPassword: '', confirmPassword: '' });
    } catch (error) {
      setMessage({
        type: 'error',
        text: error.response?.data?.error || 'Error al cambiar contraseña'
      });
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="max-w-md">
      <h3 className="text-lg font-semibold mb-4">Cambiar Contraseña</h3>

      {message.text && (
        <div className={`mb-4 p-3 rounded-md text-sm ${
          message.type === 'error' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600'
        }`}>
          {message.text}
        </div>
      )}

      <form onSubmit={handleSubmit} className="space-y-4">
        <div>
          <label className="block text-sm font-medium text-gray-700">Contraseña Actual</label>
          <input
            type="password"
            required
            value={formData.currentPassword}
            onChange={(e) => setFormData({ ...formData, currentPassword: e.target.value })}
            className="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
          <input
            type="password"
            required
            value={formData.newPassword}
            onChange={(e) => setFormData({ ...formData, newPassword: e.target.value })}
            className="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
          <input
            type="password"
            required
            value={formData.confirmPassword}
            onChange={(e) => setFormData({ ...formData, confirmPassword: e.target.value })}
            className="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <button
          type="submit"
          disabled={loading}
          className="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
        >
          {loading ? 'Actualizando...' : 'Cambiar Contraseña'}
        </button>
      </form>
    </div>
  );
}

function InfoItem({ label, value }) {
  return (
    <div>
      <dt className="text-sm text-gray-500">{label}</dt>
      <dd className="font-medium">{value}</dd>
    </div>
  );
}
