const express = require('express');
const { PrismaClient } = require('@prisma/client');
const { authMiddleware, requireRole } = require('../middlewares/auth.middleware');

const router = express.Router();
const prisma = new PrismaClient();

// ==================== CONCEPTOS DE PAGO ====================
router.get('/conceptos', authMiddleware, async (req, res) => {
  try {
    const conceptos = await prisma.conceptoPago.findMany({
      orderBy: { nombre: 'asc' }
    });
    res.json(conceptos);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener conceptos' });
  }
});

router.post('/conceptos', authMiddleware, requireRole('ADMIN', 'DIRECTOR'), async (req, res) => {
  try {
    const concepto = await prisma.conceptoPago.create({ data: req.body });
    res.status(201).json(concepto);
  } catch (error) {
    res.status(500).json({ error: 'Error al crear concepto' });
  }
});

router.put('/conceptos/:id', authMiddleware, requireRole('ADMIN', 'DIRECTOR'), async (req, res) => {
  try {
    const concepto = await prisma.conceptoPago.update({
      where: { id: parseInt(req.params.id) },
      data: req.body
    });
    res.json(concepto);
  } catch (error) {
    res.status(500).json({ error: 'Error al actualizar concepto' });
  }
});

// ==================== PAGOS ====================
// Listar pagos
router.get('/', authMiddleware, async (req, res) => {
  try {
    const { estudianteId, estado, mes, anioEscolarId } = req.query;
    const where = {};

    if (estudianteId) where.estudianteId = parseInt(estudianteId);
    if (estado) where.estado = estado;
    if (mes) where.mes = parseInt(mes);
    if (anioEscolarId) where.anioEscolarId = parseInt(anioEscolarId);

    const pagos = await prisma.pago.findMany({
      where,
      include: {
        estudiante: true,
        concepto: true,
        anioEscolar: true
      },
      orderBy: [{ fechaVencimiento: 'asc' }]
    });

    res.json(pagos);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener pagos' });
  }
});

// Pagos de un estudiante
router.get('/estudiante/:estudianteId', authMiddleware, async (req, res) => {
  try {
    const { anioEscolarId } = req.query;
    const pagos = await prisma.pago.findMany({
      where: {
        estudianteId: parseInt(req.params.estudianteId),
        ...(anioEscolarId ? { anioEscolarId: parseInt(anioEscolarId) } : {})
      },
      include: { concepto: true, anioEscolar: true },
      orderBy: [{ mes: 'asc' }, { fechaVencimiento: 'asc' }]
    });
    res.json(pagos);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener pagos' });
  }
});

// Generar cuotas mensuales para un estudiante
router.post('/generar-cuotas', authMiddleware, requireRole('ADMIN', 'DIRECTOR', 'SECRETARIA'), async (req, res) => {
  try {
    const { estudianteId, anioEscolarId, conceptoId, meses } = req.body;

    const concepto = await prisma.conceptoPago.findUnique({
      where: { id: conceptoId }
    });

    if (!concepto) {
      return res.status(404).json({ error: 'Concepto no encontrado' });
    }

    const anio = await prisma.anioEscolar.findUnique({
      where: { id: anioEscolarId }
    });

    const pagosCreados = [];

    for (const mes of meses) {
      // Verificar si ya existe el pago
      const existe = await prisma.pago.findFirst({
        where: { estudianteId, anioEscolarId, conceptoId, mes }
      });

      if (!existe) {
        const count = await prisma.pago.count();
        const numeroRecibo = `REC${anio.anio}${String(count + 1).padStart(6, '0')}`;

        // Fecha de vencimiento: día 10 del mes
        const fechaVencimiento = new Date(anio.anio, mes - 1, 10);

        const pago = await prisma.pago.create({
          data: {
            numeroRecibo,
            estudianteId,
            anioEscolarId,
            conceptoId,
            monto: concepto.monto,
            montoPagado: 0,
            mes,
            fechaVencimiento,
            estado: 'PENDIENTE'
          },
          include: { concepto: true }
        });

        pagosCreados.push(pago);
      }
    }

    res.status(201).json(pagosCreados);
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error al generar cuotas' });
  }
});

// Registrar pago
router.post('/:id/pagar', authMiddleware, requireRole('ADMIN', 'DIRECTOR', 'SECRETARIA'), async (req, res) => {
  try {
    const { montoPagado, metodoPago, observacion } = req.body;
    const pagoId = parseInt(req.params.id);

    const pago = await prisma.pago.findUnique({ where: { id: pagoId } });

    if (!pago) {
      return res.status(404).json({ error: 'Pago no encontrado' });
    }

    const nuevoMontoPagado = pago.montoPagado + montoPagado;
    let nuevoEstado = 'PARCIAL';

    if (nuevoMontoPagado >= pago.monto) {
      nuevoEstado = 'PAGADO';
    }

    const pagoActualizado = await prisma.pago.update({
      where: { id: pagoId },
      data: {
        montoPagado: nuevoMontoPagado,
        estado: nuevoEstado,
        metodoPago,
        fechaPago: new Date(),
        observacion
      },
      include: { concepto: true, estudiante: true }
    });

    res.json(pagoActualizado);
  } catch (error) {
    res.status(500).json({ error: 'Error al registrar pago' });
  }
});

