const express = require('express');
const { PrismaClient } = require('@prisma/client');
const { authMiddleware, requireRole } = require('../middlewares/auth.middleware');

const router = express.Router();
const prisma = new PrismaClient();

// ==================== INSTITUCIÓN ====================
router.get('/institucion', authMiddleware, async (req, res) => {
  try {
    const institucion = await prisma.institucionEducativa.findFirst({
      include: { aniosEscolares: { orderBy: { anio: 'desc' } } }
    });
    res.json(institucion);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener institución' });
  }
});

router.post('/institucion', authMiddleware, requireRole('ADMIN'), async (req, res) => {
  try {
    const institucion = await prisma.institucionEducativa.create({ data: req.body });
    res.status(201).json(institucion);
  } catch (error) {
    res.status(500).json({ error: 'Error al crear institución' });
  }
});

// ==================== AÑOS ESCOLARES ====================
router.get('/anios', authMiddleware, async (req, res) => {
  try {
    const anios = await prisma.anioEscolar.findMany({
      include: { periodos: { orderBy: { numero: 'asc' } } },
      orderBy: { anio: 'desc' }
    });
    res.json(anios);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener años escolares' });
  }
});

router.get('/anios/activo', authMiddleware, async (req, res) => {
  try {
    const anio = await prisma.anioEscolar.findFirst({
      where: { activo: true },
      include: { periodos: { orderBy: { numero: 'asc' } } }
    });
    res.json(anio);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener año activo' });
  }
});

router.post('/anios', authMiddleware, requireRole('ADMIN', 'DIRECTOR'), async (req, res) => {
  try {
    const { anio, fechaInicio, fechaFin, institucionId, periodos } = req.body;

    const anioEscolar = await prisma.anioEscolar.create({
      data: {
        anio,
        fechaInicio: new Date(fechaInicio),
        fechaFin: new Date(fechaFin),
        institucionId,
        periodos: {
          create: periodos.map(p => ({
            nombre: p.nombre,
            tipo: p.tipo || 'BIMESTRE',
            numero: p.numero,
            fechaInicio: new Date(p.fechaInicio),
            fechaFin: new Date(p.fechaFin)
          }))
        }
      },
      include: { periodos: true }
    });

    res.status(201).json(anioEscolar);
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error al crear año escolar' });
  }
});

// ==================== NIVELES ====================
router.get('/niveles', authMiddleware, async (req, res) => {
  try {
    const niveles = await prisma.nivel.findMany({
      include: {
        grados: {
          include: { secciones: true },
          orderBy: { numero: 'asc' }
        }
      }
    });
    res.json(niveles);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener niveles' });
  }
});

router.post('/niveles', authMiddleware, requireRole('ADMIN'), async (req, res) => {
  try {
    const nivel = await prisma.nivel.create({ data: req.body });
    res.status(201).json(nivel);
  } catch (error) {
    res.status(500).json({ error: 'Error al crear nivel' });
  }
});

// ==================== GRADOS ====================
router.get('/grados', authMiddleware, async (req, res) => {
  try {
    const { nivelId } = req.query;
    const grados = await prisma.grado.findMany({
      where: nivelId ? { nivelId: parseInt(nivelId) } : {},
      include: { nivel: true, secciones: true },
      orderBy: [{ nivelId: 'asc' }, { numero: 'asc' }]
    });
    res.json(grados);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener grados' });
  }
});

router.post('/grados', authMiddleware, requireRole('ADMIN', 'DIRECTOR'), async (req, res) => {
  try {
    const grado = await prisma.grado.create({
      data: req.body,
      include: { nivel: true }
    });
    res.status(201).json(grado);
  } catch (error) {
    res.status(500).json({ error: 'Error al crear grado' });
  }
});

