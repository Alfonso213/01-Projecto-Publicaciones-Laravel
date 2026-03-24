<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PostService;
use App\Http\Requests\StorePostRequest; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService) {
        $this->postService = $postService;
    }

    public function index(Request $request) {
        if ($request->routeIs('trending')) {
            $data = [
                'posts' => $this->postService->getTrendingPosts(),
                'trendingComments' => $this->postService->getTrendingComments()
            ];
            return view('posts.trending', $data);
        }
        return view('posts.index', ['posts' => $this->postService->getLatestPosts()]);
    }

    public function store(StorePostRequest $request) {
        // Al entrar aquí, los datos ya están validados automáticamente
        $this->postService->createPost($request->user(), $request->validated());

        return back()->with('status', 'Publicación creada exitosamente');
    }
    //Se añade los request reitando la logica de validacion del controlador
    public function like(Post $post, Request $request) {
        $likeService = app(\App\Services\LikeService::class);
        $result = $likeService->toggleLike($post, $request->user()->id);
        return response()->json($result);
    }

    public function destroy(Post $post) {
        Gate::authorize('delete', $post);
        $post->delete();
        return back()->with('status', 'Publicación eliminada');
    }
        /**
     * Muestra la vista de detalle de una sola publicación y sus comentarios.
     */
    public function show($id)
    {
        $post = $this->postService->getPostById($id);
        return view('posts.show', compact('post'));
    }

    
}

