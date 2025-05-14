<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        
        // Create regular users
        $users = User::factory(10)->create();
        
        // Create categories
        $categories = Category::factory(8)->create();
        
        // Create posts with categories
        $posts = Post::factory(50)
            ->recycle([$admin, ...$users])
            ->create()
            ->each(function (Post $post) use ($categories, $users) {
                // Associate random categories with posts
                $post->categories()->attach(
                    $categories->random(rand(1, 3))->pluck('id')->toArray()
                );
            });
            
        // Create comments for posts
        $posts->each(function (Post $post) use ($users) {
            // Create regular comments
            $commentCount = rand(0, 8);
            if ($commentCount > 0) {
                Comment::factory($commentCount)
                    ->recycle($users)
                    ->for($post)
                    ->create();
                    
                // After creating comments, now we can add replies
                $replyCount = rand(0, 3);
                if ($replyCount > 0) {
                    // Get parent comments to attach replies to
                    $parentComments = $post->comments()->whereNull('parent_id')->get();
                    
                    if ($parentComments->count() > 0) {
                        // Create replies
                        foreach (range(1, $replyCount) as $i) {
                            $parentComment = $parentComments->random();
                            Comment::factory()
                                ->recycle($users)
                                ->for($post)
                                ->create([
                                    'parent_id' => $parentComment->id
                                ]);
                        }
                    }
                }
            }
        });
    }
}
