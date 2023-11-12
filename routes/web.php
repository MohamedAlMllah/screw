<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('games', 'App\Http\Controllers\GameController');
    Route::post('/games/{game}/join', [App\Http\Controllers\GameController::class, 'join'])->name('join');
    Route::post('/games/{game}/leave', [App\Http\Controllers\GameController::class, 'leave'])->name('leave');
    Route::get('/games/{game}/summary', [App\Http\Controllers\GameController::class, 'summary'])->name('summary');

    Route::get('/games/{hand}/card', [App\Http\Controllers\HandController::class, 'ekshif'])->name('ekshif');
    Route::get('/games/{hand}/ermy', [App\Http\Controllers\HandController::class, 'ermy'])->name('ermy');
    Route::get('/games/{hand}/bsra', [App\Http\Controllers\HandController::class, 'bsra'])->name('bsra');
    Route::get('/games/{hand}/bdel', [App\Http\Controllers\HandController::class, 'bdel'])->name('bdel');
    Route::get('/games/{hand}/bdel-with/{myHand}', [App\Http\Controllers\HandController::class, 'bdelWith'])->name('bdelWith');
    Route::get('/games/{participant}/screw', [App\Http\Controllers\HandController::class, 'screw'])->name('screw');
    Route::get('/games/{hand}/end-skill', [App\Http\Controllers\HandController::class, 'endSkill'])->name('endSkill');
});
