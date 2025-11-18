const express = require('express');
const { PrismaClient } = require('@prisma/client');
const { authMiddleware, requireRole } = require('../middlewares/auth.middleware');

const router = express.Router();
const prisma = new PrismaClient();

// Listar apoderados de un estudiante
router.get('/estudiante/:estudianteId', authMiddleware, async (req, res) => {
  try {
    const apoderados = await prisma.apoderado.findMany({
      where: { estudianteId: parseInt(req.params.estudianteId) },
      orderBy: { esPrincipal: 'desc' }
    });
    res.json(apoderados);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener apoderados' });
  }
});

// Crear apoderado
router.post('/', authMiddleware, requireRole('ADMIN', 'DIRECTOR', 'SECRETARIA'), async (req, res) => {
  try {
    const {
      estudianteId, dni, nombres, apellidos, parentesco, telefono,
      telefonoTrabajo, email, ocupacion, direccion, lugarTrabajo, esPrincipal
    } = req.body;

    // Si es principal, quitar el flag de otros apoderados
    if (esPrincipal) {
      await prisma.apoderado.updateMany({
        where: { estudianteId },
        data: { esPrincipal: false }
      });
    }

    const apoderado = await prisma.apoderado.create({
      data: {
        estudianteId, dni, nombres, apellidos, parentesco, telefono,
        telefonoTrabajo, email, ocupacion, direccion, lugarTrabajo, esPrincipal
      }
    });

    res.status(201).json(apoderado);
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error al crear apoderado' });
  }
});

// Actualizar apoderado
router.put('/:id', authMiddleware, requireRole('ADMIN', 'DIRECTOR', 'SECRETARIA'), async (req, res) => {
  try {
    const { esPrincipal, estudianteId } = req.body;

    // Si es principal, quitar el flag de otros apoderados
    if (esPrincipal && estudianteId) {
      await prisma.apoderado.updateMany({
        where: { estudianteId, NOT: { id: parseInt(req.params.id) } },
        data: { esPrincipal: false }
      });
    }

    const apoderado = await prisma.apoderado.update({
      where: { id: parseInt(req.params.id) },
      data: req.body
    });

    res.json(apoderado);
  } catch (error) {
    res.status(500).json({ error: 'Error al actualizar apoderado' });
  }
});

// Eliminar apoderado
router.delete('/:id', authMiddleware, requireRole('ADMIN', 'DIRECTOR', 'SECRETARIA'), async (req, res) => {
  try {
    await prisma.apoderado.delete({ where: { id: parseInt(req.params.id) } });
    res.json({ message: 'Apoderado eliminado' });
  } catch (error) {
    res.status(500).json({ error: 'Error al eliminar apoderado' });
  }
});

module.exports = router;
