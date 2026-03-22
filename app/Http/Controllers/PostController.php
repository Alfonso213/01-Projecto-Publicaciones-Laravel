<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\PostService;
use App\Services\LikeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    protected $postService;
    protected $likeService;

    // Inyección de dependencias en el constructor
    public function __construct(PostService $postService, LikeService $likeService)
    {
        $this->postService = $postService;
        $this->likeService = $likeService;
    }

    public function index()
    {
        $posts = $this->postService->getLatestPosts();
        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate(['body' => 'required']);
        $this->postService->createPost($request->user(), $request->only('body'));
        
        return back()->with('status', 'Publicación guardada exitosamente');
    }

    public function like(Post $post)
    {
        $result = $this->likeService->toggleLike($post, auth()->id());
        return response()->json($result);
    }

    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);
        $post->delete();
        return back();
    }
}

