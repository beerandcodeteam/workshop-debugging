@props(['post'])

<article class="bg-white rounded-xl shadow-sm overflow-hidden transition duration-300 hover:shadow-md group">
    <div class="flex flex-col h-full">
        <!-- Featured Image -->
        @if($post->featured_image)
            <div class="h-48 overflow-hidden">
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" 
                    class="w-full h-full object-cover object-center transform group-hover:scale-105 transition duration-300" />
            </div>
        @else
            <div class="h-48 bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center transform group-hover:scale-105 transition duration-300">
                <svg class="h-16 w-16 text-white opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
        @endif
        
        <div class="p-6 flex-grow flex flex-col">
            <!-- Categories -->
            @if($post->categories->count() > 0)
                <div class="flex flex-wrap gap-2 mb-3">
                    @foreach($post->categories as $category)
                        <a href="{{ route('blog.category', $category) }}" class="inline-block px-2 py-1 text-xs font-medium bg-indigo-100 text-indigo-800 rounded-full hover:bg-indigo-200 transition duration-200">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif
            
            <!-- Title -->
            <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition duration-200">
                <a href="{{ route('blog.post', $post) }}" class="stretched-link">
                    {{ $post->title }}
                </a>
            </h2>
            
            <!-- Post Meta -->
            <div class="flex items-center text-gray-500 text-sm mb-3">
                <div class="flex-shrink-0 h-7 w-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs mr-2">
                    {{ substr($post->user->name, 0, 1) }}
                </div>
                <span>{{ $post->user->name }}</span>
                <span class="mx-2">&bull;</span>
                <span>{{ $post->published_at->format('M d, Y') }}</span>
            </div>
            
            <!-- Excerpt -->
            <div class="flex-grow mb-4">
                @if($post->excerpt)
                    <p class="text-gray-600 line-clamp-3">{{ $post->excerpt }}</p>
                @else
                    <p class="text-gray-600 line-clamp-3">{{ Str::limit(strip_tags($post->content), 150) }}</p>
                @endif
            </div>
            
            <!-- Footer -->
            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">
                <span class="text-sm text-gray-500 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    {{ $post->comments->count() }} {{ Str::plural('comment', $post->comments->count()) }}
                </span>
                
                <span class="text-sm font-medium text-indigo-600 group-hover:text-indigo-800 transition duration-200">Read article →</span>
            </div>
        </div>
    </div>
</article>