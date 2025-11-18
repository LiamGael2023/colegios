const express = require('express');
const { PrismaClient } = require('@prisma/client');
const { authMiddleware, requireRole } = require('../middlewares/auth.middleware');

const router = express.Router();
const prisma = new PrismaClient();

// Obtener notas por estudiante
router.get('/estudiante/:estudianteId', authMiddleware, async (req, res) => {
  try {
    const { periodoId, anioEscolarId } = req.query;
    let where = { estudianteId: parseInt(req.params.estudianteId) };

    if (periodoId) where.periodoId = parseInt(periodoId);
    if (anioEscolarId) {
      where.periodo = { anioEscolarId: parseInt(anioEscolarId) };
    }

    const notas = await prisma.nota.findMany({
      where,
      include: {
        curso: { include: { areaCurricular: true } },
        competencia: true,
        periodo: true
      },
      orderBy: [{ periodo: { numero: 'asc' } }, { curso: { nombre: 'asc' } }]
    });
    res.json(notas);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener notas' });
  }
});

// Obtener notas por sección y curso (para el profesor)
router.get('/seccion/:seccionId/curso/:cursoId', authMiddleware, async (req, res) => {
  try {
    const { periodoId } = req.query;
    const seccionId = parseInt(req.params.seccionId);
    const cursoId = parseInt(req.params.cursoId);

    // Obtener estudiantes matriculados en la sección
    const matriculas = await prisma.matricula.findMany({
      where: { seccionId, estado: 'ACTIVA' },
      include: {
        estudiante: {
          include: {
            notas: {
              where: {
                cursoId,
                ...(periodoId ? { periodoId: parseInt(periodoId) } : {})
              },
              include: { competencia: true, periodo: true }
            }
          }
        }
      },
      orderBy: {
        estudiante: { apellidoPaterno: 'asc' }
      }
    });

    res.json(matriculas);
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error al obtener notas' });
  }
});

// Registrar/Actualizar notas
router.post('/', authMiddleware, requireRole('ADMIN', 'DIRECTOR', 'PROFESOR'), async (req, res) => {
  try {
    const { notas } = req.body;

    const resultados = await Promise.all(
      notas.map(async (nota) => {
        const { estudianteId, cursoId, competenciaId, periodoId, calificacion, tipo, descripcion } = nota;

        // Buscar si ya existe la nota
        const existente = await prisma.nota.findFirst({
          where: {
            estudianteId,
            cursoId,
            periodoId,
            competenciaId: competenciaId || null
          }
        });

        if (existente) {
          return await prisma.nota.update({
            where: { id: existente.id },
            data: { calificacion, descripcion }
          });
        } else {
          return await prisma.nota.create({
            data: {
              estudianteId,
              cursoId,
              competenciaId,
              periodoId,
              calificacion,
              tipo,
              descripcion
            }
          });
        }
      })
    );

    res.json(resultados);
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error al registrar notas' });
  }
});

// Obtener promedios por estudiante
router.get('/promedios/:estudianteId', authMiddleware, async (req, res) => {
  try {
    const { anioEscolarId } = req.query;
    const estudianteId = parseInt(req.params.estudianteId);

    const notas = await prisma.nota.findMany({
      where: {
        estudianteId,
        periodo: { anioEscolarId: parseInt(anioEscolarId) }
      },
      include: {
        curso: { include: { areaCurricular: true } },
        periodo: true
      }
    });

    // Agrupar y calcular promedios
    const cursos = {};
    notas.forEach(nota => {
      if (!cursos[nota.cursoId]) {
        cursos[nota.cursoId] = {
          curso: nota.curso,
          periodos: {},
          notas: []
        };
      }
      cursos[nota.cursoId].notas.push(nota);
      if (!cursos[nota.cursoId].periodos[nota.periodoId]) {
        cursos[nota.cursoId].periodos[nota.periodoId] = {
          periodo: nota.periodo,
          calificaciones: []
        };
      }
      cursos[nota.cursoId].periodos[nota.periodoId].calificaciones.push(nota.calificacion);
    });

    res.json(Object.values(cursos));
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener promedios' });
  }
});

// Generar libreta de notas
router.get('/libreta/:estudianteId', authMiddleware, async (req, res) => {
  try {
    const { anioEscolarId } = req.query;
    const estudianteId = parseInt(req.params.estudianteId);

    const estudiante = await prisma.estudiante.findUnique({
      where: { id: estudianteId },
      include: {
        matriculas: {
          where: { anioEscolarId: parseInt(anioEscolarId) },
          include: {
            seccion: { include: { grado: { include: { nivel: true } } } },
            anioEscolar: { include: { periodos: { orderBy: { numero: 'asc' } } } }
          }
        },
        notas: {
          where: { periodo: { anioEscolarId: parseInt(anioEscolarId) } },
          include: {
            curso: { include: { areaCurricular: true, competencias: true } },
            competencia: true,
            periodo: true
          }
        },
        asistencias: {
          where: { anioEscolarId: parseInt(anioEscolarId) }
        }
      }
    });

    if (!estudiante) {
      return res.status(404).json({ error: 'Estudiante no encontrado' });
    }

    // Organizar datos para la libreta
    const matricula = estudiante.matriculas[0];
    const periodos = matricula?.anioEscolar?.periodos || [];

    // Agrupar notas por área y curso
    const areasMap = {};
    estudiante.notas.forEach(nota => {
      const areaId = nota.curso.areaCurricularId;
      const areaName = nota.curso.areaCurricular.nombre;

      if (!areasMap[areaId]) {
        areasMap[areaId] = {
          area: areaName,
          cursos: {}
        };
      }

      if (!areasMap[areaId].cursos[nota.cursoId]) {
        areasMap[areaId].cursos[nota.cursoId] = {
          nombre: nota.curso.nombre,
          periodos: {}
        };
      }

      if (!areasMap[areaId].cursos[nota.cursoId].periodos[nota.periodoId]) {
        areasMap[areaId].cursos[nota.cursoId].periodos[nota.periodoId] = [];
      }

      areasMap[areaId].cursos[nota.cursoId].periodos[nota.periodoId].push({
        calificacion: nota.calificacion,
        competencia: nota.competencia?.nombre
      });
    });

    // Calcular resumen de asistencia
    const asistencia = {
      presente: estudiante.asistencias.filter(a => a.estado === 'PRESENTE').length,
      ausente: estudiante.asistencias.filter(a => a.estado === 'AUSENTE').length,
      tardanza: estudiante.asistencias.filter(a => a.estado === 'TARDANZA').length,
      justificado: estudiante.asistencias.filter(a => a.estado === 'JUSTIFICADO').length
    };

    const libreta = {
      estudiante: {
        codigo: estudiante.codigo,
        nombres: estudiante.nombres,
        apellidos: `${estudiante.apellidoPaterno} ${estudiante.apellidoMaterno}`,
        dni: estudiante.dni
      },
      matricula: matricula ? {
        codigo: matricula.codigo,
        grado: matricula.seccion.grado.nombre,
        seccion: matricula.seccion.nombre,
        nivel: matricula.seccion.grado.nivel.nombre,
        anio: matricula.anioEscolar.anio
      } : null,
      periodos,
      areas: Object.values(areasMap).map(area => ({
        ...area,
        cursos: Object.values(area.cursos)
      })),
      asistencia
    };

    res.json(libreta);
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error al generar libreta' });
  }
});

module.exports = router;
