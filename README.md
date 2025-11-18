# Sistema de Gestión Escolar - Perú

Sistema completo para la gestión de colegios en Perú, incluyendo los niveles de Inicial, Primaria y Secundaria.

## Características

### Módulos Incluidos

1. **Autenticación y Usuarios**
   - Roles: Admin, Director, Profesor, Secretaria
   - Control de acceso por roles

2. **Gestión de Estudiantes**
   - Registro completo de datos personales
   - Gestión de apoderados
   - Historial de matrículas

3. **Módulo Académico**
   - Niveles: Inicial, Primaria, Secundaria
   - Grados y secciones
   - Áreas curriculares y cursos
   - Asignación de profesores

4. **Registro de Notas**
   - Sistema literal (AD, A, B, C) para Inicial y Primaria
   - Sistema vigesimal (0-20) para Secundaria
   - Notas por bimestre/trimestre
   - Generación de libretas de notas en PDF

5. **Control de Asistencia**
   - Registro diario
   - Estados: Presente, Ausente, Tardanza, Justificado
   - Reportes por sección

6. **Gestión de Pagos**
   - Conceptos de pago configurables
   - Cuotas mensuales
   - Métodos de pago: Efectivo, Transferencia, Yape, Plin
   - Reportes de morosidad e ingresos

## Tecnologías

### Backend
- Node.js + Express
- Prisma ORM
- SQLite (fácil de instalar, puede migrarse a PostgreSQL)
- JWT para autenticación

### Frontend
- React 18 + Vite
- Tailwind CSS
- React Router
- Axios

## Instalación

### Requisitos
- Node.js 18+
- npm o yarn

### Backend

```bash
cd backend

# Instalar dependencias
npm install

# Configurar base de datos
npx prisma generate
npx prisma db push

# Cargar datos iniciales
npm run db:seed

# Iniciar servidor
npm run dev
```

### Frontend

```bash
cd frontend

# Instalar dependencias
npm install

# Iniciar servidor de desarrollo
npm run dev
```

## Usuarios por Defecto

Después de ejecutar el seed, puedes acceder con:

| Email | Contraseña | Rol |
|-------|------------|-----|
| admin@colegio.edu.pe | admin123 | ADMIN |
| director@colegio.edu.pe | admin123 | DIRECTOR |
| secretaria@colegio.edu.pe | admin123 | SECRETARIA |
| profesor@colegio.edu.pe | admin123 | PROFESOR |

## API Endpoints

### Autenticación
- `POST /api/auth/login` - Iniciar sesión
- `POST /api/auth/register` - Registrar usuario (Admin/Director)
- `GET /api/auth/profile` - Obtener perfil
- `PUT /api/auth/change-password` - Cambiar contraseña

### Estudiantes
- `GET /api/estudiantes` - Listar estudiantes
- `GET /api/estudiantes/:id` - Obtener estudiante
- `POST /api/estudiantes` - Crear estudiante
- `PUT /api/estudiantes/:id` - Actualizar estudiante
- `POST /api/estudiantes/:id/matricular` - Matricular estudiante

### Académico
- `GET /api/academico/niveles` - Listar niveles
- `GET /api/academico/grados` - Listar grados
- `GET /api/academico/secciones` - Listar secciones
- `GET /api/academico/cursos` - Listar cursos
- `GET /api/academico/anios/activo` - Obtener año activo

### Notas
- `GET /api/notas/estudiante/:id` - Notas por estudiante
- `GET /api/notas/seccion/:seccionId/curso/:cursoId` - Notas por sección y curso
- `POST /api/notas` - Registrar notas
- `GET /api/notas/libreta/:estudianteId` - Generar libreta

### Asistencia
- `GET /api/asistencia/seccion/:id` - Asistencia por sección
- `POST /api/asistencia` - Registrar asistencia
- `PUT /api/asistencia/:id/justificar` - Justificar inasistencia

### Pagos
- `GET /api/pagos` - Listar pagos
- `GET /api/pagos/estudiante/:id` - Pagos por estudiante
- `POST /api/pagos/generar-cuotas` - Generar cuotas mensuales
- `POST /api/pagos/:id/pagar` - Registrar pago
- `GET /api/pagos/reporte/morosidad` - Reporte de morosidad

## Estructura del Proyecto

```
colegios/
├── backend/
│   ├── prisma/
│   │   ├── schema.prisma    # Esquema de base de datos
│   │   └── seed.js          # Datos iniciales
│   └── src/
│       ├── index.js         # Servidor Express
│       ├── controllers/     # Controladores
│       ├── middlewares/     # Middlewares
│       ├── routes/          # Rutas API
│       ├── services/        # Servicios
│       └── utils/           # Utilidades
├── frontend/
│   └── src/
│       ├── components/      # Componentes React
│       ├── context/         # Contextos (Auth)
│       ├── pages/           # Páginas
│       ├── services/        # Servicios API
│       └── hooks/           # Custom hooks
└── README.md
```

## Despliegue

### Variables de Entorno (Backend)

```env
DATABASE_URL="file:./prod.db"
JWT_SECRET="tu_clave_secreta_muy_segura"
PORT=3001
```

### Build Frontend

```bash
cd frontend
npm run build
```

## Sistema de Calificaciones Peruano

- **Inicial y Primaria**: Sistema literal
  - AD: Logro destacado
  - A: Logro esperado
  - B: En proceso
  - C: En inicio

- **Secundaria**: Sistema vigesimal (0-20)
  - 18-20: Excelente
  - 14-17: Bueno
  - 11-13: Regular
  - 0-10: Desaprobado

## Licencia

MIT
