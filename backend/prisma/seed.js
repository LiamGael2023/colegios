const { PrismaClient } = require('@prisma/client');
const bcrypt = require('bcryptjs');

const prisma = new PrismaClient();

async function main() {
  console.log('Iniciando seed...');

  // Crear institución educativa
  const institucion = await prisma.institucionEducativa.create({
    data: {
      nombre: 'I.E.P. San José',
      codigoModular: '1234567',
      direccion: 'Av. Principal 123, Lima',
      telefono: '01-1234567',
      email: 'info@iepsanjose.edu.pe',
      director: 'Dr. Juan Pérez García',
      ugel: 'UGEL 03',
      region: 'Lima'
    }
  });

  // Crear año escolar 2024
  const anioEscolar = await prisma.anioEscolar.create({
    data: {
      anio: 2024,
      fechaInicio: new Date('2024-03-01'),
      fechaFin: new Date('2024-12-20'),
      activo: true,
      institucionId: institucion.id,
      periodos: {
        create: [
          { nombre: 'Bimestre 1', tipo: 'BIMESTRE', numero: 1, fechaInicio: new Date('2024-03-01'), fechaFin: new Date('2024-05-10') },
          { nombre: 'Bimestre 2', tipo: 'BIMESTRE', numero: 2, fechaInicio: new Date('2024-05-13'), fechaFin: new Date('2024-07-26') },
          { nombre: 'Bimestre 3', tipo: 'BIMESTRE', numero: 3, fechaInicio: new Date('2024-08-12'), fechaFin: new Date('2024-10-11') },
          { nombre: 'Bimestre 4', tipo: 'BIMESTRE', numero: 4, fechaInicio: new Date('2024-10-14'), fechaFin: new Date('2024-12-20') }
        ]
      }
    }
  });

  // Crear niveles
  const inicial = await prisma.nivel.create({ data: { nombre: 'INICIAL', codigo: 'INI' } });
  const primaria = await prisma.nivel.create({ data: { nombre: 'PRIMARIA', codigo: 'PRI' } });
  const secundaria = await prisma.nivel.create({ data: { nombre: 'SECUNDARIA', codigo: 'SEC' } });

  // Crear grados para cada nivel
  // Inicial: 3, 4, 5 años
  for (let i = 1; i <= 3; i++) {
    await prisma.grado.create({
      data: {
        nombre: `${i + 2} años`,
        numero: i,
        nivelId: inicial.id,
        secciones: {
          create: [
            { nombre: 'A', capacidad: 25 },
            { nombre: 'B', capacidad: 25 }
          ]
        }
      }
    });
  }

  // Primaria: 1ro a 6to
  const ordinalPrimaria = ['1er', '2do', '3er', '4to', '5to', '6to'];
  for (let i = 1; i <= 6; i++) {
    await prisma.grado.create({
      data: {
        nombre: `${ordinalPrimaria[i - 1]} Grado`,
        numero: i,
        nivelId: primaria.id,
        secciones: {
          create: [
            { nombre: 'A', capacidad: 30 },
            { nombre: 'B', capacidad: 30 }
          ]
        }
      }
    });
  }

  // Secundaria: 1ro a 5to
  const ordinalSecundaria = ['1er', '2do', '3er', '4to', '5to'];
  for (let i = 1; i <= 5; i++) {
    await prisma.grado.create({
      data: {
        nombre: `${ordinalSecundaria[i - 1]} Año`,
        numero: i,
        nivelId: secundaria.id,
        secciones: {
          create: [
            { nombre: 'A', capacidad: 35 },
            { nombre: 'B', capacidad: 35 }
          ]
        }
      }
    });
  }

  // Crear áreas curriculares
  const areas = await Promise.all([
    prisma.areaCurricular.create({ data: { nombre: 'Matemática', descripcion: 'Área de Matemática' } }),
    prisma.areaCurricular.create({ data: { nombre: 'Comunicación', descripcion: 'Área de Comunicación' } }),
    prisma.areaCurricular.create({ data: { nombre: 'Ciencias Sociales', descripcion: 'Área de Ciencias Sociales' } }),
    prisma.areaCurricular.create({ data: { nombre: 'Ciencia y Tecnología', descripcion: 'Área de Ciencia y Tecnología' } }),
    prisma.areaCurricular.create({ data: { nombre: 'Educación Física', descripcion: 'Área de Educación Física' } }),
    prisma.areaCurricular.create({ data: { nombre: 'Arte y Cultura', descripcion: 'Área de Arte y Cultura' } }),
    prisma.areaCurricular.create({ data: { nombre: 'Inglés', descripcion: 'Área de Inglés' } }),
    prisma.areaCurricular.create({ data: { nombre: 'Educación Religiosa', descripcion: 'Área de Educación Religiosa' } })
  ]);

  // Crear conceptos de pago
  await Promise.all([
    prisma.conceptoPago.create({ data: { nombre: 'Matrícula', descripcion: 'Pago de matrícula anual', monto: 350, esRecurrente: false } }),
    prisma.conceptoPago.create({ data: { nombre: 'Pensión Mensual', descripcion: 'Pensión de enseñanza mensual', monto: 450, esRecurrente: true } }),
    prisma.conceptoPago.create({ data: { nombre: 'APAFA', descripcion: 'Cuota de APAFA', monto: 100, esRecurrente: false } }),
    prisma.conceptoPago.create({ data: { nombre: 'Agenda Escolar', descripcion: 'Agenda escolar', monto: 25, esRecurrente: false } }),
    prisma.conceptoPago.create({ data: { nombre: 'Uniforme', descripcion: 'Uniforme escolar completo', monto: 200, esRecurrente: false } })
  ]);

  // Crear usuario administrador
  const hashedPassword = await bcrypt.hash('admin123', 10);
  await prisma.usuario.create({
    data: {
      email: 'admin@colegio.edu.pe',
      password: hashedPassword,
      nombre: 'Administrador',
      apellidos: 'Sistema',
      dni: '00000001',
      rol: 'ADMIN',
      activo: true
    }
  });

  // Crear director
  await prisma.usuario.create({
    data: {
      email: 'director@colegio.edu.pe',
      password: hashedPassword,
      nombre: 'Juan',
      apellidos: 'Pérez García',
      dni: '12345678',
      telefono: '999888777',
      rol: 'DIRECTOR',
      activo: true
    }
  });

  // Crear secretaria
  await prisma.usuario.create({
    data: {
      email: 'secretaria@colegio.edu.pe',
      password: hashedPassword,
      nombre: 'María',
      apellidos: 'López Sánchez',
      dni: '87654321',
      telefono: '999777666',
      rol: 'SECRETARIA',
      activo: true
    }
  });

  // Crear profesor de ejemplo
  await prisma.usuario.create({
    data: {
      email: 'profesor@colegio.edu.pe',
      password: hashedPassword,
      nombre: 'Carlos',
      apellidos: 'Rodríguez Mendoza',
      dni: '11223344',
      telefono: '999666555',
      rol: 'PROFESOR',
      activo: true,
      profesor: {
        create: {
          especialidad: 'Matemáticas'
        }
      }
    }
  });

  console.log('Seed completado exitosamente');
  console.log('Usuarios creados:');
  console.log('  - admin@colegio.edu.pe / admin123 (ADMIN)');
  console.log('  - director@colegio.edu.pe / admin123 (DIRECTOR)');
  console.log('  - secretaria@colegio.edu.pe / admin123 (SECRETARIA)');
  console.log('  - profesor@colegio.edu.pe / admin123 (PROFESOR)');
}

main()
  .catch((e) => {
    console.error(e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
