<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
           // Genera un texto aleatorio para el cuerpo del post
            'body' => $this->faker->paragraph(),
            
            // Esto asocia el post a un usuario (si no existe uno, lo crea)
            'user_id' => \App\Models\User::factory(), 
            
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
