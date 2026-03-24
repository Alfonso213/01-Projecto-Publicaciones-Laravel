<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Events\CommentCreated;
use App\Http\Requests\StoreCommentRequest;
use App\Services\LikeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    protected $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    /**
     * Almacena un comentario usando validación externa (FormRequest).
     */
    public function store(StoreCommentRequest $request)
    {
        // Se usa el request controller eliminando la logica del negocio
        $comment = $request->user()->comments()->create($request->validated());

        // Notificar en tiempo real a otros usuarios
        $post = $comment->post;
        broadcast(new CommentCreated($post, $post->comments()->count()))->toOthers();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Comentario guardado']);
        }
        
        return back()->with('status', 'Comentario guardado exitosamente');
    }

    /**
     * Alterna el Like en un comentario (Polimórfico).
     */
    public function like(Comment $comment, Request $request)
    {
        // Reutilizamos el LikeService que ya es polimórfico
        $result = $this->likeService->toggleLike($comment, $request->user()->id);
        
        return response()->json($result);
    }

    /**
     * Elimina el comentario verificando permisos.
     */
    public function destroy(Comment $comment)
    {
        // En tu proyecto usas 'delete-comment' o una Policy
        Gate::authorize('delete', $comment); 
        
        $comment->delete();
        
        return back()->with('status', 'Comentario eliminado');
    }
}
