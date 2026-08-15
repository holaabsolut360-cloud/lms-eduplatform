<?php

use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\AlumnoController;
use App\Http\Controllers\Admin\AparienciaController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\ClaseEnVivoController;
use App\Http\Controllers\Admin\CursoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExamenController as AdminExamenController;
use App\Http\Controllers\Admin\LeccionController;
use App\Http\Controllers\Admin\MetodoPagoController;
use App\Http\Controllers\Admin\ModuloController;
use App\Http\Controllers\Admin\OrdenController;
use App\Http\Controllers\Admin\PreguntaController;
use App\Http\Controllers\Admin\TareaController as AdminTareaController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Estudiante\CertificadoController;
use App\Http\Controllers\Estudiante\ExamenController as EstudianteExamenController;
use App\Http\Controllers\Estudiante\TareaController as EstudianteTareaController;
use App\Http\Controllers\Estudiante\TomarCursoController;
use App\Http\Controllers\VerificarCertificadoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas públicas (sin login)
|--------------------------------------------------------------------------
*/
Route::get('/', [CatalogoController::class, 'home'])->name('publico.home');
Route::get('/cursos', [CatalogoController::class, 'buscar'])->name('publico.catalogo');
Route::get('/cursos/{curso:slug}', [CatalogoController::class, 'detalle'])->name('publico.curso.detalle');
Route::get('/verificar-certificado/{codigo?}', [VerificarCertificadoController::class, 'mostrar'])->name('publico.certificado.verificar');

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/ingresar', [LoginController::class, 'mostrar'])->name('login');
    Route::post('/ingresar', [LoginController::class, 'iniciarSesion'])->name('login.attempt');
    Route::get('/registro', [RegisterController::class, 'mostrar'])->name('registro');
    Route::post('/registro', [RegisterController::class, 'registrar'])->name('registro.attempt');
});

Route::middleware('auth')->post('/salir', [LoginController::class, 'cerrarSesion'])->name('logout');

