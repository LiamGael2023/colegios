const express = require('express');
const cors = require('cors');
const dotenv = require('dotenv');

dotenv.config();

const app = express();

// Middlewares
app.use(cors());
app.use(express.json());

// Rutas
app.use('/api/auth', require('./routes/auth.routes'));
app.use('/api/usuarios', require('./routes/usuarios.routes'));
app.use('/api/estudiantes', require('./routes/estudiantes.routes'));
app.use('/api/apoderados', require('./routes/apoderados.routes'));
app.use('/api/academico', require('./routes/academico.routes'));
app.use('/api/notas', require('./routes/notas.routes'));
app.use('/api/asistencia', require('./routes/asistencia.routes'));
app.use('/api/pagos', require('./routes/pagos.routes'));
app.use('/api/reportes', require('./routes/reportes.routes'));

// Ruta de salud
app.get('/api/health', (req, res) => {
  res.json({ status: 'OK', message: 'Sistema Escolar API funcionando' });
});

// Manejo de errores
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({ error: 'Error interno del servidor' });
});

const PORT = process.env.PORT || 3001;
app.listen(PORT, () => {
  console.log(`Servidor corriendo en puerto ${PORT}`);
});
