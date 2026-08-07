# LMS EduPlatform

Plataforma de e-learning a medida (estilo Coursera/Udemy), para uso **exclusivo de una sola institución** — no es multi-tenant/SaaS.

## Alcance del proyecto

- Venta de cursos (Yape, Plin o transferencia bancaria en soles/dólares, con validación manual de comprobante)
- Contenido de cursos organizado en módulos y lecciones (video vía URL de YouTube, texto, PDF o archivo)
- Bloqueo secuencial de contenido configurable por curso, con posibilidad de excepción individual por alumno
- Exámenes/quizzes con calificación automática y tareas con entrega y calificación manual del instructor
- Certificado automático al completar el curso
- Panel de "Apariencia" para que el administrador edite el home público (hero, color de marca, cursos destacados) sin tocar código
- Pensado para desplegarse en hosting compartido/semidedicado (Banahosting) vía cPanel

## Roadmap de fases

1. **Base del proyecto** — estructura Laravel, limpieza de lógica multi-tenant heredada de proyectos anteriores
2. **Contenido del curso** — módulos y lecciones ✅
3. **Reproductor y progreso del alumno** — vista "tomar curso", bloqueo secuencial ✅ (controlador y lógica; falta vista Blade)
4. **Exámenes y tareas** ✅ (calificación automática de exámenes, entrega/calificación manual de tareas)
5. **Ventas y pagos** ✅ (Yape/Plin/cuenta bancaria en PEN/USD, checkout + aprobación manual → activa matrícula automáticamente)
6. **Certificados** ✅ (emisión automática al completar 100% del curso) **y despliegue en Banahosting** (pendiente)

## Estado actual

**Esquema de datos completo** (14 migraciones + 15 modelos Eloquent).

**Controladores y rutas ya conectados:**
- `CatalogoController` — home público, búsqueda, detalle de curso
- `CheckoutController` — checkout con selección de método de pago y subida de comprobante
- `Estudiante\TomarCursoController` — reproductor de curso, con la lógica de **bloqueo secuencial configurable por curso y por alumno** ya implementada (`leccionDesbloqueada()`), cálculo de avance y disparo automático de emisión de certificado al llegar a 100%
- `Admin\CursoController` / `ModuloController` / `LeccionController` — CRUD del panel de instructor
- `Admin\OrdenController` — aprobar/rechazar comprobantes de pago

**Pendiente:** vistas Blade (conectar con los mockups ya validados), autenticación (login/registro), y el `Gate` `administrar-plataforma` referenciado en las rutas de admin.