/*
|--------------------------------------------------------------------------
| Checkout (requiere estar logueado, cualquier rol)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/cursos/{curso:slug}/comprar', [CheckoutController::class, 'mostrar'])->name('publico.checkout');
    Route::post('/cursos/{curso:slug}/comprar', [CheckoutController::class, 'confirmar'])->name('publico.checkout.confirmar');
    Route::get('/ordenes/{orden}/gracias', [CheckoutController::class, 'gracias'])->name('publico.checkout.gracias');
});

/*
|--------------------------------------------------------------------------
| Panel del estudiante (tomar curso, examenes, tareas)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('mis-cursos')->name('estudiante.')->group(function () {
    Route::get('/{curso:slug}', [TomarCursoController::class, 'index'])->name('curso.index');
    Route::get('/{curso:slug}/leccion/{leccion}', [TomarCursoController::class, 'verLeccion'])->name('curso.leccion');
    Route::post('/{curso:slug}/leccion/{leccion}/completar', [TomarCursoController::class, 'marcarCompletada'])->name('curso.completar');

    Route::get('/{curso:slug}/examen/{examen}', [EstudianteExamenController::class, 'mostrar'])->name('examen.mostrar');
    Route::post('/{curso:slug}/examen/{examen}', [EstudianteExamenController::class, 'enviar'])->name('examen.enviar');
    Route::get('/{curso:slug}/examen/{examen}/resultado/{intento}', [EstudianteExamenController::class, 'resultado'])->name('examen.resultado');

    Route::get('/{curso:slug}/tarea/{tarea}', [EstudianteTareaController::class, 'mostrar'])->name('tarea.mostrar');
    Route::post('/{curso:slug}/tarea/{tarea}', [EstudianteTareaController::class, 'entregar'])->name('tarea.entregar');

    Route::get('/{curso:slug}/certificado', [CertificadoController::class, 'mostrar'])->name('certificado.mostrar');
    Route::get('/{curso:slug}/certificado/descargar', [CertificadoController::class, 'descargar'])->name('certificado.descargar');
});

/*
|--------------------------------------------------------------------------
| Panel de administración (instructor / dueño de la plataforma)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:administrar-plataforma'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('agenda', [AgendaController::class, 'index'])->name('agenda.index');

    Route::get('alumnos', [AlumnoController::class, 'index'])->name('alumnos.index');
    Route::get('alumnos/{alumno}', [AlumnoController::class, 'show'])->name('alumnos.show');
    Route::post('alumnos/{alumno}/notas', [AlumnoController::class, 'storeNota'])->name('alumnos.notas.store');
    Route::delete('alumnos/{alumno}/notas/{nota}', [AlumnoController::class, 'destroyNota'])->name('alumnos.notas.destroy');

    Route::resource('cursos', CursoController::class);
    Route::post('cursos/{curso}/publicar', [CursoController::class, 'publicar'])->name('cursos.publicar');
    Route::get('cursos/{curso}/alumnos', [CursoController::class, 'alumnos'])->name('cursos.alumnos');

    Route::post('cursos/{curso}/clases-vivo', [ClaseEnVivoController::class, 'store'])->name('clases-vivo.store');
    Route::put('cursos/{curso}/clases-vivo/{clase}', [ClaseEnVivoController::class, 'update'])->name('clases-vivo.update');
    Route::delete('cursos/{curso}/clases-vivo/{clase}', [ClaseEnVivoController::class, 'destroy'])->name('clases-vivo.destroy');

    Route::post('cursos/{curso}/modulos', [ModuloController::class, 'store'])->name('modulos.store');
    Route::put('cursos/{curso}/modulos/{modulo}', [ModuloController::class, 'update'])->name('modulos.update');
    Route::post('cursos/{curso}/modulos/reordenar', [ModuloController::class, 'reordenar'])->name('modulos.reordenar');
    Route::delete('cursos/{curso}/modulos/{modulo}', [ModuloController::class, 'destroy'])->name('modulos.destroy');

    Route::post('modulos/{modulo}/lecciones', [LeccionController::class, 'store'])->name('lecciones.store');
    Route::put('modulos/{modulo}/lecciones/{leccion}', [LeccionController::class, 'update'])->name('lecciones.update');
    Route::delete('modulos/{modulo}/lecciones/{leccion}', [LeccionController::class, 'destroy'])->name('lecciones.destroy');

    Route::post('cursos/{curso}/examenes', [AdminExamenController::class, 'store'])->name('examenes.store');
    Route::get('cursos/{curso}/examenes/{examen}', [AdminExamenController::class, 'edit'])->name('examenes.edit');
    Route::delete('cursos/{curso}/examenes/{examen}', [AdminExamenController::class, 'destroy'])->name('examenes.destroy');
    Route::post('examenes/{examen}/preguntas', [PreguntaController::class, 'store'])->name('preguntas.store');
    Route::delete('examenes/{examen}/preguntas/{pregunta}', [PreguntaController::class, 'destroy'])->name('preguntas.destroy');

    Route::post('cursos/{curso}/tareas', [AdminTareaController::class, 'store'])->name('tareas.store');
    Route::get('cursos/{curso}/tareas/{tarea}/entregas', [AdminTareaController::class, 'entregas'])->name('tareas.entregas');
    Route::post('cursos/{curso}/tareas/{tarea}/entregas/{entrega}/calificar', [AdminTareaController::class, 'calificar'])->name('tareas.calificar');
    Route::delete('cursos/{curso}/tareas/{tarea}', [AdminTareaController::class, 'destroy'])->name('tareas.destroy');

    Route::get('categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::post('categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::put('categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');

    Route::get('metodos-pago', [MetodoPagoController::class, 'index'])->name('metodos-pago.index');
    Route::post('metodos-pago', [MetodoPagoController::class, 'store'])->name('metodos-pago.store');
    Route::put('metodos-pago/{metodo}', [MetodoPagoController::class, 'update'])->name('metodos-pago.update');
    Route::post('metodos-pago/{metodo}/toggle', [MetodoPagoController::class, 'toggle'])->name('metodos-pago.toggle');
    Route::delete('metodos-pago/{metodo}', [MetodoPagoController::class, 'destroy'])->name('metodos-pago.destroy');

    Route::get('apariencia', [AparienciaController::class, 'editar'])->name('apariencia.editar');
    Route::put('apariencia', [AparienciaController::class, 'actualizar'])->name('apariencia.actualizar');

    Route::get('pagos', [OrdenController::class, 'index'])->name('pagos.index');
    Route::post('pagos/{orden}/aprobar', [OrdenController::class, 'aprobar'])->name('pagos.aprobar');
    Route::post('pagos/{orden}/rechazar', [OrdenController::class, 'rechazar'])->name('pagos.rechazar');

    Route::get('usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::post('usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::post('usuarios/{usuario}/desactivar', [UsuarioController::class, 'desactivar'])->name('usuarios.desactivar');
});
