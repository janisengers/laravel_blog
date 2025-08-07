<?php

namespace App\Http\Middleware;

use App\Models\BlogPost;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBlogPostOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var BlogPost|null $blogPost */
        $blogPost = $request->route('blog_post');

        if (!$blogPost instanceof BlogPost) {
            abort(404);
        }

        if (!auth()->check() || $blogPost->user_id !== auth()->id()) {
            abort(403);
        }

        return $next($request);
    }
}