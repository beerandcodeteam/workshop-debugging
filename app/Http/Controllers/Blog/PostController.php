<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        if (!$post->is_published) {
            abort(404);
        }
        
        $post->load(['user', 'categories', 'comments' => function($query) {
            $query->whereNull('parent_id')
                  ->where('is_approved', true)
                  ->with(['user', 'replies' => function($q) {
                      $q->where('is_approved', true)->with('user');
                  }]);
        }]);
        
        return view('blog.post', compact('post'));
    }
}