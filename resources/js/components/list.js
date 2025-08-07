export function list() {
    return {
        showNoPostsMessage: false,
        
        init() {
            this.$store.posts.hasMorePages = true;
            
            let throttleTimer = null;
            window.addEventListener('scroll', () => {
                if (throttleTimer) return;
                
                throttleTimer = setTimeout(() => {
                    this.checkScroll();
                    throttleTimer = null;
                }, 100);
            });
            
            window.addEventListener('posts-loaded', () => {
                this.showNoPostsMessage = !this.$store.posts.loading && this.$store.posts.posts.length === 0;
                this.checkAvailableSpace();
            });
            
            this.$nextTick(() => {
                this.checkAvailableSpace();
            });
        },
        
        checkScroll() {
            if (this.$store.posts.loading || !this.$store.posts.hasMorePages) return;
            
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;
            
            if (scrollTop + windowHeight >= documentHeight - 100) {
                this.$store.posts.loadMore();
            }
        },
        
        checkAvailableSpace() {
            if (this.$store.posts.loading || !this.$store.posts.hasMorePages) return;
            
            const postsContainer = document.getElementById('posts-container');
            const containerHeight = postsContainer.scrollHeight;
            const windowHeight = window.innerHeight;
            
            if (containerHeight < windowHeight) {
                this.$store.posts.loadMore();
            }
        },
        
        truncateText(text, maxLength = 150) {
            if (!text) return '';
            return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
        }
    }
} 