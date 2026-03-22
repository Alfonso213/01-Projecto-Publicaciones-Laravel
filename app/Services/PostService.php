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
            ->withCount('likes')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Crea una nueva publicación para un usuario.
     */
    public function createPost($user, array $data): Post
    {
        return $user->posts()->create($data);
    }
}
