export function searchBox(isIndexPage = true) {
    return {
        searchTimeout: null,
        isOnIndexPage: isIndexPage,
        
        init() {
            const input = this.$el.querySelector('input[name="search"]');
            if (input) {
                this.$store.posts.searchQuery = input.value;               
            }

            const url = new URL(window.location);
            url.searchParams.delete('search');
            window.history.replaceState({}, '', url);
        },
        
        searchPosts(query) {
            if (!this.isOnIndexPage) return;
            
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }
            
            this.searchTimeout = setTimeout(() => {
                this.$store.posts.searchQuery = query;
                this.$store.posts.loadPosts({ replace: true });
            }, 300);
        },
        
        submitSearch() {
            if (!this.isOnIndexPage) return;
            
            const input = this.$el.querySelector('input[name="search"]');
            const query = input ? input.value : '';
            this.$store.posts.searchQuery = query;
            this.$store.posts.loadPosts({ replace: true });
        },
        
        redirectToIndex() {
            // When called from input event, this.$el might be the input, so find the form
            const form = this.$el.closest('form');
            const input = form.querySelector('input[name="search"]');
            const query = input ? input.value.trim() : '';
            
            const actionUrl = form.action;
            
            if (query) {
                window.location.href = `${actionUrl}?search=${encodeURIComponent(query)}`;
            } else {
                window.location.href = actionUrl;
            }
        }
    }
} 