<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display blog home page
     */
    public function index(Request $request)
    {

        $posts = Post::with(['user', 'categories'])
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        $categories = Category::orderBy('name')->get();

        $result = Cache::get('posts');

        return view('blog.home', compact('posts', 'categories'));
    }
}
