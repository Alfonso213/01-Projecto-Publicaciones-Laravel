<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Services\LikeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
   protected $likeService;

   public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }
    public function like(Comment $comment)
    {
      $result = $this->likeService->toggleLike($comment, Auth::id());
        
        return response()->json($result);
    }

   public function store(Request $request)
{
    // Validacion de body y post_id
    $request->validate([
        'body' => 'required',
        'post_id' => 'required|exists:posts,id'
    ]);
    // Creación del comentario con todas sus relaciones
    $request->user()->comments()->create([
        'body' => $request->body,
        'post_id' => $request->post_id
    ]);
    // Devolvemos JSON para JS
    if ($request->wantsJson()) {
        return response()->json(['message' => 'Comentario guardado']);
    }
    return back()->with('status', 'Comentario guardado exitosamente');
}

   public function destroy(Request $request, Comment $comment)
   {
    // Esto te dirá si el objeto $comment tiene datos o está vacío
    //dd($comment); 

    // Esto te dirá si los IDs coinciden
    //dd($request->user()->id, $comment->user_id);

    \Illuminate\Support\Facades\Gate::authorize('delete', $comment);
    $comment->delete();
    return back();
   }
}
