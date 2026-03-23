<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class BulkSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Aumentamos el límite de memoria por si acaso
        ini_set('memory_limit', '512M');

        // 1. Usuarios (Solo guardamos los IDs)
        User::factory(30)->create();
        $userIds = User::pluck('id');

        // 2. Posts (Solo guardamos los IDs)
        Post::factory(300)->recycle(User::all())->create(); 
        $postIds = Post::pluck('id');

        $now = now();
        
        // 3. Comentarios (Insertar por bloques y no guardar en memoria)
        foreach (array_chunk($postIds->toArray(), 50) as $chunkPostIds) {
            $commentsData = [];
            foreach ($chunkPostIds as $postId) {
                for ($i = 0; $i < 3; $i++) {
                    $commentsData[] = [
                        'body' => 'Comentario del post ' . $postId . ' - ' . $i,
                        'post_id' => $postId,
                        'user_id' => $userIds->random(),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
            Comment::insert($commentsData);
        }

        // 4. Likes de Posts (Insertar por bloques)
        foreach (array_chunk($postIds->toArray(), 50) as $chunkPostIds) {
            $likesData = [];
            foreach ($chunkPostIds as $postId) {
                $likers = $userIds->random(mt_rand(5, 15));
                foreach ($likers as $uid) {
                    $likesData[] = [
                        'user_id' => $uid,
                        'likeable_id' => $postId,
                        'likeable_type' => Post::class,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
            Like::insert($likesData);
        }

        // 5. Likes de Comentarios (Usamos IDs de comentarios por bloques)
        Comment::chunk(500, function ($comments) use ($userIds, $now) {
            $likesData = [];
            foreach ($comments as $comment) {
                $likers = $userIds->random(mt_rand(2, 6));
                foreach ($likers as $uid) {
                    $likesData[] = [
                        'user_id' => $uid,
                        'likeable_id' => $comment->id,
                        'likeable_type' => Comment::class,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
            Like::insert($likesData);
        });

        // 6. Usuario de prueba
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);
    }
}

