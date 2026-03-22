<div x-data="comentariosForm({{ $post->id }})" class="mt-8 border-t border-gray-100 dark:border-slate-800 pt-6">
    <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100 mb-4">Comentarios</h3>

    {{-- Formulario para nuevo comentario --}}
    <form @submit.prevent="enviarComentario" class="mb-8">
        <div class="mb-3">
            <textarea 
                x-model="body" 
                rows="3" 
                class="w-full border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 placeholder-gray-400 dark:placeholder-slate-500"
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

    {{-- Listado de comentarios --}}
    <div class="space-y-4">
        @forelse($post->comments as $comment)
            <div class="bg-gray-50 dark:bg-slate-800/50 p-4 rounded-lg border border-gray-100 dark:border-slate-800">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="font-bold text-gray-900 dark:text-slate-100 text-sm">
                            {{ $comment->user->name }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-slate-400 ml-2">
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

                <p class="mt-2 text-sm text-gray-700 dark:text-slate-300">
                    {{ $comment->body }}
                </p>

                {{-- BLOQUE DE LIKES (SISTEMA POLIMÓRFICO) --}}
                <div x-data="{ 
                    liked: {{ $comment->likes->contains('user_id', auth()->id()) ? 'true' : 'false' }},
                    likesCount: {{ $comment->likes_count ?? $comment->likes->count() }},
                    loading: false
                }" class="mt-3 flex items-center">
                    <button @click="
                        if(loading) return;
                        loading = true;
                        try {
                            const response = await fetch('{{ route('comments.like', $comment->id) }}', {
                                method: 'POST',
                                headers: { 
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });
                            const data = await response.json();
                            liked = data.liked;
                            likesCount = data.likesCount;
                        } catch (e) { console.error(e) } finally { loading = false }
                    " class="flex items-center space-x-1 text-xs transition-colors p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-slate-800"
                    :class="liked ? 'bg-gray-100 dark:bg-slate-800' : 'text-gray-400 dark:text-slate-500'">
                        <i class="fa-heart" :class="liked ? 'fas text-red-500' : 'far hover:text-red-500'"></i>
                        <span :class="liked ? 'font-bold text-gray-900 dark:text-slate-100' : 'hover:text-slate-300'" x-text="likesCount"></span>
                    </button>
                </div>
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
                    const response = await fetch(`{{ route('comments.store') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ post_id: postId, body: this.body })
                    });

                    if (response.ok) {
                        this.body = '';
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
