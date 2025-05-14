<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $post->title }}
            </h2>
            <div class="flex space-x-2">
                @if($post->is_published)
                    <a href="{{ route('blog.post', $post) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700" target="_blank">
                        View on Blog
                    </a>
                @endif
                
                <a href="{{ route('posts.edit', $post) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Edit Post
                </a>
                
                <form action="{{ route('posts.toggle-publish', $post) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 border {{ $post->is_published ? 'bg-yellow-600 hover:bg-yellow-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }} rounded-md font-semibold text-xs uppercase tracking-widest">
                        {{ $post->is_published ? 'Unpublish' : 'Publish' }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            <!-- Post Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $post->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $post->is_published ? 'Published' : 'Draft' }}
                            </span>
                            
                            @foreach($post->categories as $category)
                                <a href="{{ route('categories.show', $category) }}" class="inline-block px-3 py-1 text-sm font-medium bg-indigo-100 text-indigo-800 rounded-md hover:bg-indigo-200">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                        
                        @if($post->featured_image)
                            <div class="mb-6 rounded-lg overflow-hidden shadow-md">
                                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-auto">
                            </div>
                        @endif
                        
                        <div class="text-sm text-gray-500 mb-4">
                            <p>By: {{ $post->user->name }}</p>
                            <p>Created: {{ $post->created_at->format('F j, Y g:i a') }}</p>
                            @if($post->published_at)
                                <p>Published: {{ $post->published_at->format('F j, Y g:i a') }}</p>
                            @endif
                            <p>Comments: {{ $post->comments->count() }}</p>
                        </div>
                        
                        @if($post->excerpt)
                            <div class="text-lg italic text-gray-700 mb-4 border-l-4 border-indigo-500 pl-4">
                                {{ $post->excerpt }}
                            </div>
                        @endif
                        
                        <div class="prose max-w-none">
                            {!! $post->content !!}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Comments Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        Comments ({{ $post->comments->count() }})
                    </h3>
                </div>
                
                <div class="p-6">
                    @foreach($post->comments->whereNull('parent_id') as $comment)
                        <div class="mb-6 pb-6 {{ !$loop->last ? 'border-b border-gray-200' : '' }}" id="comment-{{ $comment->id }}">
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
                                            <p class="text-xs text-gray-500">
                                                {{ $comment->created_at->format('F j, Y g:i a') }}
                                                @if(!$comment->is_approved)
                                                    <span class="ml-2 text-yellow-600 font-medium">Pending Approval</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="flex space-x-2">
                                            @if(auth()->id() === $comment->user_id || auth()->user()->hasRole('admin'))
                                                <a href="{{ route('comments.edit', $comment) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Edit</a>
                                                
                                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                                                </form>
                                            @endif
                                            
                                            @if(auth()->user()->hasRole('admin'))
                                                <form action="{{ route('comments.toggle-approval', $comment) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-sm {{ $comment->is_approved ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}">
                                                        {{ $comment->is_approved ? 'Unapprove' : 'Approve' }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-2 text-gray-700">
                                        <p>{{ $comment->content }}</p>
                                    </div>
                                    
                                    <!-- Replies -->
                                    @if($comment->replies->count() > 0)
                                        <div class="mt-4 pl-6 border-l-2 border-gray-100 space-y-4">
                                            @foreach($comment->replies as $reply)
                                                <div class="pb-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}" id="comment-{{ $reply->id }}">
                                                    <div class="flex items-start space-x-3">
                                                        <div class="flex-shrink-0">
                                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-700 font-bold text-sm">
                                                                {{ substr($reply->user->name, 0, 1) }}
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow">
                                                            <div class="flex items-center justify-between">
                                                                <div>
                                                                    <h4 class="text-sm font-medium text-gray-900">{{ $reply->user->name }}</h4>
                                                                    <p class="text-xs text-gray-500">
                                                                        {{ $reply->created_at->format('F j, Y g:i a') }}
                                                                        @if(!$reply->is_approved)
                                                                            <span class="ml-2 text-yellow-600 font-medium">Pending Approval</span>
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                                <div class="flex space-x-2">
                                                                    @if(auth()->id() === $reply->user_id || auth()->user()->hasRole('admin'))
                                                                        <a href="{{ route('comments.edit', $reply) }}" class="text-xs text-indigo-600 hover:text-indigo-900">Edit</a>
                                                                        
                                                                        <form action="{{ route('comments.destroy', $reply) }}" method="POST" class="inline">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="text-xs text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this reply?')">Delete</button>
                                                                        </form>
                                                                    @endif
                                                                    
                                                                    @if(auth()->user()->hasRole('admin'))
                                                                        <form action="{{ route('comments.toggle-approval', $reply) }}" method="POST" class="inline">
                                                                            @csrf
                                                                            <button type="submit" class="text-xs {{ $reply->is_approved ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}">
                                                                                {{ $reply->is_approved ? 'Unapprove' : 'Approve' }}
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="mt-1 text-sm text-gray-700">
                                                                <p>{{ $reply->content }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    <!-- Reply Form -->
                                    <div x-data="{ open: false }" class="mt-3">
                                        <button @click="open = !open" class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center">
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
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($post->comments->whereNull('parent_id')->count() === 0)
                        <div class="text-center py-4 text-gray-500">
                            No comments yet.
                        </div>
                    @endif
                    
                    <!-- Add New Comment -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Add a Comment</h4>
                        
                        <form action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            
                            <div class="mb-4">
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Comment</label>
                                <textarea id="content" name="content" rows="4" required 
                                    class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                @error('content')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:shadow-outline-indigo transition ease-in-out duration-150">
                                    Post Comment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>