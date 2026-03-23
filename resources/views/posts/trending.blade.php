<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white font-semibold transition-colors duration-300">
            {{ __('Tendencias del último dia') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Bloque de Posts Tendencia -->
            <section>
                <h3 class="text-lg font-bold mb-4 text-gray-600 dark:text-gray-400">¡Publicaciones que lo estan arrasando!</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($trendingPosts as $post)
                        @include('posts.inc.item', ['post' => $post])
                    @endforeach
                </div>
            </section>

            <!-- Bloque de Comentarios -->
            <section>
                <h3 class="text-lg font-bold mb-4 text-gray-600 dark:text-gray-400"> Comentarios más votados</h3>
                <div class="space-y-4">
                    @forelse($trendingComments as $comment)
                        <div class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-slate-800">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-semibold text-indigo-600">{{ $comment->user->name }}</span>
                                <span class="text-xs text-gray-400">En: {{ Str::limit($comment->post->body, 30) }}</span>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 italic">"{{ $comment->body }}"</p>
                            <div class="mt-2 flex items-center text-xs text-gray-500">
                                <span> ¡A {{ $comment->likes_count }} personas les encanta!</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No hay actividad reciente aún.</p>
                    @endforelse
                </div>
            </section>

        </div>
    </div>
</x-app-layout>
