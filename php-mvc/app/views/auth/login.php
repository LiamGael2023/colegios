<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Iniciar Sesión - <?= APP_NAME ?></title>
    <!-- Tabler Core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</head>
<body class="d-flex flex-column bg-white">
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark">
                    <i class="ti ti-school" style="font-size: 3rem; color: #206bc4;"></i>
                </a>
            </div>
            <div class="card card-md">
                <div class="card-body">
                    <h2 class="h2 text-center mb-4"><?= APP_NAME ?></h2>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <div class="d-flex">
                                <div><i class="ti ti-alert-circle me-2"></i></div>
                                <div><?= $error ?></div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= APP_URL ?>/auth/login" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <i class="ti ti-mail"></i>
                                </span>
                                <input type="email" name="email" class="form-control"
                                       placeholder="correo@ejemplo.com"
                                       value="<?= $email ?? '' ?>" required autofocus>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                Contraseña
                            </label>
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <i class="ti ti-lock"></i>
                                </span>
                                <input type="password" name="password" class="form-control"
                                       placeholder="Ingrese su contraseña" required>
                            </div>
                        </div>
                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-login me-2"></i>Ingresar al Sistema
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center text-muted mt-3">
                Sistema de Gestión Escolar - Perú
            </div>
        </div>
    </div>
    <!-- Tabler Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
</body>
</html>
