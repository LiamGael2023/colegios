const express = require('express');
const { PrismaClient } = require('@prisma/client');
const { authMiddleware, requireRole } = require('../middlewares/auth.middleware');

const router = express.Router();
const prisma = new PrismaClient();

// Listar estudiantes
router.get('/', authMiddleware, async (req, res) => {
  try {
    const { nivel, grado, seccion, buscar, activo } = req.query;

    let where = {};
    if (activo !== undefined) where.activo = activo === 'true';
    if (buscar) {
      where.OR = [
        { nombres: { contains: buscar } },
        { apellidoPaterno: { contains: buscar } },
        { apellidoMaterno: { contains: buscar } },
        { dni: { contains: buscar } },
        { codigo: { contains: buscar } }
      ];
    }

    const estudiantes = await prisma.estudiante.findMany({
      where,
      include: {
        matriculas: {
          where: { estado: 'ACTIVA' },
          include: {
            seccion: {
              include: {
                grado: {
                  include: { nivel: true }
                }
              }
            },
            anioEscolar: true
          },
          take: 1
        },
        apoderados: { where: { esPrincipal: true }, take: 1 }
      },
      orderBy: [{ apellidoPaterno: 'asc' }, { apellidoMaterno: 'asc' }, { nombres: 'asc' }]
    });

    res.json(estudiantes);
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error al obtener estudiantes' });
  }
});

// Obtener estudiante por ID
router.get('/:id', authMiddleware, async (req, res) => {
  try {
    const estudiante = await prisma.estudiante.findUnique({
      where: { id: parseInt(req.params.id) },
      include: {
        apoderados: true,
        matriculas: {
          include: {
            seccion: { include: { grado: { include: { nivel: true } } } },
            anioEscolar: true
          }
        }
      }
    });
    if (!estudiante) return res.status(404).json({ error: 'Estudiante no encontrado' });
    res.json(estudiante);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener estudiante' });
  }
});

// Crear estudiante
router.post('/', authMiddleware, requireRole('ADMIN', 'DIRECTOR', 'SECRETARIA'), async (req, res) => {
  try {
    const {
      dni, nombres, apellidoPaterno, apellidoMaterno, fechaNacimiento,
      genero, direccion, telefono, email, lugarNacimiento, nacionalidad,
      lengua, religion, tipoSangre, alergias, discapacidad, observaciones
    } = req.body;

    // Generar código único
    const count = await prisma.estudiante.count();
    const codigo = `EST${new Date().getFullYear()}${String(count + 1).padStart(5, '0')}`;

    const estudiante = await prisma.estudiante.create({
      data: {
        codigo, dni, nombres, apellidoPaterno, apellidoMaterno,
        fechaNacimiento: new Date(fechaNacimiento), genero, direccion,
        telefono, email, lugarNacimiento, nacionalidad, lengua,
        religion, tipoSangre, alergias, discapacidad, observaciones
      }
    });

    res.status(201).json(estudiante);
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error al crear estudiante' });
  }
});

// Actualizar estudiante
router.put('/:id', authMiddleware, requireRole('ADMIN', 'DIRECTOR', 'SECRETARIA'), async (req, res) => {
  try {
    const estudiante = await prisma.estudiante.update({
      where: { id: parseInt(req.params.id) },
      data: {
        ...req.body,
        fechaNacimiento: req.body.fechaNacimiento ? new Date(req.body.fechaNacimiento) : undefined
      }
    });
    res.json(estudiante);
  } catch (error) {
    res.status(500).json({ error: 'Error al actualizar estudiante' });
  }
});

// Matricular estudiante
router.post('/:id/matricular', authMiddleware, requireRole('ADMIN', 'DIRECTOR', 'SECRETARIA'), async (req, res) => {
  try {
    const { seccionId, anioEscolarId, tipoMatricula, procedencia, observaciones } = req.body;
    const estudianteId = parseInt(req.params.id);

    // Verificar si ya está matriculado en el año escolar
    const existeMatricula = await prisma.matricula.findUnique({
      where: { estudianteId_anioEscolarId: { estudianteId, anioEscolarId } }
    });

    if (existeMatricula) {
      return res.status(400).json({ error: 'Estudiante ya matriculado en este año escolar' });
    }

    // Generar código de matrícula
    const count = await prisma.matricula.count({ where: { anioEscolarId } });
    const anio = await prisma.anioEscolar.findUnique({ where: { id: anioEscolarId } });
    const codigo = `MAT${anio.anio}${String(count + 1).padStart(5, '0')}`;

    const matricula = await prisma.matricula.create({
      data: {
        codigo,
        estudianteId,
        seccionId,
        anioEscolarId,
        tipoMatricula: tipoMatricula || 'REGULAR',
        procedencia,
        observaciones
      },
      include: {
        seccion: { include: { grado: { include: { nivel: true } } } },
        anioEscolar: true
      }
    });

    res.status(201).json(matricula);
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error al matricular estudiante' });
  }
});

module.exports = router;
