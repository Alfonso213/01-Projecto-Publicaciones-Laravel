<div x-data="comentariosForm({{ $post->id }})" class="mt-8 border-t border-gray-100 pt-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Comentarios</h3>

    <form @submit.prevent="enviarComentario" class="mb-8">
        <div class="mb-3">
            <textarea 
                x-model="body" 
                rows="3" 
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3"
                placeholder="Escribe un comentario..."
                required>
            </textarea>
        </div>
        <div class="flex justify-end">
            <button 
                type="submit" 
                class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition-colors"
                :disabled="!body.trim() || cargando">
                <span x-show="!cargando">Enviar comentario</span>
                <span x-show="cargando">Enviando...</span>
            </button>
        </div>
    </form>

    <div class="space-y-4">
        @forelse($post->comments as $comment)
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                <div class="flex justify-between items-start">
                    
                    <div>
                        <span class="font-bold text-gray-900 text-sm">
                            {{ $comment->user->name }}
                        </span>
                        <span class="text-xs text-gray-500 ml-2">
                            {{ $comment->created_at->diffForHumans() }}
                        </span>
                    </div>

                    @can('delete', $comment)
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-red-500 hover:text-red-700 text-xs font-medium hover:underline"
                                    onclick="return confirm('¿Estás seguro de eliminar este comentario?')">
                                Eliminar
                            </button>
                        </form>
                    @endcan
                </div>

                <p class="mt-2 text-sm text-gray-700">
                    {{ $comment->body }}
                </p>
            </div>
        @empty
            <p class="text-sm text-gray-500 text-center py-4">Aún no hay comentarios. ¡Sé el primero en comentar!</p>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('comentariosForm', (postId) => ({
            body: '',
            cargando: false,

            async enviarComentario() {
                if (!this.body.trim()) return;
                
                this.cargando = true;

                try {
                    // Petición AJAX (usando Fetch API)
                    const response = await fetch(`{{ route('comments.store') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            post_id: postId,
                            body: this.body
                        })
                    });

                    if (response.ok) {
                        this.body = ''; // Limpiar el textarea
                        // Aquí idealmente recargarías la lista de comentarios o añadirías el nuevo al DOM dinámicamente.
                        // Por simplicidad en este paso, recargamos la página para ver el nuevo comentario.
                        window.location.reload(); 
                    } else {
                        alert('Hubo un error al enviar el comentario.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error de conexión.');
                } finally {
                    this.cargando = false;
                }
            }
        }));
    });
</script>