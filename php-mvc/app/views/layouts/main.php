<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?? 'Sistema Escolar' ?> - <?= APP_NAME ?></title>
    <!-- Tabler Core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        :root {
            --tblr-primary: #206bc4;
            --tblr-primary-rgb: 32, 107, 196;
        }

        .navbar-brand-image {
            height: 2rem;
        }

        /* Sidebar mejorado */
        .navbar-vertical {
            transition: all 0.3s ease;
        }

        .navbar-vertical .nav-link {
            border-radius: 0.375rem;
            margin: 0.125rem 0.5rem;
            transition: all 0.2s ease;
        }

        .navbar-vertical .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(3px);
        }

        .navbar-vertical .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            font-weight: 600;
        }

        /* Cards mejoradas */
        .card {
            transition: all 0.3s ease;
            border: 0;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Stat cards */
        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(32, 107, 196, 0.1);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* Tablas responsive mejoradas */
        .table-responsive {
            border-radius: 0.375rem;
        }

        .table thead th {
            background: #f8fafc;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
        }

        .table tbody tr {
            transition: background 0.2s ease;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        /* Mobile table cards */
        @media (max-width: 768px) {
            .table-mobile-cards thead {
                display: none;
            }

            .table-mobile-cards tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #e2e8f0;
                border-radius: 0.5rem;
                padding: 1rem;
                background: white;
            }

            .table-mobile-cards tbody td {
                display: flex;
                justify-content: space-between;
                padding: 0.5rem 0;
                border: 0;
            }

            .table-mobile-cards tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #64748b;
            }
        }

        /* Botones mejorados */
        .btn {
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* Forms mejorados */
        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--tblr-primary);
            box-shadow: 0 0 0 3px rgba(var(--tblr-primary-rgb), 0.1);
        }

        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.375rem;
        }

        .form-label.required::after {
            content: ' *';
            color: #ef4444;
        }

        /* Badges mejorados */
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
        }

        /* Avatar mejorado */
        .avatar {
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Page header sticky */
        .page-header-sticky {
            position: sticky;
            top: 0;
            z-index: 100;
            background: white;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Dropdown mejorado */
        .dropdown-menu {
            border: 0;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            padding: 0.5rem;
        }

        .dropdown-item {
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: #f1f5f9;
        }

        /* Alert mejorado */
        .alert {
            border: 0;
            border-radius: 0.5rem;
        }

        /* Search input */
        .search-input {
            background: #f1f5f9;
            border: 0;
        }

        .search-input:focus {
            background: white;
            box-shadow: 0 0 0 3px rgba(var(--tblr-primary-rgb), 0.1);
        }

        /* Mobile improvements */
        @media (max-width: 991.98px) {
            .navbar-vertical {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1030;
            }

            .page-wrapper {
                padding-top: 3.5rem;
            }

            .navbar-collapse {
                position: fixed;
                top: 3.5rem;
                left: 0;
                right: 0;
                bottom: 0;
                background: #1e293b;
                padding: 1rem;
                overflow-y: auto;
            }

            .col-lg-4, .col-lg-6, .col-lg-8 {
                margin-bottom: 1rem;
            }
        }

        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }

            .page-wrapper {
                padding: 0 !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #e2e8f0 !important;
            }
        }

        /* Empty state */
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
        }

        .empty-state-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            color: #94a3b8;
        }

        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: skeleton 1.5s infinite;
        }

        @keyframes skeleton {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
</head>
<body class="layout-fluid">
    <div class="page">
        <!-- Sidebar -->
        <aside class="navbar navbar-vertical navbar-expand-lg navbar-dark bg-dark no-print">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark">
                    <a href="<?= APP_URL ?>/dashboard">
                        <span class="text-white">
                            <i class="ti ti-school me-2"></i><?= APP_NAME ?>
                        </span>
                    </a>
                </h1>
                <div class="collapse navbar-collapse" id="sidebar-menu">
                    <ul class="navbar-nav pt-lg-3">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_URL ?>/dashboard">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="ti ti-dashboard"></i>
                                </span>
                                <span class="nav-link-title">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#navbar-estudiantes" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="ti ti-users"></i>
                                </span>
                                <span class="nav-link-title">Estudiantes</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?= APP_URL ?>/estudiantes">
                                    Listado de Estudiantes
                                </a>
                                <a class="dropdown-item" href="<?= APP_URL ?>/estudiantes/crear">
                                    Nuevo Estudiante
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= APP_URL ?>/apoderados">
                                    <i class="ti ti-users-group me-1"></i>Apoderados
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= APP_URL ?>/matriculas">
                                    <i class="ti ti-file-certificate me-1"></i>Matrículas
                                </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#navbar-notas" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="ti ti-notebook"></i>
                                </span>
                                <span class="nav-link-title">Notas</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?= APP_URL ?>/notas">
                                    Gestión de Notas
                                </a>
                                <a class="dropdown-item" href="<?= APP_URL ?>/reportes/consolidado">
                                    Consolidado de Notas
                                </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#navbar-asistencia" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="ti ti-calendar-check"></i>
                                </span>
                                <span class="nav-link-title">Asistencia</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?= APP_URL ?>/asistencia">
                                    Registro Diario
                                </a>
                                <a class="dropdown-item" href="<?= APP_URL ?>/reportes/asistenciaMensual">
                                    Reporte Mensual
                                </a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_URL ?>/horarios">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="ti ti-calendar-time"></i>
                                </span>
                                <span class="nav-link-title">Horarios</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_URL ?>/comunicados">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="ti ti-speakerphone"></i>
                                </span>
                                <span class="nav-link-title">Comunicados</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#navbar-pagos" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="ti ti-credit-card"></i>
                                </span>
                                <span class="nav-link-title">Pagos</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?= APP_URL ?>/pagos">
                                    Historial de Pagos
                                </a>
                                <a class="dropdown-item" href="<?= APP_URL ?>/pagos/nuevo">
                                    Nuevo Pago
                                </a>
                                <a class="dropdown-item" href="<?= APP_URL ?>/pagos/morosidad">
                                    Reporte de Morosidad
                                </a>
                                <a class="dropdown-item" href="<?= APP_URL ?>/pagos/ingresos">
                                    Reporte de Ingresos
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= APP_URL ?>/pagos/conceptos">
                                    Conceptos de Pago
                                </a>
                            </div>
                        </li>
                        <?php if (in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#navbar-admin" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="ti ti-settings"></i>
                                </span>
                                <span class="nav-link-title">Administración</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?= APP_URL ?>/usuarios">
                                    Usuarios del Sistema
                                </a>
                                <a class="dropdown-item" href="<?= APP_URL ?>/docentes">
                                    <i class="ti ti-chalkboard me-1"></i>Docentes
                                </a>
                                <a class="dropdown-item" href="<?= APP_URL ?>/asignaciones">
                                    <i class="ti ti-link me-1"></i>Asignaciones
                                </a>
                                <div class="dropdown-divider"></div>
                                <span class="dropdown-header">Configuración Académica</span>
                                <a class="dropdown-item" href="<?= APP_URL ?>/configuracion/anios">
                                    Años Escolares
                                </a>
                                <a class="dropdown-item" href="<?= APP_URL ?>/configuracion/grados">
                                    Grados
                                </a>
                                <a class="dropdown-item" href="<?= APP_URL ?>/configuracion/secciones">
                                    Secciones
                                </a>
                                <a class="dropdown-item" href="<?= APP_URL ?>/configuracion/cursos">
                                    Cursos
                                </a>
                                <a class="dropdown-item" href="<?= APP_URL ?>/configuracion/areas">
                                    Áreas Curriculares
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= APP_URL ?>/configuracion/institucion">
                                    <i class="ti ti-building me-1"></i>Institución
                                </a>
                            </div>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item mt-auto">
                            <a class="nav-link text-danger" href="<?= APP_URL ?>/auth/logout">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="ti ti-logout"></i>
                                </span>
                                <span class="nav-link-title">Cerrar Sesión</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        <!-- Page wrapper -->
        <div class="page-wrapper">
            <!-- Page header -->
            <div class="page-header d-print-none no-print">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title">
                                <?= $title ?? 'Dashboard' ?>
                            </h2>
                        </div>
                        <div class="col-auto ms-auto d-flex align-items-center">
                            <!-- Búsqueda global -->
                            <form action="<?= APP_URL ?>/buscar" method="GET" class="d-none d-md-block me-3">
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <i class="ti ti-search"></i>
                                    </span>
                                    <input type="text" name="q" class="form-control form-control-sm" placeholder="Buscar..." style="width: 200px;">
                                </div>
                            </form>
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-sm bg-primary-lt me-2">
                                    <i class="ti ti-user"></i>
                                </span>
                                <div class="d-none d-md-block">
                                    <div class="small text-muted">Bienvenido</div>
                                    <div class="fw-medium"><?= $_SESSION['usuario_nombre'] ?? '' ?></div>
                                </div>
                                <span class="badge bg-blue-lt ms-2"><?= $_SESSION['usuario_rol'] ?? '' ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div class="d-flex">
                                <div><i class="ti ti-check me-2"></i></div>
                                <div><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex">
                                <div><i class="ti ti-alert-circle me-2"></i></div>
                                <div><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    <?php endif; ?>

                    <?php
                    if (isset($content)) {
                        require_once APP_ROOT . '/app/views/' . $content . '.php';
                    }
                    ?>
                </div>
            </div>

            <!-- Footer -->
            <footer class="footer footer-transparent d-print-none no-print">
                <div class="container-xl">
                    <div class="row text-center align-items-center">
                        <div class="col-12">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    &copy; <?= date('Y') ?> <?= APP_NAME ?>
                                </li>
                                <li class="list-inline-item">
                                    Sistema de Gestión Escolar - Perú
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Tabler Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
    <script>
        // Marcar enlace activo en el menú
        document.querySelectorAll('.nav-link, .dropdown-item').forEach(link => {
            if (link.href === window.location.href) {
                link.classList.add('active');
                // Si está en un dropdown, expandir el padre
                const dropdown = link.closest('.dropdown');
                if (dropdown) {
                    dropdown.querySelector('.nav-link').classList.add('active');
                }
            }
        });
    </script>
</body>
</html>
