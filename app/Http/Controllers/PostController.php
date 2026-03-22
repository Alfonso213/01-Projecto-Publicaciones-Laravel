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

    /**
     * Constructor: Inyecta los servicios de dominio (PostService, LikeService)
     * para separar la lógica de negocio compleja del flujo HTTP.
     */
    public function __construct(PostService $postService, LikeService $likeService)
    {
        $this->postService = $postService;
        $this->likeService = $likeService;
    }

    /**
     * Muestra el muro principal (Dashboard) cargando los posts recientes.
     */
    public function index()
    {
        $posts = $this->postService->getLatestPosts();
        return view('posts.index', compact('posts'));
    }

    /**
     * Valida y almacena una nueva publicación, relacionándola con el usuario activo.
     */
    public function store(Request $request)
    {
        $request->validate(['body' => 'required']);
        $this->postService->createPost($request->user(), $request->only('body'));
        
        return back()->with('status', 'Publicación guardada exitosamente');
    }

    /**
     * Endpoint API (vía AlpineJS): Alterna (crea o elimina) el Like del usuario actual en un Post.
     * Retorna JSON con el conteo actualizado y el estado de la acción.
     */
    public function like(Post $post)
    {
        $result = $this->likeService->toggleLike($post, auth()->id());
        return response()->json($result);
    }

    /**
     * Muestra la vista de detalle de una sola publicación y sus comentarios.
     */
    public function show($id)
    {
        $post = $this->postService->getPostById($id);
        return view('posts.show', compact('post'));
    }

    /**
     * Elimina el Post. Utiliza "Gate::authorize" y el "PostPolicy" para
     * bloquear el borrado a un usuario que no sea el autor original.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);
        $post->delete();
        return back();
    }
}
