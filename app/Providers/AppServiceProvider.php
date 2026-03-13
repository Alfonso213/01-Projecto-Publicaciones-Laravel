<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Policies\PostPolicy;

class AppServiceProvider extends ServiceProvider
{

    protected $policies = [
    Post::class => PostPolicy::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
        // Definición del Gate para eliminar posts
        Gate::define('destroy-post', function (User $user, Post $post) {
            // Usamos == para comparar el ID del usuario logueado 
            // con el ID del dueño del post
            return $user->id == $post->user_id;
        });
        */
    }
}