<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-2">
            <nav class="flex items-center text-sm">
                <a href="{{ route('blog-posts.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Blog
                </a>
            </nav>
            @if(auth()->id() === $blogPost->user_id)
                <div class="flex items-center space-x-2">
                    <a href="{{ route('blog-posts.my-posts') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        My Posts
                    </a>
                    <a href="{{ route('blog-posts.edit', $blogPost) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('blog-posts.destroy', $blogPost) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this post? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                            <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-2 sm:py-8 bg-gray-50 min-h-screen">
        <article class="max-w-4xl mx-auto px-2 sm:px-4 lg:px-8">
            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex items-center">
                    <svg class="flex-shrink-0 mr-2 h-5 w-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-4 sm:px-8 pt-6 sm:pt-12 pb-8 bg-gradient-to-b from-white to-gray-50">
                    <header class="text-center max-w-3xl mx-auto">
                        <h1 class="text-3xl md:text-5xl font-bold text-gray-900 leading-tight mb-6">
                            {{ $blogPost->title }}
                        </h1>
                        
                        <div class="flex flex-col sm:flex-row items-center justify-center text-gray-600 space-y-2 sm:space-y-0 sm:space-x-6 mb-6">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $blogPost->author_name }}</p>
                                <p class="text-xs text-gray-500">Author</p>
                            </div>                     
                            
                            <div class="hidden sm:block w-px h-8 bg-gray-300"></div>
                            
                            <div class="text-center sm:text-left">
                                <div class="text-sm text-gray-600">
                                    {{ $blogPost->created_at->format('F d, Y') }}
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $blogPost->created_at->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($blogPost->categories->count() > 0)
                            <div class="flex flex-wrap justify-center gap-2">
                                @foreach($blogPost->categories as $category)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-50 text-red-600 border border-red-200">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </header>
                </div>

                <div class="px-4 sm:px-8 py-6 sm:py-12">
                    <div class="prose prose-lg prose-blue max-w-none">
                        <div class="text-gray-800 text-lg text-justify">
                            {!! nl2br(e($blogPost->body)) !!}
                        </div>
                    </div>
                </div>

                @include('blog-posts.partials.comments')

            </div>
        </article>
    </div>
</x-app-layout> 