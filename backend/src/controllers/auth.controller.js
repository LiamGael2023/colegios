const { PrismaClient } = require('@prisma/client');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');

const prisma = new PrismaClient();

const login = async (req, res) => {
  try {
    const { email, password } = req.body;

    const usuario = await prisma.usuario.findUnique({
      where: { email },
      include: { profesor: true }
    });

    if (!usuario) {
      return res.status(401).json({ error: 'Credenciales inválidas' });
    }

    const validPassword = await bcrypt.compare(password, usuario.password);
    if (!validPassword) {
      return res.status(401).json({ error: 'Credenciales inválidas' });
    }

    if (!usuario.activo) {
      return res.status(401).json({ error: 'Usuario desactivado' });
    }

    const token = jwt.sign(
      { id: usuario.id, rol: usuario.rol },
      process.env.JWT_SECRET,
      { expiresIn: '24h' }
    );

    const { password: _, ...usuarioSinPassword } = usuario;
    res.json({ token, usuario: usuarioSinPassword });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error en el servidor' });
  }
};

const register = async (req, res) => {
  try {
    const { email, password, nombre, apellidos, dni, telefono, rol, especialidad } = req.body;

    const existeUsuario = await prisma.usuario.findFirst({
      where: { OR: [{ email }, { dni }] }
    });

    if (existeUsuario) {
      return res.status(400).json({ error: 'El email o DNI ya está registrado' });
    }

    const hashedPassword = await bcrypt.hash(password, 10);

    const usuario = await prisma.usuario.create({
      data: {
        email,
        password: hashedPassword,
        nombre,
        apellidos,
        dni,
        telefono,
        rol: rol || 'PROFESOR',
        profesor: rol === 'PROFESOR' ? {
          create: { especialidad }
        } : undefined
      },
      include: { profesor: true }
    });

    const { password: _, ...usuarioSinPassword } = usuario;
    res.status(201).json(usuarioSinPassword);
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error al registrar usuario' });
  }
};

const getProfile = async (req, res) => {
  try {
    const { password: _, ...usuarioSinPassword } = req.usuario;
    res.json(usuarioSinPassword);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener perfil' });
  }
};

const changePassword = async (req, res) => {
  try {
    const { currentPassword, newPassword } = req.body;

    const validPassword = await bcrypt.compare(currentPassword, req.usuario.password);
    if (!validPassword) {
      return res.status(400).json({ error: 'Contraseña actual incorrecta' });
    }

    const hashedPassword = await bcrypt.hash(newPassword, 10);
    await prisma.usuario.update({
      where: { id: req.usuario.id },
      data: { password: hashedPassword }
    });

    res.json({ message: 'Contraseña actualizada correctamente' });
  } catch (error) {
    res.status(500).json({ error: 'Error al cambiar contraseña' });
  }
};

module.exports = { login, register, getProfile, changePassword };
