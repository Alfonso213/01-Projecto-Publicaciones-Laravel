<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Services\LikeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected $likeService;

    /**
     * Inyecta LikeService, reutilizando la lógica polimórfica preexistente
     * para alternar 'Me gustas' en comentarios al igual que se hace con los posts.
     */
    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    /**
     * Endpoint API (vía AlpineJS): Alterna (da/quita) Like a un comentario.
     */
    public function like(Comment $comment)
    {
        $result = $this->likeService->toggleLike($comment, Auth::id());
        
        return response()->json($result);
    }

    /**
     * Valida y persiste un nuevo comentario en la base de datos asociado a un Post.
     * Soporta repuestas tipo JSON (para AlpineJS) y tipo Web clásica (redirección).
     */
    public function store(Request $request)
    {
        // Validación de cuerpo (body) y existencia del post al que pertenece
        $request->validate([
            'body' => 'required',
            'post_id' => 'required|exists:posts,id'
        ]);

        // Creación del comentario con todas sus relaciones
        $request->user()->comments()->create([
            'body' => $request->body,
            'post_id' => $request->post_id
        ]);

        // Devolvemos JSON para JS integrado
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Comentario guardado']);
        }
        return back()->with('status', 'Comentario guardado exitosamente');
    }

    /**
     * Elimina el comentario utilizando confirmación de capa de seguridad (Policy).
     * Asegura que sólo el autor (o administradores autorizados) puedan borrarlo.
     */
    public function destroy(Request $request, Comment $comment)
    {
        \Illuminate\Support\Facades\Gate::authorize('delete', $comment);
        $comment->delete();
        return back();
    }
}
