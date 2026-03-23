<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Usuarios base
        User::factory(30)->create();
        $users = User::all();

        // 2. Posts aleatorios
        Post::factory(300)->create([
            'user_id' => fn() => $users->random()->id
        ]);
        $posts = Post::all();

        // 3. Comentarios con sus Likes (Optimizado)
        $posts->each(function ($post) use ($users) {
            foreach (range(1, 3) as $i) {
                $comment = Comment::factory()->create([
                    'post_id' => $post->id, 
                    'user_id' => $users->random()->id
                ]);

                // Generar likes para el comentario
                $numLikes = mt_rand(4, 12);
                $likers = $users->random($numLikes);
                foreach ($likers as $u) {
                    $comment->likes()->create(['user_id' => $u->id]);
                }
            }
        });

        // 4. Likes MASIVOS para Posts (Estrategia de flujo mejorada)
        // Evitamos el bucle infinito y validamos unicidad antes de insertar
        $posts->each(function ($post) use ($users) {
            $numLikes = mt_rand(intval($users->count() * 0.4), intval($users->count() * 0.7));
            $likers = $users->random($numLikes);
            
            foreach ($likers as $u) {
                // Usamos create en lugar de firstOrCreate por rendimiento al ser datos nuevos
                $post->likes()->create(['user_id' => $u->id]);
            }
        });

        User::factory()->create([
            'name' => 'Test User', 
            'email' => 'test@example.com', 
            'password' => bcrypt('password')
        ]);
    }
}

