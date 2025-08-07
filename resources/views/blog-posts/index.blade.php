<x-app-layout>
    <x-slot name="header">
        <div x-data="categoryFilter()">
            <form method="GET" action="{{ route('blog-posts.index') }}" id="category-filter-form">
                <div class="relative">
                    <div class="relative">
                        <div class="absolute left-0 top-0 bottom-0 w-20 bg-gradient-to-r from-white via-white to-transparent z-20 pointer-events-none" 
                                x-show="showLeftArrow" x-transition></div>
                        
                        <div class="absolute right-0 top-0 bottom-0 w-20 bg-gradient-to-l from-white via-white to-transparent z-20 pointer-events-none" 
                                x-show="showRightArrow" x-transition></div>
                        
                        <button type="button" 
                                class="absolute left-2 top-1/2 transform -translate-y-1/2 z-30 text-gray-600 hover:text-gray-800 transition-colors duration-200"
                                @click="scrollCategories('left')"
                                x-show="showLeftArrow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        
                        <button type="button" 
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 z-30 text-gray-600 hover:text-gray-800 transition-colors duration-200"
                                @click="scrollCategories('right')"
                                x-show="showRightArrow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        
                        <div class="flex gap-3 overflow-x-auto scrollbar-hide px-4" 
                                x-ref="categoriesContainer"
                                style="scroll-behavior: smooth;">
                            @foreach($categories as $category)
                                <div class="relative cursor-pointer flex-shrink-0" 
                                        @click="toggleCategory($el, $event)">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                            class="sr-only"
                                            :checked="isCategoryActive({{ $category->id }})">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 whitespace-nowrap"
                                            :class="isCategoryActive({{ $category->id }}) ? 'bg-red-600 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'">
                                        {{ $category->name }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="max-w-3xl mx-auto" x-data="list()">                
                <div class="space-y-4 border-t border-gray-100" id="posts-container">
                    <template x-for="post in $store.posts.posts" :key="post.id">
                        <div class="border-b border-gray-100">
                            <a :href="post.url" class="group block hover:shadow-md transition-shadow duration-200">
                                <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                                    <div class="flex items-start justify-between mb-3">
                                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-red-600 transition-colors duration-200 flex-1 pr-4">
                                            <span x-text="post.title"></span>
                                        </h3>
                                        <div class="flex items-center text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            <span x-text="post.comments_count || 0"></span>
                                        </div>
                                    </div>
                                    <p class="text-gray-600 text-sm mb-4 leading-relaxed" x-text="truncateText(post.body)"></p>
                                    <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                        <div class="flex items-center">
                                            <span x-text="`By ${post.user.name}`"></span>
                                        </div>
                                        <span x-text="post.time_ago"></span>
                                    </div>
                                    <template x-if="post.categories.length > 0">
                                        <div class="flex flex-wrap gap-1.5">
                                            <template x-for="category in post.categories" :key="category.id">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-50 text-gray-700 border border-gray-600" x-text="category.name"></span>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </a>
                        </div>
                    </template>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"  x-show="showNoPostsMessage" x-cloak>
                        <div class="p-6 text-center">
                            <p class="text-gray-500 mb-4">No blog posts found.</p>
                        </div>
                    </div>
                    <div class="mt-6 text-center">
                        <div x-show="$store.posts.loading" class="mt-4">
                            <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                            <span class="ml-2 text-gray-600">Loading...</span>
                        </div>
                    </div>
                </div>              
            </div>
        </div>
    </div>
</x-app-layout> 