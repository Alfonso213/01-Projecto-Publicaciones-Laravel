<?php
namespace App\Services;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostService
{
    /**
     * Extrae al vuelo el volumen estadístico de (likes y comentarios) con "withCount",
     * y precarga los datos en lote ("with") para optimizar las vistas (Muro y Tendencias).
     */
    public function getLatestPosts(int $perPage = 15): LengthAwarePaginator
    {
        return Post::with(['user', 'comments.user'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Idéntico propósito que "getLatestPosts" pero forjado para 
     * traer una única vista detallada con estadísticas y respuestas precargadas.
     */
    public function getPostById(int $id): Post
    {
        return Post::with(['user', 'comments.user'])
            ->withCount(['likes', 'comments'])
            ->findOrFail($id);
    }

    /**
     * Aisla la persistencia de un nuevo elemento Post en base de datos.
     */
    public function createPost($user, array $data): Post
    {
        return $user->posts()->create($data);
    }
}
