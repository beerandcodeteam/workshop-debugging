<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display posts by category
     */
    public function show(Category $category)
    {
        $posts = $category->posts()
            ->with('user')
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(10);
            
        return view('blog.category', compact('category', 'posts'));
    }
}