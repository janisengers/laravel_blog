<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {       
        $categories = Category::all();
        
        return view('blog-posts.index', compact('categories'));
    }

     /**
     * API endpoint for fetching blog posts
     */
    public function ajaxList(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = BlogPost::with(['user', 'categories'])->withCount(['comments']);
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('categories') && !empty($request->categories)) {
            $categories = is_array($request->categories) ? $request->categories : [$request->categories];
            $query->whereHas('categories', function($q) use ($categories) {
                $q->whereIn('categories.id', $categories);
            });
        }
        
        $page = max(1, (int) $request->get('page', 1));
        $blogPosts = $query->latest()->paginate(8, ['*'], 'page', $page);
        
        $blogPosts->getCollection()->transform(function ($post) {
            $post->append(['formatted_created_at', 'time_ago']);
            $post->url = route('blog-posts.show', $post->id);
            return $post;
        });
        
        return response()->json($blogPosts);
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogPost $blogPost): View
    {
        $blogPost->load(['comments.user', 'categories']);
        
        return view('blog-posts.show', compact('blogPost'));
    }

    /**
     * Display a listing of the resource.
     */
    public function myPosts(): View
    {
        $blogPosts = auth()->user()->blogPosts()->latest()->paginate(10);
        
        return view('blog-posts.my-posts', compact('blogPosts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::all();
        return view('blog-posts.create', compact('categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogPost $blogPost): View
    {
        if ($blogPost->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = Category::all();
        return view('blog-posts.edit', compact('blogPost', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
        ]);

        $blogPost = auth()->user()->blogPosts()->create([
            'title' => $validated['title'],
            'body' => $validated['body'],
        ]);

        if (isset($validated['categories'])) {
            $blogPost->categories()->attach($validated['categories']);
        }

        return redirect()->route('blog-posts.show', $blogPost)
            ->with('success', 'Blog post created successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BlogPost $blogPost): RedirectResponse
    {
        if ($blogPost->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
        ]);

        $blogPost->update([
            'title' => $validated['title'],
            'body' => $validated['body'],
        ]);

        $blogPost->categories()->sync($validated['categories'] ?? []);

        return redirect()->route('blog-posts.show', $blogPost)
            ->with('success', 'Blog post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        if ($blogPost->user_id !== auth()->id()) {
            abort(403);
        }

        $blogPost->delete();

        return redirect()->route('blog-posts.index')
            ->with('success', 'Blog post deleted successfully!');
    }
}
