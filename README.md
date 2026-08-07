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
3. **Reproductor y progreso del alumno** — vista "tomar curso", bloqueo secuencial
4. **Exámenes y tareas** ✅ (calificación automática de exámenes, entrega/calificación manual de tareas)
5. **Ventas y pagos** ✅ (Yape/Plin/cuenta bancaria en PEN/USD, aprobación manual de comprobante → activa matrícula automáticamente)
6. **Certificados y despliegue en Banahosting**

## Estado actual

En construcción — Fase 1-2, 4 y 5 completas a nivel de estructura de datos (migraciones + modelos Eloquent). Faltan controladores, vistas Blade, la Fase 3 (reproductor/progreso) y la Fase 6.
