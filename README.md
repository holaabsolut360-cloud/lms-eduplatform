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

**Autenticación y roles ✅:**
- Tabla `users` con campo `rol` (`estudiante` / `instructor` / `administrador`) — plataforma single-tenant, sin tabla de academias
- `LoginController` / `RegisterController` con las clases nativas de Laravel (`Auth`, `Hash`) — sin dependencias externas como Breeze
- El registro público **siempre** crea cuentas de estudiante por seguridad; las cuentas de instructor/administrador solo las crea un administrador desde `Admin\UsuarioController`
- `Gate::administrar-plataforma` (instructores y administradores acceden a `/admin`) y `Gate::gestionar-pagos` (solo administradores aprueban/rechazan órdenes) definidos en `AppServiceProvider`

**Pendiente:** vistas Blade (conectar con los mockups ya validados) — es lo único que falta para que el proyecto sea 100% ejecutable de punta a punta.

**Exámenes, tareas y apariencia ✅:**
- `Estudiante\ExamenController` — rinde examen, calificación automática vía `IntentoExamen::calificar()`, respeta `intentos_permitidos`
- `Estudiante\TareaController` — entrega de tarea (archivo y/o comentario), bloqueada si `fecha_limite` ya pasó
- `Admin\ExamenController` + `Admin\PreguntaController` — crear examen y agregar preguntas con sus opciones (o respuesta esperada si es de respuesta corta)
- `Admin\TareaController` — crear tarea, listar entregas de todos los alumnos, calificar con nota + feedback
- `Admin\AparienciaController` — conecta el panel de "Apariencia" (hero, color de marca, cifras, cursos destacados con orden) con `ConfiguracionApariencia`, tal como se diseñó en el mockup
