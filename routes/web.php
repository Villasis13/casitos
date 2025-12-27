<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PortalusuariosController;
use App\Http\Controllers\IntranetController;
use App\Http\Controllers\Auth\GoogleController;

route::get('/phpinfo', function(){
    phpinfo();
});
// XDEBUG
/* ----------------------------- RUTAS CONTROLLER INTRANET ---------------------------------*/
route::get('/',[IntranetController::class ,'intranet'])->name('intranet')->middleware('verifyUserStatus')->middleware('auth');
//route::get('test',[IntranetController::class ,'test'])->name('test');
route::get('perfil',[IntranetController::class ,'perfil'])->name('intranet.perfil')->middleware('verifyUserStatus')->middleware('auth');
/* ----------------------------- FIN RUTAS CONTROLLER INTRANET ---------------------------------*/


/* ----------------------------- RUTAS DEL LOGIN ------------------------------*/
route::get('login',[LoginController::class ,'login'])->name('login');

/* ----------------------------- FIN RUTAS DEL LOGIN ------------------------------*/

/* ----------------------------- RUTAS DE CONFIGURACIÓN ---------------------------------*/
Route::prefix('configuracion')->middleware('auth')->group(function () {
    /* MENÚ */
    route::get('/menus',[ConfigurationController::class ,'menus'])->name('configuracion.menus')->middleware('verifyUserStatus')->middleware('can:menus');
    route::get('/submenu',[ConfigurationController::class ,'submenu'])->name('configuracion.submenu')->middleware('verifyUserStatus')->middleware('can:submenu');
    route::get('/usuarios',[ConfigurationController::class ,'usuarios'])->name('configuracion.usuarios')->middleware('verifyUserStatus')->middleware('can:usuarios');
    route::get('/roles',[ConfigurationController::class ,'roles'])->name('configuracion.roles')->middleware('verifyUserStatus')->middleware('can:roles');
    route::get('/iconos',[ConfigurationController::class ,'iconos'])->name('configuracion.iconos')->middleware('verifyUserStatus')->middleware('can:iconos');
    route::get('/empresas',[ConfigurationController::class ,'empresas'])->name('configuracion.empresas')->middleware('verifyUserStatus')->middleware('can:empresas');
});
/* ----------------------------- RUTAS FINALES DE CONFIGURACIÓN ---------------------------------*/

/* ----------------------------- RUTAS DE ADMIN ---------------------------------*/
Route::prefix('Admin')->middleware('auth')->group(function () {
    /* ADMIN */
    route::get('/carreras',[AdminController::class ,'carreras'])->name('Admin.carreras')->middleware('verifyUserStatus')->middleware('can:carreras');
    route::get('/especialidades',[AdminController::class ,'especialidades'])->name('Admin.especialidades')->middleware('verifyUserStatus')->middleware('can:especialidades');
    route::get('/categorias',[AdminController::class ,'categorias'])->name('Admin.categorias')->middleware('verifyUserStatus')->middleware('can:categorias');
    route::get('/casos',[AdminController::class ,'casos'])->name('Admin.casos')->middleware('verifyUserStatus')->middleware('can:casos');
    route::get('/etapas',[AdminController::class ,'etapas'])->name('Admin.etapas')->middleware('verifyUserStatus')->middleware('can:etapas');
});
/* ----------------------------- RUTAS FINALES DE ADMIN ---------------------------------*/


Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])
    ->name('auth.google.redirect');

Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->name('auth.google.callback');


/* ----------------------------- RUTAS DE PORTAL USUARIO ---------------------------------*/
Route::prefix('Portalusuarios')->middleware('auth')->group(function () {
    /* PORTAL USUARIO */
    route::get('/usuarios_vista',[PortalusuariosController::class ,'usuarios_vista'])->name('Portalusuarios.usuarios_vista')->middleware('verifyUserStatus')->middleware('can:usuarios_vista');
    route::get('/especialidades_users',[PortalusuariosController::class ,'especialidades_users'])->name('Portalusuarios.especialidades_users')->middleware('verifyUserStatus')->middleware('can:especialidades_users');
    route::get('/categorias_users',[PortalusuariosController::class ,'categorias_users'])->name('Portalusuarios.categorias_users')->middleware('verifyUserStatus')->middleware('can:categorias_users');
    route::get('/casos_users',[PortalusuariosController::class ,'casos_users'])->name('Portalusuarios.casos_users')->middleware('verifyUserStatus')->middleware('can:casos_users');
    route::get('/iniciar_caso',[PortalusuariosController::class ,'iniciar_caso'])->name('Portalusuarios.iniciar_caso')->middleware('verifyUserStatus')->middleware('can:iniciar_caso');
});
/* ----------------------------- RUTAS FINALES DE PORTAL USUARIO ---------------------------------*/