// ==================== SECCIONES ====================
router.get('/secciones', authMiddleware, async (req, res) => {
  try {
    const { gradoId } = req.query;
    const secciones = await prisma.seccion.findMany({
      where: gradoId ? { gradoId: parseInt(gradoId) } : {},
      include: {
        grado: { include: { nivel: true } },
        _count: { select: { matriculas: true } }
      }
    });
    res.json(secciones);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener secciones' });
  }
});

router.post('/secciones', authMiddleware, requireRole('ADMIN', 'DIRECTOR'), async (req, res) => {
  try {
    const seccion = await prisma.seccion.create({
      data: req.body,
      include: { grado: { include: { nivel: true } } }
    });
    res.status(201).json(seccion);
  } catch (error) {
    res.status(500).json({ error: 'Error al crear sección' });
  }
});

// ==================== ÁREAS Y CURSOS ====================
router.get('/areas', authMiddleware, async (req, res) => {
  try {
    const areas = await prisma.areaCurricular.findMany({
      include: { cursos: true }
    });
    res.json(areas);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener áreas' });
  }
});

router.post('/areas', authMiddleware, requireRole('ADMIN', 'DIRECTOR'), async (req, res) => {
  try {
    const area = await prisma.areaCurricular.create({ data: req.body });
    res.status(201).json(area);
  } catch (error) {
    res.status(500).json({ error: 'Error al crear área' });
  }
});

router.get('/cursos', authMiddleware, async (req, res) => {
  try {
    const { gradoId, areaId } = req.query;
    const where = {};
    if (gradoId) where.gradoId = parseInt(gradoId);
    if (areaId) where.areaCurricularId = parseInt(areaId);

    const cursos = await prisma.curso.findMany({
      where,
      include: {
        areaCurricular: true,
        grado: { include: { nivel: true } },
        competencias: true
      }
    });
    res.json(cursos);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener cursos' });
  }
});

router.post('/cursos', authMiddleware, requireRole('ADMIN', 'DIRECTOR'), async (req, res) => {
  try {
    const { nombre, horasSemanales, areaCurricularId, gradoId, competencias } = req.body;

    const curso = await prisma.curso.create({
      data: {
        nombre,
        horasSemanales,
        areaCurricularId,
        gradoId,
        competencias: competencias ? {
          create: competencias.map(c => ({ nombre: c.nombre, descripcion: c.descripcion }))
        } : undefined
      },
      include: { areaCurricular: true, grado: true, competencias: true }
    });

    res.status(201).json(curso);
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error al crear curso' });
  }
});

// ==================== ASIGNACIÓN DE PROFESORES ====================
router.get('/asignaciones', authMiddleware, async (req, res) => {
  try {
    const { profesorId, seccionId } = req.query;
    const where = {};
    if (profesorId) where.profesorId = parseInt(profesorId);
    if (seccionId) where.seccionId = parseInt(seccionId);

    const asignaciones = await prisma.asignacionProfesor.findMany({
      where,
      include: {
        profesor: { include: { usuario: { select: { nombre: true, apellidos: true } } } },
        curso: { include: { areaCurricular: true } },
        seccion: { include: { grado: { include: { nivel: true } } } }
      }
    });
    res.json(asignaciones);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener asignaciones' });
  }
});

router.post('/asignaciones', authMiddleware, requireRole('ADMIN', 'DIRECTOR'), async (req, res) => {
  try {
    const asignacion = await prisma.asignacionProfesor.create({
      data: req.body,
      include: {
        profesor: { include: { usuario: true } },
        curso: true,
        seccion: { include: { grado: true } }
      }
    });
    res.status(201).json(asignacion);
  } catch (error) {
    res.status(500).json({ error: 'Error al crear asignación' });
  }
});

router.delete('/asignaciones/:id', authMiddleware, requireRole('ADMIN', 'DIRECTOR'), async (req, res) => {
  try {
    await prisma.asignacionProfesor.delete({ where: { id: parseInt(req.params.id) } });
    res.json({ message: 'Asignación eliminada' });
  } catch (error) {
    res.status(500).json({ error: 'Error al eliminar asignación' });
  }
});

module.exports = router;
