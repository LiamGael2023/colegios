<div class="row g-2 align-items-center mb-3">
    <div class="col">
        <h3 class="page-title">Usuarios del Sistema</h3>
    </div>
    <div class="col-auto">
        <a href="<?= APP_URL ?>/usuarios/crear" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Nuevo Usuario
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th class="text-center">Rol</th>
                    <th class="text-center">Estado</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['usuarios'])): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No hay usuarios registrados
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['usuarios'] as $usuario): ?>
                        <tr>
                            <td>
                                <div class="d-flex py-1 align-items-center">
                                    <span class="avatar avatar-sm bg-<?= $usuario->rol == 'ADMIN' ? 'red' : ($usuario->rol == 'DIRECTOR' ? 'blue' : ($usuario->rol == 'PROFESOR' ? 'green' : 'azure')) ?>-lt me-2">
                                        <?= strtoupper(substr($usuario->nombre, 0, 1)) ?>
                                    </span>
                                    <div class="flex-fill">
                                        <div class="font-weight-medium"><?= $usuario->nombre ?> <?= $usuario->apellidos ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted"><?= $usuario->email ?></td>
                            <td class="text-muted"><?= $usuario->dni ?: '-' ?></td>
                            <td><?= $usuario->telefono ?: '-' ?></td>
                            <td class="text-center">
                                <?php
                                $rolesColors = [
                                    'ADMIN' => 'red',
                                    'DIRECTOR' => 'blue',
                                    'SECRETARIA' => 'azure',
                                    'PROFESOR' => 'green'
                                ];
                                ?>
                                <span class="badge bg-<?= $rolesColors[$usuario->rol] ?? 'secondary' ?>-lt">
                                    <?= $usuario->rol ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if ($usuario->activo): ?>
                                    <span class="badge bg-green-lt">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-red-lt">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle align-text-top" data-bs-toggle="dropdown">
                                        Acciones
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="<?= APP_URL ?>/usuarios/editar/<?= $usuario->id ?>">
                                            <i class="ti ti-edit me-2"></i> Editar
                                        </a>
                                        <a class="dropdown-item" href="<?= APP_URL ?>/usuarios/cambiarPassword/<?= $usuario->id ?>">
                                            <i class="ti ti-key me-2"></i> Cambiar Contraseña
                                        </a>
                                        <?php if ($usuario->id != $_SESSION['usuario_id']): ?>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-red" href="<?= APP_URL ?>/usuarios/eliminar/<?= $usuario->id ?>"
                                               onclick="return confirm('¿Está seguro de eliminar este usuario?')">
                                                <i class="ti ti-trash me-2"></i> Eliminar
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
