<?php
namespace App\Services;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostService
{
    /**
     * Obtiene los posts con sus relaciones y contadores necesarios.
     */
    public function getLatestPosts(int $perPage = 15): LengthAwarePaginator
    {
        return Post::with(['user', 'comments.user'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Obtiene un post específico por ID con sus relaciones.
     */
    public function getPostById(int $id): Post
    {
        return Post::with(['user', 'comments.user'])
            ->withCount(['likes', 'comments'])
            ->findOrFail($id);
    }

    /**
     * Crea una nueva publicación para un usuario.
     */
    public function createPost($user, array $data): Post
    {
        return $user->posts()->create($data);
    }
}
