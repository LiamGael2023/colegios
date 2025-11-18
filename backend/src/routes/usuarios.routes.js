const express = require('express');
const { PrismaClient } = require('@prisma/client');
const { authMiddleware, requireRole } = require('../middlewares/auth.middleware');

const router = express.Router();
const prisma = new PrismaClient();

// Listar usuarios
router.get('/', authMiddleware, requireRole('ADMIN', 'DIRECTOR'), async (req, res) => {
  try {
    const usuarios = await prisma.usuario.findMany({
      select: {
        id: true, email: true, nombre: true, apellidos: true,
        dni: true, telefono: true, rol: true, activo: true, createdAt: true,
        profesor: true
      },
      orderBy: { createdAt: 'desc' }
    });
    res.json(usuarios);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener usuarios' });
  }
});

// Obtener usuario por ID
router.get('/:id', authMiddleware, requireRole('ADMIN', 'DIRECTOR'), async (req, res) => {
  try {
    const usuario = await prisma.usuario.findUnique({
      where: { id: parseInt(req.params.id) },
      select: {
        id: true, email: true, nombre: true, apellidos: true,
        dni: true, telefono: true, rol: true, activo: true,
        profesor: true
      }
    });
    if (!usuario) return res.status(404).json({ error: 'Usuario no encontrado' });
    res.json(usuario);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener usuario' });
  }
});

// Actualizar usuario
router.put('/:id', authMiddleware, requireRole('ADMIN', 'DIRECTOR'), async (req, res) => {
  try {
    const { nombre, apellidos, telefono, rol, activo, especialidad } = req.body;
    const usuario = await prisma.usuario.update({
      where: { id: parseInt(req.params.id) },
      data: { nombre, apellidos, telefono, rol, activo },
      include: { profesor: true }
    });

    if (usuario.profesor && especialidad) {
      await prisma.profesor.update({
        where: { id: usuario.profesor.id },
        data: { especialidad }
      });
    }

    res.json(usuario);
  } catch (error) {
    res.status(500).json({ error: 'Error al actualizar usuario' });
  }
});

// Desactivar usuario
router.delete('/:id', authMiddleware, requireRole('ADMIN'), async (req, res) => {
  try {
    await prisma.usuario.update({
      where: { id: parseInt(req.params.id) },
      data: { activo: false }
    });
    res.json({ message: 'Usuario desactivado' });
  } catch (error) {
    res.status(500).json({ error: 'Error al desactivar usuario' });
  }
});

module.exports = router;
