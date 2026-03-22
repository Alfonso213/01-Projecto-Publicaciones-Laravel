<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    User::factory(30)->create();
    $users = User::all();
    // 1. Generar Posts
    $users->each(fn($u) => Post::factory(10)->create(['user_id' => $u->id]));
    $posts = Post::all();
    // 2. Generar Comentarios (3 por post) con 4-12 likes cada uno
    $posts->each(function ($post) use ($users) {
        $comments = Comment::factory(3)->create(['post_id' => $post->id, 'user_id' => $users->random()->id]);
        $comments->each(function ($comment) use ($users) {
            $numLikes = mt_rand(4, 12);
            $randomUsers = $users->random($numLikes);
            foreach ($randomUsers as $u) {
                $comment->likes()->firstOrCreate(['user_id' => $u->id]);
            }
        });
    });
    // 3. Restaurar Likes MASIVOS para Posts (50-70% de saturación)
    $maxLikes = $users->count() * $posts->count();
    $totalLikes = mt_rand(intval($maxLikes * 0.5), intval($maxLikes * 0.7));
    $createdLikes = 0;
    while ($createdLikes < $totalLikes) {
        $user = $users->random();
        $post = $posts->random();
        $like = $post->likes()->firstOrCreate(['user_id' => $user->id]);
        if ($like->wasRecentlyCreated) $createdLikes++;
    }
    User::factory()->create(['name' => 'Test User', 'email' => 'test@example.com', 'password' => bcrypt('password')]);
}
}
