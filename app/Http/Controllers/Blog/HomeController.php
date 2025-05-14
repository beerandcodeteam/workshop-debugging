<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display blog home page
     */
    public function index()
    {
        $posts = Post::with(['user', 'categories'])
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(10);
        
        $categories = Category::orderBy('name')->get();
        
        return view('blog.home', compact('posts', 'categories'));
    }
}