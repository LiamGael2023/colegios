# Sistema de Gestión Escolar - PHP MVC

Sistema completo para la gestión de colegios en Perú, desarrollado en PHP con arquitectura MVC y MySQL.

## Características

### Módulos Incluidos

1. **Autenticación y Usuarios**
   - Roles: Admin, Director, Profesor, Secretaria
   - Control de acceso por roles
   - Sesiones seguras

2. **Gestión de Estudiantes**
   - Registro completo de datos personales
   - Gestión de apoderados
   - Historial de matrículas

3. **Módulo Académico**
   - Niveles: Inicial, Primaria, Secundaria
   - Grados y secciones
   - Áreas curriculares y cursos

4. **Registro de Notas**
   - Sistema literal (AD, A, B, C) para Inicial y Primaria
   - Sistema vigesimal (0-20) para Secundaria
   - Notas por bimestre
   - Generación de libretas de notas

5. **Control de Asistencia**
   - Registro diario
   - Estados: Presente, Ausente, Tardanza, Justificado
   - Reportes por sección

6. **Gestión de Pagos**
   - Conceptos de pago configurables
   - Cuotas mensuales
   - Métodos de pago: Efectivo, Transferencia, Yape, Plin
   - Reportes de morosidad e ingresos

## Requisitos

- PHP 7.4+
- MySQL 5.7+
- Apache con mod_rewrite habilitado
- Extensión PDO MySQL

## Instalación

### 1. Configurar Base de Datos

```bash
# Crear la base de datos y tablas
mysql -u root -p < database/schema.sql

# Cargar datos iniciales
mysql -u root -p < database/seed.sql
```

### 2. Configurar la Aplicación

Editar el archivo `config/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'tu_contraseña');
define('DB_NAME', 'sistema_escolar');

define('APP_URL', 'http://localhost/colegios/php-mvc/public');
```

### 3. Configurar Apache

Asegúrate de que el archivo `.htaccess` en `public/` esté funcionando correctamente.

Para XAMPP/WAMP, coloca el proyecto en `htdocs/colegios/php-mvc`.

### 4. Acceder al Sistema

Abre en tu navegador: `http://localhost/colegios/php-mvc/public`

## Usuarios por Defecto

| Email | Contraseña | Rol |
|-------|------------|-----|
| admin@colegio.edu.pe | password | ADMIN |
| director@colegio.edu.pe | password | DIRECTOR |
| secretaria@colegio.edu.pe | password | SECRETARIA |
| profesor@colegio.edu.pe | password | PROFESOR |

> **Nota:** La contraseña por defecto es "password" (hash bcrypt estándar de Laravel/PHP)

## Estructura del Proyecto

```
php-mvc/
├── app/
│   ├── controllers/     # Controladores
│   ├── models/          # Modelos
│   └── views/           # Vistas
│       ├── auth/
│       ├── estudiantes/
│       ├── notas/
│       ├── asistencia/
│       ├── pagos/
│       ├── reportes/
│       └── layouts/
├── config/
│   └── config.php       # Configuración
├── core/
│   ├── Controller.php   # Controlador base
│   ├── Database.php     # Conexión PDO
│   └── Router.php       # Enrutador
├── database/
│   ├── schema.sql       # Esquema de BD
│   └── seed.sql         # Datos iniciales
├── public/
│   ├── index.php        # Punto de entrada
│   └── .htaccess        # Reescritura URL
└── README.md
```

## Arquitectura MVC

### Models
Los modelos manejan la lógica de datos y consultas a la base de datos usando PDO.

### Views
Las vistas usan Bootstrap 5 para el diseño responsivo.

### Controllers
Los controladores procesan las peticiones y coordinan modelos y vistas.

## Rutas Principales

- `/` o `/dashboard` - Panel principal
- `/estudiantes` - Lista de estudiantes
- `/estudiantes/crear` - Nuevo estudiante
- `/estudiantes/ver/{id}` - Ver detalle
- `/notas` - Registro de notas
- `/asistencia` - Control de asistencia
- `/pagos` - Gestión de pagos
- `/pagos/morosidad` - Reporte de morosos
- `/reportes/libreta/{id}` - Libreta de notas

## Sistema de Calificaciones Peruano

- **Inicial y Primaria**: Sistema literal
  - AD: Logro destacado
  - A: Logro esperado
  - B: En proceso
  - C: En inicio

- **Secundaria**: Sistema vigesimal (0-20)

## Seguridad

- Contraseñas hasheadas con `password_hash()` (bcrypt)
- Consultas preparadas con PDO para prevenir SQL injection
- Escape de salida con `htmlspecialchars()`
- Validación de roles por ruta

## Personalización

### Cambiar Logo/Nombre
Editar en `config/config.php`:
```php
define('APP_NAME', 'Mi Colegio');
```

### Agregar Nuevo Módulo

1. Crear modelo en `app/models/`
2. Crear controlador en `app/controllers/`
3. Crear vistas en `app/views/nombremodulo/`
4. Agregar enlace en `app/views/layouts/main.php`

## Licencia

MIT
