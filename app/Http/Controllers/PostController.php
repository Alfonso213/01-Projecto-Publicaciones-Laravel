<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
   use AuthorizesRequests;
   public function index()
   {
      return view('posts.index', [
         'posts' => Post::with('user','comments.user')
            ->withCount('likes')
            ->latest()
            ->paginate(),
      ]);
   }

   public function store(Request $request)
   {
      $request->validate(['body' => 'required']);

      //dd($request->only('body'));

      $request->user()->posts()->create($request->only('body'));
      return back()->with('status', 'Publicacion guardada exitosamente');
   }

   public function destroy(Request $request, Post $post)
   {
    // Esto te dirá si el objeto $post tiene datos o está vacío
    //dd($post); 

    // Esto te dirá si los IDs coinciden
    //dd($request->user()->id, $post->user_id);

    \Illuminate\Support\Facades\Gate::authorize('delete', $post);
    $post->delete();
    return back();
   }

  
   public function like(Post $post)
   {
      $userHasLiked = $post->likes()->where('user_id', auth()->id())->exists();
      
      if ($userHasLiked) {
         // Elimina el like
         $post->likes()->where('user_id', auth()->id())->delete();
         $liked = false;
      } else {
         // Agrega un like
         $post->likes()->create(['user_id' => auth()->id()]);
         $liked = true;
      }
      
      // Recalcular el contador de likes
      $likesCount = $post->likes()->count();
      
      // Retornar JSON para AJAX (sin reload de página)
      return response()->json([
         'liked' => $liked,
         'likesCount' => $likesCount,
         'message' => $liked ? 'Like agregado' : 'Like removido'
      ]);
   }
}
