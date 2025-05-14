<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:posts,slug'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
        ];
        
        // If we're updating an existing post, exclude the current slug from the unique rule
        if ($this->post) {
            $rules['slug'] = ['required', 'string', 'max:255', 'unique:posts,slug,' . $this->post->id];
        }
        
        return $rules;
    }
    
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->slug === null && $this->title) {
            $this->merge([
                'slug' => str()->slug($this->title),
            ]);
        }
        
        // Set user_id to the authenticated user if not provided
        if (!$this->has('user_id')) {
            $this->merge([
                'user_id' => auth()->id(),
            ]);
        }
        
        // Handle published_at field based on is_published
        if ($this->boolean('is_published') && !$this->has('published_at')) {
            $this->merge([
                'published_at' => now(),
            ]);
        } elseif (!$this->boolean('is_published')) {
            $this->merge([
                'published_at' => null,
            ]);
        }
    }
}
