<!-- Comments Section -->
<div class="px-8 py-8 border-t border-gray-200">
    <div class="max-w-3xl mx-auto">
        <!-- Comments Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-gray-900">Comments</h3>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-50 text-red-600">
                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    {{ $blogPost->comments->count() }}
                </span>
            </div>
        </div>

        @auth
            <div class="mb-8">
                <form action="{{ route('comments.store', $blogPost) }}" method="POST" class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    @csrf
                    <div class="mb-3">
                        <label for="body" class="block text-sm font-medium text-gray-900 mb-2">Comment</label>
                        <textarea name="body" id="body" rows="4" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <x-primary-button>
                            {{ __('Post Comment') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
            @else
            <!-- Login Prompt -->
            <div class="mb-8 bg-gradient-to-r bg-gray-50 border border-gray-200 rounded-xl p-6">
                <div class="flex items-center justify-center text-center">
                    <div>
                        <svg class="mx-auto h-8 w-8 text-red-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-gray-700 mb-3">Join the discussion and share your thoughts!</p>
                        <a href="{{ route('login', ['redirect' => url()->current()]) }}" 
                           class="px-4 py-2 bg-red-600 border border-transparent rounded-3xl text-sm font-medium text-white hover:bg-red-700 transition-colors duration-200">
                            Sign in to comment
                        </a>
                    </div>
                </div>
            </div>
        @endauth

        <!-- Comments List -->
        @if($blogPost->comments->count() > 0)
            <div class="space-y-6">
                @foreach($blogPost->comments as $comment)
                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-start space-x-4">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <h4 class="font-semibold text-gray-900">{{ $comment->author_name }}</h4>
                                        <div class="flex items-center text-sm text-gray-500">
                                            {{ $comment->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    @if(auth()->id() === $comment->user_id)
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this comment? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors duration-200">
                                                <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <div class="prose prose-sm max-w-none">
                                    <p class="text-gray-700 leading-relaxed">{{ $comment->body }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <h4 class="text-lg font-medium text-gray-900 mb-2">No comments yet</h4>
                <p class="text-gray-500 mb-4">Be the first to share your thoughts on this post!</p>
            </div>
        @endif
    </div>
</div> 