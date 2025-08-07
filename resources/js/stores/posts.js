export const postsStore = () => ({
        loading: false,
        hasMorePages: false,
        currentPage: 0,
        searchQuery: '',
        selectedCategories: [],
        posts: [],

        setLoading(loading) {
            this.loading = loading;
        },
        
        buildParams(page = null) {
            const params = new URLSearchParams();
            
            if (page) params.set('page', page);
            if (this.searchQuery) params.set('search', this.searchQuery);
            this.selectedCategories.forEach(category => {
                params.append('categories[]', category);
            });
            
            return params;
        },
        
        async fetchPosts(params) {        
            const response = await fetch(`/ajax/blog-posts?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();                       
            return data;
        },
        
        async loadPosts(options = {}) {
            const { replace = true, page = null } = options;
            
            this.setLoading(true);
            
            if (replace) {
                this.currentPage = 1;
                this.posts = [];
            }
            
            const params = this.buildParams(page);
            const data = await this.fetchPosts(params);
            
            this.posts = this.posts.concat(data.data);
            this.hasMorePages = data.current_page < data.last_page;
            this.currentPage = data.current_page;
            this.setLoading(false);

            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('posts-loaded'));
            }, 100);
            
            return data;
        },
        
        async loadMore() {
            await this.loadPosts({ 
                replace: false, 
                page: this.currentPage + 1
            });
        }
}); 