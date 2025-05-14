<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Only admin can see a list of all comments
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $comments = Comment::with(['post', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('comments.index', compact('comments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Creating a comment is done on the post page, so this method isn't needed
        return redirect()->route('posts.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request)
    {
        $comment = Comment::create($request->validated());
        
        $post = Post::findOrFail($request->post_id);
        
        return redirect()->route('posts.show', $post)
            ->with('success', 'Comment added successfully and awaiting approval.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        // Individual comments are shown on the post page, so redirect there
        return redirect()->route('posts.show', $comment->post_id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        // Ensure only comment owner or admin can edit
        if ($comment->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('comments.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request, Comment $comment)
    {
        // Ensure only comment owner or admin can update
        if ($comment->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $comment->update($request->validated());
        
        return redirect()->route('posts.show', $comment->post)
            ->with('success', 'Comment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        // Ensure only comment owner or admin can delete
        if ($comment->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $post = $comment->post;
        $comment->delete();
        
        return redirect()->route('posts.show', $post)
            ->with('success', 'Comment deleted successfully.');
    }
    
    /**
     * Toggle approval status.
     */
    public function toggleApproval(Comment $comment)
    {
        // Only admin can approve/disapprove comments
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $comment->is_approved = !$comment->is_approved;
        $comment->save();
        
        $status = $comment->is_approved ? 'approved' : 'unapproved';
        
        return back()->with('success', "Comment {$status} successfully.");
    }
}
