<?php

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Portfolio Work & Detail routes
Route::get('/work', [ProjectController::class, 'index'])->name('work.index');
Route::get('/work/{category:slug}', [ProjectController::class, 'index'])->name('work.category');
Route::get('/work/{category}/{project:slug}', [ProjectController::class, 'show'])->name('work.show');
Route::get('/project/{project:slug}', [ProjectController::class, 'showDirect'])->name('project.show');

