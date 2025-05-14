<x-blog.layout>
    <x-slot name="title">{{ $post->title }}</x-slot>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-4xl mx-auto">
            <!-- Post Header -->
            <div class="mb-8">
                @if($post->categories->count() > 0)
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($post->categories as $category)
                            <a href="{{ route('blog.category', $category) }}" class="inline-block px-3 py-1 text-sm font-medium bg-indigo-100 text-indigo-800 rounded-md hover:bg-indigo-200">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
                
                <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl mb-4">
                    {{ $post->title }}
                </h1>
                
                <div class="flex items-center text-gray-500 text-sm mb-6">
                    <span>By {{ $post->user->name }}</span>
                    <span class="mx-2">&bull;</span>
                    <span>{{ $post->published_at->format('F j, Y') }}</span>
                    <span class="mx-2">&bull;</span>
                    <span>{{ $post->comments->count() }} {{ Str::plural('comment', $post->comments->count()) }}</span>
                </div>
                
                @if($post->featured_image)
                    <div class="mb-8 rounded-lg overflow-hidden shadow-lg">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-auto">
                    </div>
                @endif
                
                @if($post->excerpt)
                    <div class="text-xl text-gray-600 mb-8 border-l-4 border-indigo-500 pl-4 italic">
                        {{ $post->excerpt }}
                    </div>
                @endif
            </div>
            
            <!-- Post Content -->
            <div class="prose prose-indigo max-w-none mb-12">
                {!! $post->content !!}
            </div>
            
            <!-- Author Info -->
            <div class="bg-gray-50 rounded-lg p-6 mb-12 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xl">
                            {{ substr($post->user->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $post->user->name }}</h3>
                        <div class="text-gray-500 text-sm">
                            <p>Author</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Comments Section -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ $post->comments->count() }} {{ Str::plural('Comment', $post->comments->count()) }}
                    </h3>
                </div>
                
                <!-- Comment Form -->
                @auth
                    <div class="p-6 border-b border-gray-200">
                        <form action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            
                            <div class="mb-4">
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Add a comment</label>
                                <textarea id="content" name="content" rows="3" required class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:shadow-outline-indigo transition ease-in-out duration-150">
                                    Post Comment
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <p class="text-gray-600">
                            Please <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800">log in</a> to post a comment.
                        </p>
                    </div>
                @endauth
                
                <!-- Comments List -->
                <div class="divide-y divide-gray-200">
                    @forelse($post->comments as $comment)
                        <div class="p-6" id="comment-{{ $comment->id }}">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-700 font-bold">
                                        {{ substr($comment->user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $comment->user->name }}</h4>
                                            <p class="text-xs text-gray-500">{{ $comment->created_at->format('F j, Y g:i a') }}</p>
                                        </div>
                                        @auth
                                            @if(auth()->id() === $comment->user_id || auth()->user()->hasRole('admin'))
                                                <div x-data="{ open: false }" @click.away="open = false" class="relative inline-block text-left">
                                                    <button @click="open = !open" type="button" class="inline-flex justify-center items-center text-gray-400 hover:text-gray-500">
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                                        </svg>
                                                    </button>
                                                    <div x-cloak x-show="open" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                                        <div class="py-1" role="menu" aria-orientation="vertical">
                                                            <a href="{{ route('comments.edit', $comment) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Edit</a>
                                                            
                                                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="block">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100" role="menuitem" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                                                            </form>
                                                            
                                                            @if(auth()->user()->hasRole('admin'))
                                                                <form action="{{ route('comments.toggle-approval', $comment) }}" method="POST" class="block">
                                                                    @csrf
                                                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                                                        {{ $comment->is_approved ? 'Unapprove' : 'Approve' }}
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                    <div class="mt-2 text-sm text-gray-700">
                                        <p>{{ $comment->content }}</p>
                                    </div>
                                    
                                    <!-- Comment Replies -->
                                    @if($comment->replies && $comment->replies->count() > 0)
                                        <div class="mt-4 space-y-4 pl-6 border-l-2 border-gray-100">
                                            @foreach($comment->replies as $reply)
                                                <div class="flex items-start space-x-4" id="comment-{{ $reply->id }}">
                                                    <div class="flex-shrink-0">
                                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-700 font-bold text-xs">
                                                            {{ substr($reply->user->name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow">
                                                        <div class="flex items-center justify-between">
                                                            <div>
                                                                <h4 class="text-sm font-medium text-gray-900">{{ $reply->user->name }}</h4>
                                                                <p class="text-xs text-gray-500">{{ $reply->created_at->format('F j, Y g:i a') }}</p>
                                                            </div>
                                                            @auth
                                                                @if(auth()->id() === $reply->user_id || auth()->user()->hasRole('admin'))
                                                                    <div x-data="{ open: false }" @click.away="open = false" class="relative inline-block text-left">
                                                                        <button @click="open = !open" type="button" class="inline-flex justify-center items-center text-gray-400 hover:text-gray-500">
                                                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                                                            </svg>
                                                                        </button>
                                                                        <div x-cloak x-show="open" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                                                            <div class="py-1" role="menu" aria-orientation="vertical">
                                                                                <a href="{{ route('comments.edit', $reply) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Edit</a>
                                                                                
                                                                                <form action="{{ route('comments.destroy', $reply) }}" method="POST" class="block">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100" role="menuitem" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                                                                                </form>
                                                                                
                                                                                @if(auth()->user()->hasRole('admin'))
                                                                                    <form action="{{ route('comments.toggle-approval', $reply) }}" method="POST" class="block">
                                                                                        @csrf
                                                                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                                                                            {{ $reply->is_approved ? 'Unapprove' : 'Approve' }}
                                                                                        </button>
                                                                                    </form>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endauth
                                                        </div>
                                                        <div class="mt-2 text-sm text-gray-700">
                                                            <p>{{ $reply->content }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    <!-- Reply Form -->
                                    @auth
                                        <div x-data="{ open: false }">
                                            <button @click="open = !open" class="mt-2 text-xs text-indigo-600 hover:text-indigo-800 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                </svg>
                                                Reply
                                            </button>
                                            
                                            <div x-cloak x-show="open" class="mt-3">
                                                <form action="{{ route('comments.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                    
                                                    <div class="mb-3">
                                                        <label for="reply-content-{{ $comment->id }}" class="sr-only">Your reply</label>
                                                        <textarea id="reply-content-{{ $comment->id }}" name="content" rows="2" required class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"></textarea>
                                                    </div>
                                                    
                                                    <div class="flex justify-end">
                                                        <button type="button" @click="open = false" class="mr-2 inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                                            Cancel
                                                        </button>
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                                                            Post Reply
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            No comments yet. Be the first to share your thoughts!
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-blog.layout>