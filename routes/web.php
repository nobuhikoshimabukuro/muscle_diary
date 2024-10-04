<?php

use Illuminate\Support\Facades\Route;

// user
use App\Http\Controllers\user\main_controller;
use App\Http\Controllers\user\login_controller;
use App\Http\Controllers\user\dashboard_controller;
use App\Http\Controllers\user\weight_log_t_controller;
use App\Http\Controllers\user\training_controller;

use App\Http\Controllers\user\master\exercise_m_controller;
use App\Http\Controllers\user\master\gym_m_controller;
use App\Http\Controllers\user\master\user_m_controller;


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

Route::get('/', [main_controller::class, 'index'])->name('user.index');

Route::get('/login', [login_controller::class, 'login'])->name('user.login');
Route::post('/login_check', [login_controller::class, 'login_check'])->name('user.login_check');


Route::get('/training', [training_controller::class, 'index'])->name('user.training.index');


Route::get('/graph_test', [dashboard_controller::class, 'graph_test'])->name('user.dashboard.graph_test');

Route::get('/weight_log', [weight_log_t_controller::class, 'index'])->name('user.weight_log.index');
Route::post('/weight_log/save', [weight_log_t_controller::class, 'save'])->name('user.weight_log.save');
