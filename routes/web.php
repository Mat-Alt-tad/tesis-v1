<?php

use App\Http\Controllers\AporteController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\LectorController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\Admin\AuditoriaController;
use App\Http\Controllers\Admin\CategoriaAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ModeracionController;
use App\Http\Controllers\Admin\PlantaAdminController;
use App\Http\Controllers\Admin\SubtemaAdminController;
use App\Http\Controllers\Admin\UsuarioAdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/* ══════════════════════════════════════════════
   RUTAS PÚBLICAS
══════════════════════════════════════════════ */
Route::get('/', [CatalogoController::class, 'home'])->name('home');

Route::get('/creditos', fn() => view('public.creditos'))->name('creditos');

Route::get('/catalogo', [CatalogoController::class, 'catalogo'])->name('catalogo');

Route::get('/categorias/{categoria}', [CatalogoController::class, 'categoria'])
    ->name('categorias.show');

Route::get('/categorias/{categoria}/todos', [CatalogoController::class, 'categoriaTodos'])
    ->name('categorias.todos');

Route::get('/categorias/{categoria}/subtemas/{subtema}', [CatalogoController::class, 'subtema'])
    ->name('subtemas.show');

Route::get('/plantas/{planta}', [CatalogoController::class, 'ficha'])
    ->name('plantas.show');

Route::get('/buscar', [CatalogoController::class, 'buscar'])
    ->name('plantas.buscar');

// Detalle público de un aporte aprobado
Route::get('/aportes/{aporte}', [AporteController::class, 'show'])->name('aportes.show');

// Comentarios — store requiere auth (manejado en el controller)
Route::post('/comentarios',                   [ComentarioController::class, 'store'])->middleware('auth')->name('comentarios.store');
Route::post('/comentarios/{comentario}/like', [ComentarioController::class, 'like'])->name('comentarios.like');

// Formulario aportar (solo rol lector — auth implícito en role middleware de Spatie)
Route::middleware(['auth', 'role:lector'])->group(function () {
    Route::get('/aportar',  [AporteController::class, 'create'])->name('aportar');
    Route::post('/aportar', [AporteController::class, 'store'])->name('aportar.store');
});

// Dashboard lector
Route::middleware(['auth', 'role:lector'])->prefix('mis-aportes')->name('lector.')->group(function () {
    Route::get('/',                [LectorController::class, 'dashboard'])->name('dashboard');
    Route::get('/{aporte}/editar', [LectorController::class, 'edit'])->name('aporte.edit');
    Route::put('/{aporte}',        [LectorController::class, 'update'])->name('aporte.update');
});

// Notificaciones (auth)
Route::middleware('auth')->prefix('notificaciones')->name('notificaciones.')->group(function () {
    Route::get('/',                        [NotificacionController::class, 'index'])->name('index');
    Route::post('/marcar-leidas',          [NotificacionController::class, 'marcarLeidas'])->name('leidas');
    Route::post('/{notificacion}/leida',   [NotificacionController::class, 'marcarUna'])->name('una');
});

// Perfil (auth)
Route::middleware('auth')->prefix('perfil')->name('perfil.')->group(function () {
    Route::get('/change-password', [ProfileController::class, 'showChangePassword'])->name('show-change-password');
    Route::patch('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
});

/* ══════════════════════════════════════════════
   RUTAS ADMIN (autenticadas + rol admin/moderador)
══════════════════════════════════════════════ */
Route::middleware(['auth', 'role:admin|moderador'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard (solo admin y moderador)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Plantas CRUD
    Route::resource('plantas', PlantaAdminController::class)->except(['show']);
    Route::get('categorias/{categoria}/subtemas', [PlantaAdminController::class, 'subtemasPorCategoria'])
        ->name('plantas.subtemas');

    // Moderación
    Route::get('moderacion', [ModeracionController::class, 'index'])->name('moderacion.index');
    Route::patch('moderacion/{aporte}/aprobar',  [ModeracionController::class, 'aprobar'])->name('moderacion.aprobar');
    Route::post('moderacion/{aporte}/rechazar',  [ModeracionController::class, 'rechazar'])->name('moderacion.rechazar');
    Route::delete('moderacion/{aporte}', [ModeracionController::class, 'destroy'])->name('moderacion.destroy');

    // Auditoría y Reportes
    Route::get('auditoria', [AuditoriaController::class, 'index'])->name('auditoria');
    Route::get('reportes',  [AuditoriaController::class, 'reportes'])->name('reportes');

    // Categorías
    Route::resource('categorias', CategoriaAdminController::class)->except(['show']);

    // Subtemas
    Route::resource('subtemas', SubtemaAdminController::class)->except(['show', 'create', 'edit']);

    // Usuarios
    Route::resource('usuarios', UsuarioAdminController::class)->except(['show']);
    Route::patch('usuarios/{usuario}/activar', [UsuarioAdminController::class, 'activar'])->name('usuarios.activar');
    Route::patch('usuarios/{usuario}/change-password', [UsuarioAdminController::class, 'changePassword'])->name('usuarios.change-password');

    // Configuración
    Route::get('config', fn() => view('admin.config'))->name('config');
});

/* ══════════════════════════════════════════════
   AUTH (Breeze / Fortify las genera automático,
   pero se deja el login manual por si acaso)
══════════════════════════════════════════════ */
require __DIR__.'/auth.php';
