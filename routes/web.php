<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

Route::redirect('/','dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('dashboard', [PostController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::post('posts', [PostController::class,'store'])->middleware(['auth', 'verified'])->name('posts.store');
Route::delete('posts/{post}', [PostController::class,'destroy'])->middleware(['auth', 'verified'])->name('posts.destroy');

Route::get('user/{user}', [UserController::class,'show'])->middleware(['auth', 'verified'])->name('users.show');



require __DIR__.'/auth.php';
