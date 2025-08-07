@props(['blogPost' => null, 'categories'])

@php
    $isEditing = $blogPost !== null;
    $formAction = $isEditing ? route('blog-posts.update', $blogPost) : route('blog-posts.store');
    $heading = $isEditing ? 'Edit Blog Post' : 'Create New Blog Post';
    $subheading = $isEditing ? 'Fill in the details below to update your blog post.' : 'Fill in the details below to create your blog post.';
    $selectedCategories = $isEditing ? $blogPost->categories->pluck('id')->toArray() : [];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __($heading) }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    {{ __($subheading) }}
                </p>
            </div>
            <a href="{{ route('blog-posts.my-posts') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                My Posts
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">

                <div class="p-6">
                    <form action="{{ $formAction }}" method="POST">
                        @csrf
                        @if($isEditing)
                            @method('PUT')
                        @endif

                        <div class="space-y-6">
                            <!-- Title Field -->
                            <div>
                                <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">
                                    <span class="flex items-center">Post Title *</span>
                                </label>
                                <input type="text" name="title" id="title" 
                                       value="{{ old('title', $blogPost?->title) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200 @error('title') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                       placeholder="Enter a compelling title for your blog post"
                                       required>
                                @error('title')
                                    <div class="mt-2 flex items-center text-sm text-red-600">
                                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Content Field -->
                            <div>
                                <label for="body" class="block text-sm font-semibold text-gray-900 mb-2">
                                    <span class="flex items-center">Post Content *</span>
                                </label>
                                <textarea name="body" id="body" rows="12" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200 @error('body') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                          placeholder="Write your blog post content here. Share your thoughts, insights, and ideas..."
                                          required>{{ old('body', $blogPost?->body) }}</textarea>
                                @error('body')
                                    <div class="mt-2 flex items-center text-sm text-red-600">
                                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Categories Field -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-3">
                                    <span class="flex items-center">Categories</span>
                                </label>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <p class="text-xs text-gray-600 mb-3">Select one or more categories that best describe your post</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                        @foreach($categories as $category)
                                            <label class="inline-flex items-center p-2 rounded-md hover:bg-white transition-colors duration-200 cursor-pointer">
                                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                                       class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                                                       {{ in_array($category->id, old('categories', $selectedCategories)) ? 'checked' : '' }}>
                                                <span class="ml-3 text-sm text-gray-700 font-medium">{{ $category->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                @error('categories')
                                    <div class="mt-2 flex items-center text-sm text-red-600">
                                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-wrap gap-2 items-center justify-between pt-6 border-t border-gray-200 mt-8">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Fields marked with * are required
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('blog-posts.my-posts') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-green-700 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    Save Post
                                </button>                             
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 