// Anular pago
router.put('/:id/anular', authMiddleware, requireRole('ADMIN'), async (req, res) => {
  try {
    const { observacion } = req.body;
    const pago = await prisma.pago.update({
      where: { id: parseInt(req.params.id) },
      data: {
        estado: 'ANULADO',
        observacion
      }
    });
    res.json(pago);
  } catch (error) {
    res.status(500).json({ error: 'Error al anular pago' });
  }
});

// Reporte de morosidad
router.get('/reporte/morosidad', authMiddleware, requireRole('ADMIN', 'DIRECTOR', 'SECRETARIA'), async (req, res) => {
  try {
    const { anioEscolarId } = req.query;

    const pagosVencidos = await prisma.pago.findMany({
      where: {
        anioEscolarId: parseInt(anioEscolarId),
        estado: { in: ['PENDIENTE', 'PARCIAL'] },
        fechaVencimiento: { lt: new Date() }
      },
      include: {
        estudiante: {
          include: {
            matriculas: {
              where: { anioEscolarId: parseInt(anioEscolarId) },
              include: {
                seccion: { include: { grado: { include: { nivel: true } } } }
              }
            },
            apoderados: { where: { esPrincipal: true }, take: 1 }
          }
        },
        concepto: true
      },
      orderBy: { fechaVencimiento: 'asc' }
    });

    // Agrupar por estudiante
    const morosidadPorEstudiante = {};
    pagosVencidos.forEach(pago => {
      if (!morosidadPorEstudiante[pago.estudianteId]) {
        const matricula = pago.estudiante.matriculas[0];
        morosidadPorEstudiante[pago.estudianteId] = {
          estudiante: {
            id: pago.estudiante.id,
            codigo: pago.estudiante.codigo,
            nombres: pago.estudiante.nombres,
            apellidos: `${pago.estudiante.apellidoPaterno} ${pago.estudiante.apellidoMaterno}`,
            grado: matricula?.seccion?.grado?.nombre,
            seccion: matricula?.seccion?.nombre,
            nivel: matricula?.seccion?.grado?.nivel?.nombre
          },
          apoderado: pago.estudiante.apoderados[0],
          pagosVencidos: [],
          totalDeuda: 0
        };
      }
      morosidadPorEstudiante[pago.estudianteId].pagosVencidos.push({
        id: pago.id,
        concepto: pago.concepto.nombre,
        mes: pago.mes,
        monto: pago.monto,
        montoPagado: pago.montoPagado,
        pendiente: pago.monto - pago.montoPagado,
        fechaVencimiento: pago.fechaVencimiento
      });
      morosidadPorEstudiante[pago.estudianteId].totalDeuda += (pago.monto - pago.montoPagado);
    });

    res.json(Object.values(morosidadPorEstudiante));
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error al generar reporte' });
  }
});

// Resumen de ingresos
router.get('/reporte/ingresos', authMiddleware, requireRole('ADMIN', 'DIRECTOR'), async (req, res) => {
  try {
    const { anioEscolarId, mes } = req.query;
    let where = {
      estado: 'PAGADO',
      anioEscolarId: parseInt(anioEscolarId)
    };

    const pagos = await prisma.pago.findMany({
      where,
      include: { concepto: true }
    });

    // Filtrar por mes si se especifica
    const pagosFiltrados = mes
      ? pagos.filter(p => p.fechaPago && new Date(p.fechaPago).getMonth() + 1 === parseInt(mes))
      : pagos;

    // Agrupar por concepto
    const porConcepto = {};
    pagosFiltrados.forEach(pago => {
      if (!porConcepto[pago.conceptoId]) {
        porConcepto[pago.conceptoId] = {
          concepto: pago.concepto.nombre,
          cantidad: 0,
          total: 0
        };
      }
      porConcepto[pago.conceptoId].cantidad++;
      porConcepto[pago.conceptoId].total += pago.montoPagado;
    });

    const totalGeneral = Object.values(porConcepto).reduce((sum, c) => sum + c.total, 0);

    res.json({
      porConcepto: Object.values(porConcepto),
      totalGeneral
    });
  } catch (error) {
    res.status(500).json({ error: 'Error al generar reporte' });
  }
});

module.exports = router;
