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

## Roles

- **Estudiante** — se registra libremente, compra y toma cursos
- **Instructor** — cuenta creada solo por un administrador, gestiona sus propios cursos desde `/admin`
- **Administrador** — además de todo lo del instructor, aprueba/rechaza pagos y crea otras cuentas internas

## Roadmap de fases

1. Base del proyecto — estructura Laravel, sin lógica multi-tenant ✅
2. Contenido del curso — módulos y lecciones ✅
3. Reproductor y progreso del alumno — bloqueo secuencial ✅
4. Exámenes y tareas — calificación automática/manual ✅
5. Ventas y pagos — Yape/Plin/cuenta bancaria PEN/USD ✅
6. Certificados — emisión automática al completar el curso ✅

## Estado actual

**✅ Proyecto ejecutable de punta a punta.** Esquema de datos, controladores, rutas y vistas Blade completos.

**Esquema de datos:** 15 migraciones (categorías, cursos, módulos, lecciones, matrículas, progreso, apariencia, exámenes, preguntas, opciones, intentos, tareas, entregas, métodos de pago, órdenes, certificados, usuarios) + 17 modelos Eloquent.

**Backend:** controladores públicos (catálogo, checkout), del estudiante (tomar curso con bloqueo secuencial real, exámenes con calificación automática, tareas) y del panel admin (cursos/módulos/lecciones, exámenes/preguntas, tareas/entregas, pagos, apariencia, usuarios). Autenticación nativa de Laravel (sin Breeze), con 3 roles y Gates de acceso.

**Vistas Blade:**
- **Públicas** — home (estilo claro tipo c3peru.com), catálogo, detalle de curso, checkout con Yape/Plin/cuenta bancaria, login/registro
- **Estudiante** — reproductor tipo Coursera (modo oscuro), examen, resultado de examen, tarea
- **Admin** — listado/creación/edición de cursos (con módulos, lecciones, exámenes y tareas integrados), preguntas de examen, entregas de tarea, pagos (aprobar/rechazar), apariencia, usuarios

## Próximos pasos para desplegar

El esqueleto de Laravel 13 (`composer.json`, `artisan`, `bootstrap/`, `config/`, `public/`, `storage/`) ya está incluido en el repo — no hace falta generarlo.

1. Clonar el repo y correr `composer install` (este entorno de desarrollo no tenía salida a Packagist, así que la instalación de dependencias debe hacerse en tu máquina o en el servidor)
2. Copiar `.env.example` a `.env`, configurar las credenciales de tu base de datos MySQL de Banahosting, y correr `php artisan key:generate`
3. `php artisan migrate` — crea las 15 tablas
4. `php artisan db:seed` — crea automáticamente el primer usuario **administrador** (`admin@eduplatform.test` / `cambiar-esta-clave` — **cámbiala de inmediato**) y algunos métodos de pago de ejemplo (Yape, Plin, cuenta bancaria) para editar desde la base de datos
5. `php artisan storage:link` — necesario para que imágenes/comprobantes subidos sean accesibles públicamente
6. En Banahosting (cPanel): apuntar el Document Root del dominio a la carpeta `/public`, y crear un Cron Job que corra `php artisan schedule:run` cada minuto
