<?php
namespace App\Services;
use App\Events\PostLiked;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
class LikeService
{
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
        $likesCount = $model->likes()->count();
        // Si el modelo es un Post, disparamos evento de tiempo real
        if ($model instanceof Post) {
            broadcast(new PostLiked($model, $likesCount))->toOthers();
        }
        return [
            'liked' => $liked,
            'likesCount' => $likesCount,
            'message' => $liked ? 'Like agregado' : 'Like removido'
        ];
    }
}
