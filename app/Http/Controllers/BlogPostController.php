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
        $baseQuery = BlogPost::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $baseQuery->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('body', 'like', "%{$search}%");
            });
        }

        if ($request->has('categories') && !empty($request->categories)) {
            $categories = is_array($request->categories) ? $request->categories : [$request->categories];
            $baseQuery->whereHas('categories', function($q) use ($categories) {
                $q->whereIn('categories.id', $categories);
            });
        }

        $page = max(1, (int) $request->get('page', 1));
        $ids = $baseQuery->latest('blog_posts.id')->paginate(8, ['blog_posts.id'], 'page', $page);

        $posts = BlogPost::with(['user', 'categories'])->withCount('comments')
            ->whereIn('id', $ids->pluck('id'))
            ->get()
            ->keyBy('id');

        $ids->setCollection($ids->getCollection()->map(function ($post) use ($posts) {
            $fullPost = $posts[$post->id];
            $fullPost->append(['formatted_created_at', 'time_ago']);
            $fullPost->url = route('blog-posts.show', $post->id);
            return $fullPost;
        }));

        return response()->json($ids);
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
        $blogPost->delete();

        return redirect()->route('blog-posts.index')
            ->with('success', 'Blog post deleted successfully!');
    }
}
