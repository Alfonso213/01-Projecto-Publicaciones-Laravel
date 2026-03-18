<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
   use AuthorizesRequests;

   public function store(Request $request)
   {
      $request->validate(['body' => 'required']);

      //dd($request->only('body'));

      $request->user()->comments()->create($request->only('body'));
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
