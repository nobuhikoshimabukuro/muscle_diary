<?php

use Illuminate\Support\Facades\Route;

// user
use App\Http\Controllers\user\main_controller;
use App\Http\Controllers\user\login_controller;
use App\Http\Controllers\user\dashboard_controller;
use App\Http\Controllers\user\weight_log_controller;
use App\Http\Controllers\user\training_controller;
use App\Http\Controllers\user\user_controller;

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

Route::get('/user/login', [login_controller::class, 'login'])->name('user.login');
Route::post('/user/login_check', [login_controller::class, 'login_check'])->name('user.login_check');

Route::get('/user/logout', [login_controller::class, 'logout'])->name('user.logout');
Route::get('/user/top', [user_controller::class, 'top'])->name('user.top');




Route::get('/training', [training_controller::class, 'index'])->name('user.training.index');
Route::get('/training/detail', [training_controller::class, 'detail'])->name('user.training.detail');
Route::get('/training/analysis', [training_controller::class, 'analysis'])->name('user.training.analysis');

Route::post('/training/training_history_save', [training_controller::class, 'training_history_save'])->name('user.training_history.save');
Route::post('/training/training_detail_save', [training_controller::class, 'training_detail_save'])->name('user.training_detail.save');

Route::get('/training/record_sheet', [training_controller::class, 'record_sheet'])->name('user.training.record_sheet');


Route::get('/graph_test', [dashboard_controller::class, 'graph_test'])->name('user.dashboard.graph_test');


Route::get('/exercise_m', [exercise_m_controller::class, 'index'])->name('user.exercise_m.index');
Route::post('/exercise_m/save', [exercise_m_controller::class, 'save'])->name('user.exercise_m.save');

Route::get('/gym_m', [gym_m_controller::class, 'index'])->name('user.gym_m.index');
Route::post('/gym_m/save', [gym_m_controller::class, 'save'])->name('user.gym_m.save');


Route::get('/weight_log', [weight_log_controller::class, 'index'])->name('user.weight_log.index');
Route::post('/weight_log/save', [weight_log_controller::class, 'save'])->name('user.weight_log.save');
