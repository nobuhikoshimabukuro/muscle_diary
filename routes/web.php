<?php

use Illuminate\Support\Facades\Route;

// web
use App\Http\Controllers\web\main_controller;
use App\Http\Controllers\web\dashboard_controller;

use App\Http\Controllers\table\weight_log_t_controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [main_controller::class, 'index'])->name('web.index');

Route::get('/graph_test', [dashboard_controller::class, 'graph_test'])->name('dashboard.graph_test');

Route::get('/weight_log', [weight_log_t_controller::class, 'index'])->name('web.weight_log.index');
Route::post('/weight_log/save', [weight_log_t_controller::class, 'save'])->name('web.weight_log.save');