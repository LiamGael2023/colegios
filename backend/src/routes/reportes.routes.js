const express = require('express');
const { PrismaClient } = require('@prisma/client');
const PDFDocument = require('pdfkit');
const { authMiddleware, requireRole } = require('../middlewares/auth.middleware');

const router = express.Router();
const prisma = new PrismaClient();

// Generar PDF de libreta de notas
router.get('/libreta-pdf/:estudianteId', authMiddleware, async (req, res) => {
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
            curso: { include: { areaCurricular: true } },
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

    const matricula = estudiante.matriculas[0];
    if (!matricula) {
      return res.status(404).json({ error: 'Matrícula no encontrada' });
    }

    // Crear PDF
    const doc = new PDFDocument({ margin: 50 });

    res.setHeader('Content-Type', 'application/pdf');
    res.setHeader('Content-Disposition', `attachment; filename=libreta_${estudiante.codigo}.pdf`);

    doc.pipe(res);

    // Encabezado
    doc.fontSize(18).text('LIBRETA DE NOTAS', { align: 'center' });
    doc.moveDown();
    doc.fontSize(14).text(`Año Escolar ${matricula.anioEscolar.anio}`, { align: 'center' });
    doc.moveDown();

    // Datos del estudiante
    doc.fontSize(10);
    doc.text(`Estudiante: ${estudiante.apellidoPaterno} ${estudiante.apellidoMaterno}, ${estudiante.nombres}`);
    doc.text(`Código: ${estudiante.codigo}`);
    doc.text(`DNI: ${estudiante.dni || 'No registrado'}`);
    doc.text(`Nivel: ${matricula.seccion.grado.nivel.nombre}`);
    doc.text(`Grado: ${matricula.seccion.grado.nombre} - Sección: ${matricula.seccion.nombre}`);
    doc.moveDown();

    // Notas por área
    const periodos = matricula.anioEscolar.periodos;

    // Organizar notas
    const areasMap = {};
    estudiante.notas.forEach(nota => {
      const areaName = nota.curso.areaCurricular.nombre;
      if (!areasMap[areaName]) {
        areasMap[areaName] = {};
      }
      if (!areasMap[areaName][nota.curso.nombre]) {
        areasMap[areaName][nota.curso.nombre] = {};
      }
      areasMap[areaName][nota.curso.nombre][nota.periodoId] = nota.calificacion;
    });

    // Tabla de notas
    doc.fontSize(12).text('CALIFICACIONES', { underline: true });
    doc.moveDown(0.5);

    Object.entries(areasMap).forEach(([area, cursos]) => {
      doc.fontSize(10).text(area, { bold: true });

      Object.entries(cursos).forEach(([curso, notas]) => {
        let notasStr = `  ${curso}: `;
        periodos.forEach(p => {
          notasStr += `${p.nombre.substring(0, 3)}: ${notas[p.id] || '-'} | `;
        });
        doc.fontSize(9).text(notasStr.slice(0, -3));
      });
      doc.moveDown(0.3);
    });

    // Asistencia
    doc.moveDown();
    doc.fontSize(12).text('ASISTENCIA', { underline: true });
    doc.moveDown(0.5);

    const presente = estudiante.asistencias.filter(a => a.estado === 'PRESENTE').length;
    const ausente = estudiante.asistencias.filter(a => a.estado === 'AUSENTE').length;
    const tardanza = estudiante.asistencias.filter(a => a.estado === 'TARDANZA').length;
    const justificado = estudiante.asistencias.filter(a => a.estado === 'JUSTIFICADO').length;

    doc.fontSize(9);
    doc.text(`Días asistidos: ${presente}`);
    doc.text(`Inasistencias: ${ausente}`);
    doc.text(`Tardanzas: ${tardanza}`);
    doc.text(`Justificaciones: ${justificado}`);

    // Pie de página
    doc.moveDown(2);
    doc.fontSize(8).text(`Generado el ${new Date().toLocaleDateString('es-PE')}`, { align: 'right' });

    doc.end();
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error al generar libreta' });
  }
});

// Reporte general de sección
router.get('/seccion/:seccionId', authMiddleware, async (req, res) => {
  try {
    const { anioEscolarId } = req.query;
    const seccionId = parseInt(req.params.seccionId);

    const seccion = await prisma.seccion.findUnique({
      where: { id: seccionId },
      include: {
        grado: { include: { nivel: true } },
        matriculas: {
          where: {
            estado: 'ACTIVA',
            anioEscolarId: parseInt(anioEscolarId)
          },
          include: {
            estudiante: {
              include: {
                notas: {
                  where: { periodo: { anioEscolarId: parseInt(anioEscolarId) } },
                  include: { curso: true, periodo: true }
                },
                asistencias: {
                  where: { anioEscolarId: parseInt(anioEscolarId) }
                }
              }
            }
          }
        }
      }
    });

    if (!seccion) {
      return res.status(404).json({ error: 'Sección no encontrada' });
    }

    const reporte = {
      seccion: {
        nombre: seccion.nombre,
        grado: seccion.grado.nombre,
        nivel: seccion.grado.nivel.nombre
      },
      totalEstudiantes: seccion.matriculas.length,
      estudiantes: seccion.matriculas.map(m => {
        const estudiante = m.estudiante;
        const totalAsistencias = estudiante.asistencias.length;
        const presentes = estudiante.asistencias.filter(a => a.estado === 'PRESENTE').length;

        return {
          id: estudiante.id,
          codigo: estudiante.codigo,
          nombres: `${estudiante.apellidoPaterno} ${estudiante.apellidoMaterno}, ${estudiante.nombres}`,
          totalNotas: estudiante.notas.length,
          porcentajeAsistencia: totalAsistencias > 0
            ? ((presentes / totalAsistencias) * 100).toFixed(1)
            : 0
        };
      })
    };

    res.json(reporte);
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error al generar reporte' });
  }
});

// Dashboard general
router.get('/dashboard', authMiddleware, async (req, res) => {
  try {
    const { anioEscolarId } = req.query;

    const [
      totalEstudiantes,
      totalProfesores,
      totalMatriculas,
      pagosPendientes,
      ingresos
    ] = await Promise.all([
      prisma.estudiante.count({ where: { activo: true } }),
      prisma.profesor.count(),
      prisma.matricula.count({
        where: {
          anioEscolarId: parseInt(anioEscolarId),
          estado: 'ACTIVA'
        }
      }),
      prisma.pago.count({
        where: {
          anioEscolarId: parseInt(anioEscolarId),
          estado: { in: ['PENDIENTE', 'PARCIAL'] }
        }
      }),
      prisma.pago.aggregate({
        where: {
          anioEscolarId: parseInt(anioEscolarId),
          estado: 'PAGADO'
        },
        _sum: { montoPagado: true }
      })
    ]);

    // Estudiantes por nivel
    const estudiantesPorNivel = await prisma.matricula.groupBy({
      by: ['seccionId'],
      where: {
        anioEscolarId: parseInt(anioEscolarId),
        estado: 'ACTIVA'
      },
      _count: true
    });

    res.json({
      totalEstudiantes,
      totalProfesores,
      totalMatriculas,
      pagosPendientes,
      ingresosTotales: ingresos._sum.montoPagado || 0
    });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error al obtener dashboard' });
  }
});

module.exports = router;
