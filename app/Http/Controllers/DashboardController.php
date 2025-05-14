<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        
        $data = [
            'postCount' => $user->posts()->count(),
            'publishedPostCount' => $user->posts()->where('is_published', true)->count(),
            'draftPostCount' => $user->posts()->where('is_published', false)->count(),
            'commentCount' => $user->comments()->count(),
            'latestPosts' => $user->posts()->latest()->take(5)->get(),
            'latestComments' => $user->comments()->latest()->take(5)->get(),
        ];
        
        if ($user->hasRole('admin')) {
            $data['categoryCount'] = Category::count();
            $data['totalPosts'] = Post::count();
            $data['totalComments'] = Comment::count();
            $data['pendingComments'] = Comment::where('is_approved', false)->count();
        }
        
        return view('dashboard', $data);
    }
}