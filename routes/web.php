<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;

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
Route::get('trending', [PostController::class,'index'])->middleware(['auth', 'verified'])->name('trending');
Route::post('posts', [PostController::class,'store'])->middleware(['auth', 'verified'])->name('posts.store');
Route::delete('posts/{post}', [PostController::class,'destroy'])->middleware(['auth', 'verified'])->name('posts.destroy');

Route::get('posts/{post}', [PostController::class,'show'])->middleware(['auth', 'verified'])->name('posts.show');
Route::post('posts/{post}/likes', [PostController::class,'like'])->middleware(['auth', 'verified'])->name('posts.like');
Route::get('user/{user}', [UserController::class,'show'])->middleware(['auth', 'verified'])->name('users.show');

Route::post('comments', [CommentController::class,'store'])->middleware(['auth', 'verified'])->name('comments.store');
Route::delete('comments/{comment}', [CommentController::class,'destroy'])->middleware(['auth', 'verified'])->name('comments.destroy');

Route::post('comments/{comment}/likes', [CommentController::class, 'like'])->middleware(['auth', 'verified'])->name('comments.like');

require __DIR__.'/auth.php';
