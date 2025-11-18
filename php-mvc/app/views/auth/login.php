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
    <style>
        :root {
            --tblr-primary: #206bc4;
            --tblr-primary-rgb: 32, 107, 196;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .login-card {
            border: 0;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border-radius: 1rem;
            overflow: hidden;
        }

        .login-card .card-body {
            padding: 2.5rem;
        }

        .login-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #206bc4 0%, #4299e1 100%);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 30px rgba(32, 107, 196, 0.4);
        }

        .login-logo i {
            font-size: 2.5rem;
            color: white;
        }

        .login-title {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: #64748b;
            font-size: 0.875rem;
        }

        .form-control {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--tblr-primary);
            box-shadow: 0 0 0 4px rgba(var(--tblr-primary-rgb), 0.1);
        }

        .input-icon .input-icon-addon {
            padding: 0 1rem;
            color: #94a3b8;
        }

        .input-icon .form-control {
            padding-left: 2.75rem;
        }

        .btn-login {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            background: linear-gradient(135deg, #206bc4 0%, #4299e1 100%);
            border: 0;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(32, 107, 196, 0.3);
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
        }

        .alert {
            border: 0;
            border-radius: 0.5rem;
        }

        .footer-text {
            color: rgba(255, 255, 255, 0.7);
        }

        .footer-text strong {
            color: white;
        }

        /* Animated background pattern */
        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
            overflow: hidden;
        }

        .bg-pattern::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: patternMove 20s linear infinite;
        }

        @keyframes patternMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        /* Mobile optimizations */
        @media (max-width: 576px) {
            .login-card .card-body {
                padding: 1.5rem;
            }

            .login-logo {
                width: 60px;
                height: 60px;
            }

            .login-logo i {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body class="d-flex flex-column">
    <div class="bg-pattern"></div>
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="card login-card">
                <div class="card-body">
                    <div class="login-logo">
                        <i class="ti ti-school"></i>
                    </div>
                    <h2 class="login-title text-center"><?= APP_NAME ?></h2>
                    <p class="login-subtitle text-center mb-4">Ingresa tus credenciales para acceder</p>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <div class="d-flex">
                                <div><i class="ti ti-alert-circle me-2"></i></div>
                                <div><?= $error ?></div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
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
                        <div class="mb-4">
                            <label class="form-label">Contraseña</label>
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <i class="ti ti-lock"></i>
                                </span>
                                <input type="password" name="password" class="form-control"
                                       placeholder="Ingrese su contraseña" required>
                            </div>
                        </div>
                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary btn-login w-100">
                                <i class="ti ti-login me-2"></i>Ingresar al Sistema
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center footer-text mt-4">
                <strong>Sistema de Gestión Escolar</strong><br>
                <small>Perú <?= date('Y') ?></small>
            </div>
        </div>
    </div>
    <!-- Tabler Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
</body>
</html>
