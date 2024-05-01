<?php

use App\Livewire\CreateEmail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', CreateEmail::class);
Route::get('/artisan', function () {
  // Limpiar la caché de configuración
  Artisan::call('config:cache');

  // Limpiar la configuración almacenada en caché
  Artisan::call('config:clear');
  Artisan::call('storage:link');

  return 'comandos ejecutados.';
});
