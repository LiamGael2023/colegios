import { useState, useEffect } from 'react';
import { pagosService, academicoService } from '../services/api';
import { Search, DollarSign, AlertTriangle } from 'lucide-react';

export default function Pagos() {
  const [pagos, setPagos] = useState([]);
  const [conceptos, setConceptos] = useState([]);
  const [anioActivo, setAnioActivo] = useState(null);
  const [filtroEstado, setFiltroEstado] = useState('');
  const [filtroMes, setFiltroMes] = useState('');
  const [buscar, setBuscar] = useState('');
  const [loading, setLoading] = useState(true);
  const [showPagoModal, setShowPagoModal] = useState(false);
  const [selectedPago, setSelectedPago] = useState(null);

  useEffect(() => {
    loadInitialData();
  }, []);

  const loadInitialData = async () => {
    try {
      const [anioRes, conceptosRes] = await Promise.all([
        academicoService.getAnioActivo(),
        pagosService.getConceptos()
      ]);

      setAnioActivo(anioRes.data);
      setConceptos(conceptosRes.data);

      if (anioRes.data) {
        await loadPagos(anioRes.data.id);
      }
    } catch (error) {
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  };

  const loadPagos = async (anioEscolarId) => {
    try {
      const params = { anioEscolarId };
      if (filtroEstado) params.estado = filtroEstado;
      if (filtroMes) params.mes = filtroMes;

      const res = await pagosService.getAll(params);
      setPagos(res.data);
    } catch (error) {
      console.error('Error:', error);
    }
  };

  useEffect(() => {
    if (anioActivo) {
      loadPagos(anioActivo.id);
    }
  }, [filtroEstado, filtroMes]);

  const handleRegistrarPago = (pago) => {
    setSelectedPago(pago);
    setShowPagoModal(true);
  };

  const meses = [
    { value: 1, label: 'Enero' }, { value: 2, label: 'Febrero' }, { value: 3, label: 'Marzo' },
    { value: 4, label: 'Abril' }, { value: 5, label: 'Mayo' }, { value: 6, label: 'Junio' },
    { value: 7, label: 'Julio' }, { value: 8, label: 'Agosto' }, { value: 9, label: 'Septiembre' },
    { value: 10, label: 'Octubre' }, { value: 11, label: 'Noviembre' }, { value: 12, label: 'Diciembre' }
  ];

  // Filtrar por búsqueda
  const pagosFiltrados = pagos.filter(pago => {
    if (!buscar) return true;
    const estudiante = pago.estudiante;
    const texto = `${estudiante.codigo} ${estudiante.nombres} ${estudiante.apellidoPaterno} ${estudiante.apellidoMaterno}`.toLowerCase();
    return texto.includes(buscar.toLowerCase());
  });

  // Resumen
  const resumen = {
    total: pagosFiltrados.reduce((sum, p) => sum + p.monto, 0),
    pagado: pagosFiltrados.reduce((sum, p) => sum + p.montoPagado, 0),
    pendiente: pagosFiltrados.reduce((sum, p) => sum + (p.monto - p.montoPagado), 0)
  };

  return (
    <div>
      <h1 className="text-2xl font-bold text-gray-900 mb-6">Gestión de Pagos</h1>

      {/* Resumen */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div className="bg-white rounded-lg shadow p-4">
          <p className="text-sm text-gray-600">Total a Cobrar</p>
          <p className="text-2xl font-bold">S/ {resumen.total.toFixed(2)}</p>
        </div>
        <div className="bg-white rounded-lg shadow p-4">
          <p className="text-sm text-gray-600">Total Recaudado</p>
          <p className="text-2xl font-bold text-green-600">S/ {resumen.pagado.toFixed(2)}</p>
        </div>
        <div className="bg-white rounded-lg shadow p-4">
          <p className="text-sm text-gray-600">Pendiente</p>
          <p className="text-2xl font-bold text-red-600">S/ {resumen.pendiente.toFixed(2)}</p>
        </div>
      </div>

      {/* Filtros */}
      <div className="bg-white rounded-lg shadow p-4 mb-6">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div className="relative">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
            <input
              type="text"
              placeholder="Buscar estudiante..."
              value={buscar}
              onChange={(e) => setBuscar(e.target.value)}
              className="w-full pl-10 pr-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          <select
            value={filtroEstado}
            onChange={(e) => setFiltroEstado(e.target.value)}
            className="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="">Todos los estados</option>
            <option value="PENDIENTE">Pendiente</option>
            <option value="PAGADO">Pagado</option>
            <option value="PARCIAL">Parcial</option>
            <option value="VENCIDO">Vencido</option>
          </select>
          <select
            value={filtroMes}
            onChange={(e) => setFiltroMes(e.target.value)}
            className="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="">Todos los meses</option>
            {meses.map(m => (
              <option key={m.value} value={m.value}>{m.label}</option>
            ))}
          </select>
        </div>
      </div>

      {/* Tabla de pagos */}
      {loading ? (
        <div className="flex justify-center py-12">
          <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>
      ) : (
        <div className="bg-white rounded-lg shadow overflow-hidden">
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Recibo</th>
                  <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estudiante</th>
                  <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Concepto</th>
                  <th className="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Mes</th>
                  <th className="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Monto</th>
                  <th className="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Pagado</th>
                  <th className="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                  <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vence</th>
                  <th className="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acción</th>
                </tr>
              </thead>
              <tbody className="divide-y">
                {pagosFiltrados.map((pago) => (
                  <tr key={pago.id} className="hover:bg-gray-50">
                    <td className="px-4 py-3 text-sm font-medium">{pago.numeroRecibo}</td>
                    <td className="px-4 py-3 text-sm">
                      <div>{pago.estudiante.apellidoPaterno} {pago.estudiante.apellidoMaterno}</div>
                      <div className="text-xs text-gray-500">{pago.estudiante.nombres}</div>
                    </td>
                    <td className="px-4 py-3 text-sm">{pago.concepto.nombre}</td>
                    <td className="px-4 py-3 text-sm text-center">
                      {pago.mes ? meses.find(m => m.value === pago.mes)?.label : '-'}
                    </td>
                    <td className="px-4 py-3 text-sm text-right">S/ {pago.monto.toFixed(2)}</td>
                    <td className="px-4 py-3 text-sm text-right">S/ {pago.montoPagado.toFixed(2)}</td>
                    <td className="px-4 py-3 text-center">
                      <EstadoPago estado={pago.estado} />
                    </td>
                    <td className="px-4 py-3 text-sm">
                      {new Date(pago.fechaVencimiento).toLocaleDateString('es-PE')}
                    </td>
                    <td className="px-4 py-3 text-center">
                      {pago.estado !== 'PAGADO' && pago.estado !== 'ANULADO' && (
                        <button
                          onClick={() => handleRegistrarPago(pago)}
                          className="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700"
                        >
                          Pagar
                        </button>
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
            {pagosFiltrados.length === 0 && (
              <div className="text-center py-12 text-gray-500">
                No se encontraron pagos
              </div>
            )}
          </div>
        </div>
      )}

      {showPagoModal && selectedPago && (
        <PagoModal
          pago={selectedPago}
          onClose={() => {
            setShowPagoModal(false);
            setSelectedPago(null);
          }}
          onSave={() => {
            setShowPagoModal(false);
            setSelectedPago(null);
            loadPagos(anioActivo.id);
          }}
        />
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

function PagoModal({ pago, onClose, onSave }) {
  const [montoPagado, setMontoPagado] = useState((pago.monto - pago.montoPagado).toFixed(2));
  const [metodoPago, setMetodoPago] = useState('EFECTIVO');
  const [observacion, setObservacion] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);

    try {
      await pagosService.registrarPago(pago.id, {
        montoPagado: parseFloat(montoPagado),
        metodoPago,
        observacion
      });
      onSave();
    } catch (error) {
      console.error('Error:', error);
      alert('Error al registrar pago');
    } finally {
      setLoading(false);
    }
  };

  const pendiente = pago.monto - pago.montoPagado;

  return (
    <div className="fixed inset-0 z-50 overflow-y-auto">
      <div className="flex items-center justify-center min-h-screen px-4">
        <div className="fixed inset-0 bg-gray-500 bg-opacity-75" onClick={onClose}></div>
        <div className="relative bg-white rounded-lg max-w-md w-full p-6">
          <h3 className="text-lg font-semibold mb-4">Registrar Pago</h3>

          <div className="mb-4 p-3 bg-gray-50 rounded-lg text-sm">
            <p><strong>Recibo:</strong> {pago.numeroRecibo}</p>
            <p><strong>Estudiante:</strong> {pago.estudiante.apellidoPaterno} {pago.estudiante.nombres}</p>
            <p><strong>Concepto:</strong> {pago.concepto.nombre}</p>
            <p><strong>Monto Total:</strong> S/ {pago.monto.toFixed(2)}</p>
            <p><strong>Pagado:</strong> S/ {pago.montoPagado.toFixed(2)}</p>
            <p className="text-red-600"><strong>Pendiente:</strong> S/ {pendiente.toFixed(2)}</p>
          </div>

          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700">Monto a Pagar *</label>
              <input
                type="number"
                step="0.01"
                min="0.01"
                max={pendiente}
                required
                value={montoPagado}
                onChange={(e) => setMontoPagado(e.target.value)}
                className="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700">Método de Pago *</label>
              <select
                value={metodoPago}
                onChange={(e) => setMetodoPago(e.target.value)}
                className="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="EFECTIVO">Efectivo</option>
                <option value="TRANSFERENCIA">Transferencia</option>
                <option value="TARJETA">Tarjeta</option>
                <option value="YAPE">Yape</option>
                <option value="PLIN">Plin</option>
              </select>
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700">Observación</label>
              <textarea
                value={observacion}
                onChange={(e) => setObservacion(e.target.value)}
                rows={2}
                className="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <div className="flex justify-end gap-3 mt-6">
              <button
                type="button"
                onClick={onClose}
                className="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
              >
                Cancelar
              </button>
              <button
                type="submit"
                disabled={loading}
                className="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50"
              >
                {loading ? 'Procesando...' : 'Registrar Pago'}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}
