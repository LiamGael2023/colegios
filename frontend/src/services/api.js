import axios from 'axios';

const api = axios.create({
  baseURL: '/api',
  headers: {
    'Content-Type': 'application/json'
  }
});

// Interceptor para manejar errores
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      localStorage.removeItem('usuario');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;

// Servicios específicos
export const authService = {
  login: (data) => api.post('/auth/login', data),
  register: (data) => api.post('/auth/register', data),
  getProfile: () => api.get('/auth/profile'),
  changePassword: (data) => api.put('/auth/change-password', data)
};

export const estudiantesService = {
  getAll: (params) => api.get('/estudiantes', { params }),
  getById: (id) => api.get(`/estudiantes/${id}`),
  create: (data) => api.post('/estudiantes', data),
  update: (id, data) => api.put(`/estudiantes/${id}`, data),
  matricular: (id, data) => api.post(`/estudiantes/${id}/matricular`, data)
};

export const apoderadosService = {
  getByEstudiante: (estudianteId) => api.get(`/apoderados/estudiante/${estudianteId}`),
  create: (data) => api.post('/apoderados', data),
  update: (id, data) => api.put(`/apoderados/${id}`, data),
  delete: (id) => api.delete(`/apoderados/${id}`)
};

export const academicoService = {
  getInstitucion: () => api.get('/academico/institucion'),
  getAnios: () => api.get('/academico/anios'),
  getAnioActivo: () => api.get('/academico/anios/activo'),
  getNiveles: () => api.get('/academico/niveles'),
  getGrados: (params) => api.get('/academico/grados', { params }),
  getSecciones: (params) => api.get('/academico/secciones', { params }),
  getAreas: () => api.get('/academico/areas'),
  getCursos: (params) => api.get('/academico/cursos', { params }),
  getAsignaciones: (params) => api.get('/academico/asignaciones', { params }),
  createAsignacion: (data) => api.post('/academico/asignaciones', data)
};

export const notasService = {
  getByEstudiante: (estudianteId, params) => api.get(`/notas/estudiante/${estudianteId}`, { params }),
  getBySeccionCurso: (seccionId, cursoId, params) => api.get(`/notas/seccion/${seccionId}/curso/${cursoId}`, { params }),
  registrar: (data) => api.post('/notas', data),
  getLibreta: (estudianteId, params) => api.get(`/notas/libreta/${estudianteId}`, { params })
};

export const asistenciaService = {
  getBySeccion: (seccionId, params) => api.get(`/asistencia/seccion/${seccionId}`, { params }),
  getByEstudiante: (estudianteId, params) => api.get(`/asistencia/estudiante/${estudianteId}`, { params }),
  registrar: (data) => api.post('/asistencia', data),
  justificar: (id, data) => api.put(`/asistencia/${id}/justificar`, data),
  getReporte: (seccionId, params) => api.get(`/asistencia/reporte/seccion/${seccionId}`, { params })
};

export const pagosService = {
  getAll: (params) => api.get('/pagos', { params }),
  getByEstudiante: (estudianteId, params) => api.get(`/pagos/estudiante/${estudianteId}`, { params }),
  getConceptos: () => api.get('/pagos/conceptos'),
  generarCuotas: (data) => api.post('/pagos/generar-cuotas', data),
  registrarPago: (id, data) => api.post(`/pagos/${id}/pagar`, data),
  getMorosidad: (params) => api.get('/pagos/reporte/morosidad', { params }),
  getIngresos: (params) => api.get('/pagos/reporte/ingresos', { params })
};

export const reportesService = {
  getLibretaPdf: (estudianteId, params) => api.get(`/reportes/libreta-pdf/${estudianteId}`, { params, responseType: 'blob' }),
  getReporteSeccion: (seccionId, params) => api.get(`/reportes/seccion/${seccionId}`, { params }),
  getDashboard: (params) => api.get('/reportes/dashboard', { params })
};
