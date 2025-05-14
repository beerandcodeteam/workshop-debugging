<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'post_id' => \App\Models\Post::factory(),
            'user_id' => \App\Models\User::factory(),
            'parent_id' => null,
            'content' => fake()->paragraph(),
            'is_approved' => fake()->boolean(90),
        ];
    }
    
    /**
     * Indicate that the comment is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => true,
        ]);
    }
    
    /**
     * Indicate that the comment is unapproved.
     */
    public function unapproved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
        ]);
    }
    
    /**
     * Define the comment as a reply to another comment.
     */
    public function asReply(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => function () use ($attributes) {
                    // This will be executed after the post is created
                    $post = \App\Models\Post::find($attributes['post_id']);
                    if ($post) {
                        $parentComment = $post->comments()
                            ->where('parent_id', null)
                            ->inRandomOrder()
                            ->first();
                        
                        return $parentComment ? $parentComment->id : null;
                    }
                    return null;
                },
            ];
        });
    }
}
