<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Generar más datos para un número masivo de likes
        User::factory(30)
            ->hasPosts(10)
            ->create();

        $users = User::all();
        $posts = Post::all();
        
        // Calcular máximo posible de likes
        $maxLikes = $users->count() * $posts->count();
        
        // Generar número masivo de likes (50-70% del máximo)
        $totalLikes = mt_rand(
            intval($maxLikes * 0.5),  // 50% del máximo
            intval($maxLikes * 0.7)   // 70% del máximo
        );

        // Distribuir likes aleatorios
        $createdLikes = 0;
        while ($createdLikes < $totalLikes) {
            $user = $users->random();
            $post = $posts->random();
    
            // Crear like solo si no existe (evita duplicados)
            $like = $user->likes()->firstOrCreate(['post_id' => $post->id]);
            if ($like->wasRecentlyCreated) {
                $createdLikes++;
            }
        }

        // Usuario de prueba
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}
