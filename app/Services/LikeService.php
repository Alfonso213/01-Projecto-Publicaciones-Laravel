<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class LikeService
{
    /**
     * Alterna el estado de un Like de forma polimórfica.
     * Acepta cualquier Modelo (Post o Comment).
     */
    public function toggleLike(Model $model, int $userId): array
    {
        $like = $model->likes()->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $model->likes()->create(['user_id' => $userId]);
            $liked = true;
        }

        return [
            'liked' => $liked,
            'likesCount' => $model->likes()->count(),
            'message' => $liked ? 'Like agregado' : 'Like removido'
        ];
    }
}
