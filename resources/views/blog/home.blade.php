<x-blog.layout>
    <x-slot name="title">Home</x-slot>
    <x-slot name="heroTitle">Explore Our Stories</x-slot>
    <x-slot name="heroSubtitle">Discover articles, insights, and knowledge from our community</x-slot>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($posts->count() > 0)
            <!-- Featured Post -->
            @php $featuredPost = $posts->shift(); @endphp
            <div class="mb-12">
                <div class="bg-white rounded-xl shadow-md overflow-hidden transition-transform hover:shadow-lg">
                    <div class="md:flex">
                        <div class="md:flex-shrink-0 md:w-2/5">
                            @if($featuredPost->featured_image)
                                <div class="h-64 md:h-full bg-indigo-100 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $featuredPost->featured_image) }}')"></div>
                            @else
                                <div class="h-64 md:h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                    <svg class="h-16 w-16 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-8 md:w-3/5">
                            <div class="flex flex-wrap gap-2 mb-3">
                                @foreach($featuredPost->categories as $category)
                                    <a href="{{ route('blog.category', $category) }}" class="inline-block px-2 py-1 text-xs font-medium bg-indigo-100 text-indigo-800 rounded-md hover:bg-indigo-200">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                                <span class="inline-block px-2 py-1 text-xs font-medium bg-rose-100 text-rose-800 rounded-md">
                                    Featured
                                </span>
                            </div>
                            
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                                <a href="{{ route('blog.post', $featuredPost) }}" class="hover:text-indigo-600 transition duration-200">
                                    {{ $featuredPost->title }}
                                </a>
                            </h2>
                            
                            <div class="flex items-center mb-4 text-sm text-gray-500">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold mr-2">
                                    {{ substr($featuredPost->user->name, 0, 1) }}
                                </div>
                                <span>{{ $featuredPost->user->name }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $featuredPost->published_at->format('M d, Y') }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $featuredPost->comments->count() }} {{ Str::plural('comment', $featuredPost->comments->count()) }}</span>
                            </div>
                            
                            <p class="text-gray-600 mb-6 line-clamp-3">
                                {{ $featuredPost->excerpt ?? Str::limit(strip_tags($featuredPost->content), 200) }}
                            </p>
                            
                            <a href="{{ route('blog.post', $featuredPost) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                                Read more
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Main Content -->
                <div class="lg:w-2/3">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Latest Posts</h2>
                        <div class="flex gap-2">
                            <!-- Category filter dropdown could go here -->
                        </div>
                    </div>
                    
                    <div class="grid gap-8 md:grid-cols-2">
                        @foreach($posts as $post)
                            <x-blog.post-card :post="$post" />
                        @endforeach
                    </div>
                    
                    <div class="mt-8">
                        {{ $posts->links() }}
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="lg:w-1/3 space-y-8">
                    <!-- Categories -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white">
                            <h3 class="text-lg font-bold">Explore Categories</h3>
                        </div>
                        <div class="p-6">
                            <ul class="space-y-3">
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{ route('blog.category', $category) }}" class="flex justify-between items-center text-gray-700 hover:text-indigo-600 transition duration-200 group">
                                            <span class="group-hover:translate-x-1 transition-transform duration-200">{{ $category->name }}</span>
                                            <span class="bg-indigo-100 text-indigo-700 text-xs font-medium px-2.5 py-1 rounded-full">
                                                {{ $category->posts->where('is_published', true)->count() }}
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    <!-- User Actions -->
                    @auth
                        <div class="bg-white rounded-xl shadow-md overflow-hidden">
                            <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-green-700 text-white">
                                <h3 class="text-lg font-bold">Dashboard</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3">
                                    <a href="{{ route('dashboard') }}" class="flex items-center text-gray-700 hover:text-green-600 transition duration-200 group">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                        <span class="group-hover:translate-x-1 transition-transform duration-200">Dashboard</span>
                                    </a>
                                    <a href="{{ route('posts.create') }}" class="flex items-center text-gray-700 hover:text-green-600 transition duration-200 group">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="group-hover:translate-x-1 transition-transform duration-200">Create New Post</span>
                                    </a>
                                    <a href="{{ route('posts.index') }}" class="flex items-center text-gray-700 hover:text-green-600 transition duration-200 group">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                        </svg>
                                        <span class="group-hover:translate-x-1 transition-transform duration-200">Manage Your Posts</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-xl shadow-md overflow-hidden">
                            <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                                <h3 class="text-lg font-bold">Join Our Community</h3>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-600 mb-6">Sign up to get started with writing your own posts and joining the conversation!</p>
                                <div class="space-y-3">
                                    <a href="{{ route('login') }}" class="block w-full px-4 py-2 bg-indigo-600 text-center border border-transparent rounded-md font-medium text-white hover:bg-indigo-700 transition duration-200">
                                        Log in
                                    </a>
                                    <a href="{{ route('register') }}" class="block w-full px-4 py-2 bg-white border border-indigo-600 text-center rounded-md font-medium text-indigo-600 hover:bg-indigo-50 transition duration-200">
                                        Register
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endauth
                    
                    <!-- Newsletter -->
                    <div class="bg-gradient-to-br from-indigo-900 to-purple-900 rounded-xl shadow-md overflow-hidden">
                        <div class="p-6 text-white">
                            <h3 class="text-lg font-bold mb-3">Subscribe to our Newsletter</h3>
                            <p class="text-indigo-200 text-sm mb-4">Get the latest posts and updates delivered to your inbox.</p>
                            <form class="space-y-3">
                                <input type="email" placeholder="Your email address" class="w-full px-4 py-2 rounded-md text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <button type="submit" class="w-full px-4 py-2 bg-indigo-500 border border-transparent rounded-md font-medium text-white hover:bg-indigo-400 transition duration-200">
                                    Subscribe
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-md p-12 text-center">
                <svg class="h-16 w-16 text-indigo-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No posts yet</h3>
                <p class="text-gray-600 mb-6">Check back soon for new content or write the first post!</p>
                
                @auth
                    <a href="{{ route('posts.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-bold text-white hover:bg-indigo-700 focus:outline-none transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Create Your First Post
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-bold text-white hover:bg-indigo-700 focus:outline-none transition duration-200">
                        Join and Start Writing
                    </a>
                @endauth
            </div>
        @endif
    </div>
</x-blog.layout>