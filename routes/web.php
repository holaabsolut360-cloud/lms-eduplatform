<?php

use App\Http\Controllers\Admin\CursoController;
use App\Http\Controllers\Admin\LeccionController;
use App\Http\Controllers\Admin\ModuloController;
use App\Http\Controllers\Admin\OrdenController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Estudiante\TomarCursoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas públicas (sin login)
|--------------------------------------------------------------------------
*/
Route::get('/', [CatalogoController::class, 'home'])->name('publico.home');
Route::get('/cursos', [CatalogoController::class, 'buscar'])->name('publico.catalogo');
Route::get('/cursos/{curso:slug}', [CatalogoController::class, 'detalle'])->name('publico.curso.detalle');

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
| Panel del estudiante (tomar curso)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('mis-cursos')->name('estudiante.')->group(function () {
    Route::get('/{curso:slug}', [TomarCursoController::class, 'index'])->name('curso.index');
    Route::get('/{curso:slug}/leccion/{leccion}', [TomarCursoController::class, 'verLeccion'])->name('curso.leccion');
    Route::post('/{curso:slug}/leccion/{leccion}/completar', [TomarCursoController::class, 'marcarCompletada'])->name('curso.completar');
});

/*
|--------------------------------------------------------------------------
| Panel de administración (instructor / dueño de la plataforma)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:administrar-plataforma'])->prefix('admin')->name('admin.')->group(function () {

    Route::resource('cursos', CursoController::class);
    Route::post('cursos/{curso}/publicar', [CursoController::class, 'publicar'])->name('cursos.publicar');

    Route::post('cursos/{curso}/modulos', [ModuloController::class, 'store'])->name('modulos.store');
    Route::put('cursos/{curso}/modulos/{modulo}', [ModuloController::class, 'update'])->name('modulos.update');
    Route::post('cursos/{curso}/modulos/reordenar', [ModuloController::class, 'reordenar'])->name('modulos.reordenar');
    Route::delete('cursos/{curso}/modulos/{modulo}', [ModuloController::class, 'destroy'])->name('modulos.destroy');

    Route::post('modulos/{modulo}/lecciones', [LeccionController::class, 'store'])->name('lecciones.store');
    Route::put('modulos/{modulo}/lecciones/{leccion}', [LeccionController::class, 'update'])->name('lecciones.update');
    Route::delete('modulos/{modulo}/lecciones/{leccion}', [LeccionController::class, 'destroy'])->name('lecciones.destroy');

    Route::get('pagos', [OrdenController::class, 'index'])->name('pagos.index');
    Route::post('pagos/{orden}/aprobar', [OrdenController::class, 'aprobar'])->name('pagos.aprobar');
    Route::post('pagos/{orden}/rechazar', [OrdenController::class, 'rechazar'])->name('pagos.rechazar');
});
