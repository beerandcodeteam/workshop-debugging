<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::with(['user', 'categories'])
            ->orderBy('created_at', 'desc');
        
        // Filter by status if provided
        if ($request->has('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } else if ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }
        
        // Filter by category if provided
        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }
        
        // User-specific posts or all posts for admin
        if (auth()->user()->hasRole('admin')) {
            // Admin sees all posts
            $posts = $query->paginate(10);
        } else {
            // Regular users only see their own posts
            $posts = $query->where('user_id', auth()->id())->paginate(10);
        }
        
        $categories = Category::orderBy('name')->get();
        
        return view('posts.index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        
        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $post = Post::create($request->validated());
        
        // Sync the categories
        if ($request->has('categories')) {
            $post->categories()->sync($request->categories);
        }
        
        return redirect()->route('posts.show', $post)
            ->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // Check if user can view this post
        if (!$post->is_published && $post->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        // Load the post with related data
        $post->load(['user', 'categories', 'comments' => function($query) {
            $query->whereNull('parent_id')
                  ->where('is_approved', true)
                  ->with(['user', 'replies' => function($q) {
                      $q->where('is_approved', true)->with('user');
                  }]);
        }]);
        
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Ensure only post owner or admin can edit
        if ($post->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $categories = Category::orderBy('name')->get();
        $selectedCategories = $post->categories->pluck('id')->toArray();
        
        return view('posts.edit', compact('post', 'categories', 'selectedCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, Post $post)
    {
        // Ensure only post owner or admin can update
        if ($post->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $post->update($request->validated());
        
        // Sync the categories
        if ($request->has('categories')) {
            $post->categories()->sync($request->categories);
        }
        
        return redirect()->route('posts.show', $post)
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Ensure only post owner or admin can delete
        if ($post->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $post->delete();
        
        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }
    
    /**
     * Toggle publish status.
     */
    public function togglePublish(Post $post)
    {
        // Ensure only post owner or admin can toggle publish status
        if ($post->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $post->is_published = !$post->is_published;
        $post->published_at = $post->is_published ? now() : null;
        $post->save();
        
        $status = $post->is_published ? 'published' : 'unpublished';
        
        return redirect()->route('posts.show', $post)
            ->with('success', "Post {$status} successfully.");
    }
}
