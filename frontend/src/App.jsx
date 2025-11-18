import { Routes, Route, Navigate } from 'react-router-dom';
import { useAuth } from './context/AuthContext';
import Layout from './components/Layout';
import Login from './pages/Login';
import Dashboard from './pages/Dashboard';
import Estudiantes from './pages/Estudiantes';
import EstudianteDetalle from './pages/EstudianteDetalle';
import Notas from './pages/Notas';
import Asistencia from './pages/Asistencia';
import Pagos from './pages/Pagos';
import Reportes from './pages/Reportes';
import Configuracion from './pages/Configuracion';

function PrivateRoute({ children }) {
  const { isAuthenticated, loading } = useAuth();

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>
    );
  }

  return isAuthenticated ? children : <Navigate to="/login" />;
}

function App() {
  return (
    <Routes>
      <Route path="/login" element={<Login />} />
      <Route
        path="/*"
        element={
          <PrivateRoute>
            <Layout>
              <Routes>
                <Route path="/" element={<Dashboard />} />
                <Route path="/estudiantes" element={<Estudiantes />} />
                <Route path="/estudiantes/:id" element={<EstudianteDetalle />} />
                <Route path="/notas" element={<Notas />} />
                <Route path="/asistencia" element={<Asistencia />} />
                <Route path="/pagos" element={<Pagos />} />
                <Route path="/reportes" element={<Reportes />} />
                <Route path="/configuracion" element={<Configuracion />} />
              </Routes>
            </Layout>
          </PrivateRoute>
        }
      />
    </Routes>
  );
}

export default App;
