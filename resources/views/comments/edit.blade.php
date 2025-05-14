<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Comment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Comment on "{{ $comment->post->title }}"</h3>
                        <p class="text-sm text-gray-500">
                            By {{ $comment->user->name }} on {{ $comment->created_at->format('F j, Y g:i a') }}
                        </p>
                        
                        @if($comment->parent_id)
                            <div class="mt-3 p-4 bg-gray-50 rounded-md">
                                <p class="text-sm text-gray-500 mb-1">In reply to:</p>
                                <p class="text-sm text-gray-700">{{ $comment->parent->content }}</p>
                                <p class="text-xs text-gray-500 mt-1">By {{ $comment->parent->user->name }}</p>
                            </div>
                        @endif
                    </div>
                    
                    <form action="{{ route('comments.update', $comment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                            <textarea id="content" name="content" rows="4" 
                                class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>{{ old('content', $comment->content) }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        @if(auth()->user()->hasRole('admin'))
                            <div class="mb-6">
                                <div class="flex items-center">
                                    <input type="checkbox" id="is_approved" name="is_approved" value="1" 
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        {{ old('is_approved', $comment->is_approved) ? 'checked' : '' }}>
                                    <label for="is_approved" class="ml-2 text-sm text-gray-700">Approved</label>
                                </div>
                                @error('is_approved')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                        
                        <div class="flex items-center justify-end">
                            <a href="{{ route('posts.show', $comment->post) }}#comment-{{ $comment->id }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 mr-2">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:shadow-outline-indigo transition ease-in-out duration-150">
                                Update Comment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>