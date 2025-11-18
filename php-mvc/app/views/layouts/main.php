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
        .navbar-brand-image {
            height: 2rem;
        }
        @media print {
            .no-print {
                display: none !important;
            }
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
                                <a class="dropdown-item" href="<?= APP_URL ?>/reportes/libreta">
                                    Libreta de Notas
                                </a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_URL ?>/asistencia">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="ti ti-calendar-check"></i>
                                </span>
                                <span class="nav-link-title">Asistencia</span>
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
                        <div class="col-auto ms-auto">
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
