const express = require('express');
const { PrismaClient } = require('@prisma/client');
const { authMiddleware, requireRole } = require('../middlewares/auth.middleware');

const router = express.Router();
const prisma = new PrismaClient();

// Obtener asistencia por fecha y sección
router.get('/seccion/:seccionId', authMiddleware, async (req, res) => {
  try {
    const { fecha } = req.query;
    const seccionId = parseInt(req.params.seccionId);

    const matriculas = await prisma.matricula.findMany({
      where: { seccionId, estado: 'ACTIVA' },
      include: {
        estudiante: {
          include: {
            asistencias: fecha ? {
              where: { fecha: new Date(fecha) }
            } : undefined
          }
        }
      },
      orderBy: { estudiante: { apellidoPaterno: 'asc' } }
    });

    res.json(matriculas);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener asistencia' });
  }
});

// Obtener asistencia de un estudiante
router.get('/estudiante/:estudianteId', authMiddleware, async (req, res) => {
  try {
    const { mes, anioEscolarId } = req.query;
    let where = { estudianteId: parseInt(req.params.estudianteId) };

    if (anioEscolarId) where.anioEscolarId = parseInt(anioEscolarId);

    const asistencias = await prisma.asistencia.findMany({
      where,
      orderBy: { fecha: 'desc' }
    });

    // Filtrar por mes si se especifica
    const resultado = mes
      ? asistencias.filter(a => new Date(a.fecha).getMonth() + 1 === parseInt(mes))
      : asistencias;

    res.json(resultado);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener asistencia' });
  }
});

// Registrar asistencia
router.post('/', authMiddleware, requireRole('ADMIN', 'DIRECTOR', 'PROFESOR', 'SECRETARIA'), async (req, res) => {
  try {
    const { fecha, anioEscolarId, asistencias } = req.body;

    const resultados = await Promise.all(
      asistencias.map(async (asistencia) => {
        const { estudianteId, estado, horaEntrada, horaSalida, observacion } = asistencia;

        // Buscar si ya existe registro para esa fecha
        const existente = await prisma.asistencia.findUnique({
          where: {
            estudianteId_fecha: {
              estudianteId,
              fecha: new Date(fecha)
            }
          }
        });

        if (existente) {
          return await prisma.asistencia.update({
            where: { id: existente.id },
            data: { estado, horaEntrada, horaSalida, observacion }
          });
        } else {
          return await prisma.asistencia.create({
            data: {
              estudianteId,
              anioEscolarId,
              fecha: new Date(fecha),
              estado,
              horaEntrada,
              horaSalida,
              observacion
            }
          });
        }
      })
    );

    res.json(resultados);
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error al registrar asistencia' });
  }
});

// Justificar inasistencia
router.put('/:id/justificar', authMiddleware, requireRole('ADMIN', 'DIRECTOR', 'SECRETARIA'), async (req, res) => {
  try {
    const { observacion } = req.body;
    const asistencia = await prisma.asistencia.update({
      where: { id: parseInt(req.params.id) },
      data: {
        estado: 'JUSTIFICADO',
        justificado: true,
        observacion
      }
    });
    res.json(asistencia);
  } catch (error) {
    res.status(500).json({ error: 'Error al justificar inasistencia' });
  }
});

// Reporte de asistencia por sección
router.get('/reporte/seccion/:seccionId', authMiddleware, async (req, res) => {
  try {
    const { fechaInicio, fechaFin, anioEscolarId } = req.query;
    const seccionId = parseInt(req.params.seccionId);

    const matriculas = await prisma.matricula.findMany({
      where: { seccionId, estado: 'ACTIVA' },
      include: {
        estudiante: {
          include: {
            asistencias: {
              where: {
                anioEscolarId: parseInt(anioEscolarId),
                fecha: {
                  gte: new Date(fechaInicio),
                  lte: new Date(fechaFin)
                }
              }
            }
          }
        }
      },
      orderBy: { estudiante: { apellidoPaterno: 'asc' } }
    });

    const reporte = matriculas.map(m => {
      const asistencias = m.estudiante.asistencias;
      return {
        estudiante: {
          id: m.estudiante.id,
          codigo: m.estudiante.codigo,
          nombres: m.estudiante.nombres,
          apellidos: `${m.estudiante.apellidoPaterno} ${m.estudiante.apellidoMaterno}`
        },
        resumen: {
          total: asistencias.length,
          presente: asistencias.filter(a => a.estado === 'PRESENTE').length,
          ausente: asistencias.filter(a => a.estado === 'AUSENTE').length,
          tardanza: asistencias.filter(a => a.estado === 'TARDANZA').length,
          justificado: asistencias.filter(a => a.estado === 'JUSTIFICADO').length
        },
        porcentajeAsistencia: asistencias.length > 0
          ? ((asistencias.filter(a => a.estado === 'PRESENTE' || a.estado === 'TARDANZA').length / asistencias.length) * 100).toFixed(1)
          : 0
      };
    });

    res.json(reporte);
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error al generar reporte' });
  }
});

module.exports = router;
